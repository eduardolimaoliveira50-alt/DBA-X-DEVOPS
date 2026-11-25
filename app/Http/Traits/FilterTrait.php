<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Enums\FilterOperator;
use Spatie\QueryBuilder\QueryBuilder;

trait FilterTrait
{
    /**
     * Lista de colunas que devem usar pesquisa exata.
     * @var array<int, string>
     */
    protected array $exactMatchFields = ['plate_number', 'lumberLog_picking_list'];

    /**
     * @var array<string, array<string, string>>
     */
    private array $columnTypeCache = [];

    /**
     * @var array<string, array<int, string>>
     */
    private array $indexedColumnsCache = [];

    /**
     * @var array<string, array<int, string>>
     */
    private array $sortableColumnsCache = [];

    protected function filter(Builder $query, Request $request): void
    {
        $filters = $this->extractFiltersFromRequest($query, $request);
        $sortParameter = $this->sortParameterName();
        $sortableColumns = $this->getSortableColumns($query);
        $requestedSorts = $this->sanitizeRequestedSorts($request, $sortParameter, $sortableColumns);

        if ($filters !== []) {
            $request->query->set($this->filterParameterName(), $filters);
        } else {
            $request->query->remove($this->filterParameterName());
        }

        $queryBuilder = QueryBuilder::for($query, $request);

        if ($filters !== []) {
            $allowedFilters = array_map(
                fn(string $key) => $this->buildAllowedFilter($query, $key, $filters[$key]),
                array_keys($filters)
            );

            $queryBuilder->allowedFilters($allowedFilters);
        }

        $this->applySorting($queryBuilder, $query, $sortableColumns, $requestedSorts);
    }

    /**
     * @return array<string, mixed>
     */
    private function extractFiltersFromRequest(Builder $query, Request $request): array
    {
        $filters = $this->sanitizeFilterBag($request->query($this->filterParameterName(), []));
        $reservedKeys = $this->reservedQueryKeys();

        foreach ($request->query() as $key => $value) {
            if (in_array($key, $reservedKeys, true) || array_key_exists($key, $filters) || !$request->filled($key)) {
                continue;
            }

            $filters[$key] = $value;
        }

        $filters = $this->normalizeDateRangeFilters($query, $filters);

        $normalized = [];
        foreach ($filters as $key => $value) {
            if (!is_string($key)) {
                continue;
            }

            $sanitizedValue = $this->sanitizeFilterValue($value);
            if ($sanitizedValue === null) {
                continue;
            }

            $normalized[$key] = $sanitizedValue;
        }

        return $normalized;
    }

    /**
     * @param array<string, mixed> $filters
     * @return array<string, mixed>
     */
    private function normalizeDateRangeFilters(Builder $query, array $filters): array
    {
        foreach (array_keys($filters) as $key) {
            if (!is_string($key)) {
                continue;
            }

            $isStart = str_ends_with($key, '_start');
            $isEnd = str_ends_with($key, '_end');

            if (!$isStart && !$isEnd) {
                continue;
            }

            $baseColumn = $isStart
                ? substr($key, 0, -6)
                : substr($key, 0, -4);

            if (!$this->columnExists($query, $baseColumn) || !$this->isDateColumn($query, $baseColumn)) {
                continue;
            }

            $range = $filters[$baseColumn] ?? [];
            if (!is_array($range)) {
                $range = [];
            }

            $range[$isStart ? 'start' : 'end'] = $filters[$key];
            $filters[$baseColumn] = $range;
            unset($filters[$key]);
        }

        return $filters;
    }

    private function buildAllowedFilter(Builder $query, string $key, mixed $value): AllowedFilter
    {
        if (is_array($value) && $this->isDateRangePayload($value)) {
            return AllowedFilter::callback($key, function (Builder $subQuery, mixed $subValue) use ($key): void {
                if (!is_array($subValue)) {
                    return;
                }

                $this->handleDateRangeFilter($subQuery, $key, $subValue);
            })->ignore(null, '');
        }

        if (str_contains($key, '.')) {
            return AllowedFilter::callback($key, function (Builder $subQuery, mixed $subValue) use ($key): void {
                $scalarValue = $this->toScalarFilterValue($subValue);
                if ($scalarValue === null) {
                    return;
                }

                $this->handleDotRelationFilter($subQuery, $key, $scalarValue);
            })->ignore(null, '');
        }

        if ($this->isRelationFilter($query, $key) || $this->isSpecialRelationFilterKey($key)) {
            return AllowedFilter::callback($key, function (Builder $subQuery, mixed $subValue) use ($key): void {
                $scalarValue = $this->toScalarFilterValue($subValue);
                if ($scalarValue === null) {
                    return;
                }

                $this->handleRelationFilter($subQuery, $key, $scalarValue);
            })->ignore(null, '');
        }

        if (!$this->columnExists($query, $key)) {
            return AllowedFilter::callback($key, function (): void {
                // Mantém compatibilidade com comportamento antigo: ignora filtro inexistente.
            })->ignore(null, '');
        }

        $columnType = $this->getColumnType($query, $key);

        if ($this->isDateColumnType($columnType)) {
            return AllowedFilter::callback($key, function (Builder $subQuery, mixed $subValue) use ($key): void {
                $scalarValue = $this->toScalarFilterValue($subValue);
                if ($scalarValue === null) {
                    return;
                }

                $this->handleDateFilter($subQuery, $key, $scalarValue);
            })->ignore(null, '');
        }

        if ($this->shouldUseDynamicOperatorForValue($query, $key, $columnType, $value)) {
            return AllowedFilter::operator($key, FilterOperator::DYNAMIC)->ignore(null, '');
        }

        if ($this->shouldUseExactFilter($query, $key, $columnType)) {
            return AllowedFilter::exact($key)->ignore(null, '');
        }

        if ($this->shouldUseOperatorFilter($query, $key, $columnType)) {
            return AllowedFilter::operator($key, FilterOperator::DYNAMIC)->ignore(null, '');
        }

        if ($this->shouldUseBeginsWithStrictFilter($query, $key, $columnType)) {
            return AllowedFilter::beginsWithStrict($key)->ignore(null, '');
        }

        return AllowedFilter::partial($key)->ignore(null, '');
    }

    /**
     * @param array<int, string> $sortableColumns
     * @param array<int, string> $requestedSorts
     */
    private function applySorting(QueryBuilder $queryBuilder, Builder $query, array $sortableColumns, array $requestedSorts): void
    {
        if ($sortableColumns === []) {
            return;
        }

        $queryBuilder->allowedSorts($sortableColumns);

        if ($requestedSorts !== [] || $this->queryHasOrderBy($query)) {
            return;
        }

        $defaultSort = $this->resolveDefaultSort($query, $sortableColumns);
        if ($defaultSort !== null) {
            $queryBuilder->defaultSort($defaultSort);
        }
    }

    /**
     * @param array<int, string> $sortableColumns
     * @return array<int, string>
     */
    private function sanitizeRequestedSorts(Request $request, string $sortParameter, array $sortableColumns): array
    {
        $requestedSorts = $this->extractRequestedSorts($request, $sortParameter);
        if ($requestedSorts === []) {
            return [];
        }

        $sanitized = [];
        foreach ($requestedSorts as $sortItem) {
            $sortItem = trim($sortItem);
            if ($sortItem === '') {
                continue;
            }

            $descending = str_starts_with($sortItem, '-');
            $column = ltrim($sortItem, '-');

            if (!in_array($column, $sortableColumns, true)) {
                continue;
            }

            $sanitized[] = $descending ? "-{$column}" : $column;
        }

        if ($sanitized === []) {
            $request->query->remove($sortParameter);
            return [];
        }

        $request->query->set($sortParameter, implode(',', $sanitized));

        return $sanitized;
    }

    /**
     * @return array<int, string>
     */
    private function extractRequestedSorts(Request $request, string $sortParameter): array
    {
        $value = $request->query($sortParameter);

        if (is_string($value)) {
            return array_values(array_filter(array_map('trim', explode(',', $value))));
        }

        if (!is_array($value)) {
            return [];
        }

        $flattened = [];
        array_walk_recursive($value, function (mixed $item) use (&$flattened): void {
            if (is_scalar($item)) {
                $flattened[] = trim((string) $item);
            }
        });

        return array_values(array_filter($flattened, fn(string $item): bool => $item !== ''));
    }

    private function queryHasOrderBy(Builder $query): bool
    {
        $orders = $query->getQuery()->orders ?? [];

        return !empty($orders);
    }

    /**
     * @param array<int, string> $sortableColumns
     */
    private function resolveDefaultSort(Builder $query, array $sortableColumns): ?string
    {
        $keyName = $query->getModel()->getKeyName();

        foreach (['entry_date', 'created_at', $keyName] as $candidate) {
            if (is_string($candidate) && in_array($candidate, $sortableColumns, true)) {
                return "-{$candidate}";
            }
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    private function getSortableColumns(Builder $query): array
    {
        $cacheKey = $this->cacheKeyForBuilder($query);

        if (!array_key_exists($cacheKey, $this->sortableColumnsCache)) {
            $this->sortableColumnsCache[$cacheKey] = array_values(array_keys($this->getColumnTypes($query)));
        }

        return $this->sortableColumnsCache[$cacheKey];
    }

    /**
     * @param array<string, mixed> $value
     */
    private function isDateRangePayload(array $value): bool
    {
        return array_key_exists('start', $value) || array_key_exists('end', $value);
    }

    /**
     * @param array<string, mixed> $value
     */
    private function handleDateRangeFilter(Builder $query, string $column, array $value): void
    {
        if (!$this->columnExists($query, $column) || !$this->isDateColumn($query, $column)) {
            return;
        }

        $start = $this->normalizeDateValue($value['start'] ?? null);
        $end = $this->normalizeDateValue($value['end'] ?? null);

        if ($start !== null) {
            $query->whereDate($column, '>=', $start);
        }

        if ($end !== null) {
            $query->whereDate($column, '<=', $end);
        }
    }

    private function handleDotRelationFilter(Builder $query, string $key, string $value): void
    {
        [$relation, $field] = explode('.', $key, 2);

        if (!method_exists($query->getModel(), $relation)) {
            return;
        }

        $query->whereHas($relation, function (Builder $subQuery) use ($field, $value): void {
            $this->applyExactOrLikeFilter($subQuery, $field, $value);
        });
    }

    private function isRelationFilter(Builder $query, string $key): bool
    {
        if (!str_contains($key, '_')) {
            return false;
        }

        [$relation, $field] = $this->parseRelationAndField($key);

        return $relation !== '' && $field !== '' && method_exists($query->getModel(), $relation);
    }

    private function isSpecialRelationFilterKey(string $key): bool
    {
        return $key === 'company_name' || $key === 'companies_name';
    }

    private function isDateColumn(Builder $query, string $column): bool
    {
        return $this->isDateColumnType($this->getColumnType($query, $column));
    }

    private function handleRelationFilter(Builder $query, string $key, string $value): void
    {
        if ($this->isSpecialRelationFilterKey($key)) {
            $relation = Str::before($key, '_');
            if (!method_exists($query->getModel(), $relation)) {
                return;
            }

            $query->whereHas($relation, function (Builder $subQuery) use ($value): void {
                $subQuery->where('name', 'like', "%{$value}%");
            });
            return;
        }

        [$relation, $field] = $this->parseRelationAndField($key);
        $this->applySubqueryFilter($query, $relation, $field, $value);
    }

    private function handleDateFilter(Builder $query, string $column, string $value): void
    {
        [$operator, $dateValue] = $this->extractOperatorAndValue($value);
        $date = $this->normalizeDateValue($dateValue);

        if ($date !== null) {
            $query->whereDate($column, $operator, $date);
        }
    }

    private function handleColumnFilter(Builder $query, string $column, string $value): void
    {
        if (!$this->columnExists($query, $column)) {
            return;
        }

        $this->applyExactOrLikeFilter($query, $column, $value);
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function parseRelationAndField(string $key): array
    {
        $parts = explode('_', $key);
        $relation = (string) array_shift($parts);
        $field = implode('_', $parts);

        return [$relation, $field];
    }

    private function columnExists(Builder $query, string $column): bool
    {
        return array_key_exists($column, $this->getColumnTypes($query));
    }

    private function applyExactOrLikeFilter(Builder $query, string $column, string $value): void
    {
        $columnType = $this->getColumnType($query, $column);

        if ($this->shouldUseExactFilter($query, $column, $columnType)) {
            $query->where($column, '=', $value);
            return;
        }

        if ($this->shouldUseBeginsWithStrictFilter($query, $column, $columnType)) {
            $query->where($column, 'like', "{$value}%");
            return;
        }

        $query->where($column, 'like', "%{$value}%");
    }

    private function applySubqueryFilter(Builder $query, string $relation, string $relationKey, string $value): void
    {
        if (!method_exists($query->getModel(), $relation)) {
            return;
        }

        $query->whereHas($relation, function (Builder $subQuery) use ($relationKey, $value): void {
            if ($this->isDateColumn($subQuery, $relationKey)) {
                $this->handleDateFilter($subQuery, $relationKey, $value);
                return;
            }

            if ($this->columnExists($subQuery, $relationKey)) {
                $this->applyExactOrLikeFilter($subQuery, $relationKey, $value);
            }
        });
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function extractOperatorAndValue(string $value): array
    {
        $trimmedValue = trim($value);

        foreach (['>=', '<=', '<>', '!=', '>', '<', '='] as $operator) {
            if (!str_starts_with($trimmedValue, $operator)) {
                continue;
            }

            $rawValue = trim(substr($trimmedValue, strlen($operator)));
            if ($rawValue === '') {
                break;
            }

            return [$operator, $rawValue];
        }

        return ['=', $trimmedValue];
    }

    private function hasOperatorPrefix(string $value): bool
    {
        $trimmedValue = trim($value);

        foreach (['>=', '<=', '<>', '!=', '>', '<', '='] as $operator) {
            if (str_starts_with($trimmedValue, $operator)) {
                return true;
            }
        }

        return false;
    }

    private function normalizeDateValue(mixed $value): ?string
    {
        if (!is_scalar($value)) {
            return null;
        }

        $stringValue = trim((string) $value);
        if ($stringValue === '') {
            return null;
        }

        try {
            return Carbon::parse($stringValue)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    private function toScalarFilterValue(mixed $value): ?string
    {
        if (!is_scalar($value)) {
            return null;
        }

        $stringValue = trim((string) $value);

        return $stringValue === '' ? null : $stringValue;
    }

    private function sanitizeFilterValue(mixed $value): mixed
    {
        if (is_array($value)) {
            $sanitized = [];

            foreach ($value as $itemKey => $itemValue) {
                $normalizedValue = $this->sanitizeFilterValue($itemValue);
                if ($normalizedValue === null) {
                    continue;
                }

                $sanitized[$itemKey] = $normalizedValue;
            }

            return $sanitized === [] ? null : $sanitized;
        }

        if (!is_scalar($value)) {
            return null;
        }

        $stringValue = trim((string) $value);

        return $stringValue === '' ? null : $stringValue;
    }

    private function shouldUseExactFilter(Builder $query, string $column, ?string $columnType): bool
    {
        if (in_array($column, $this->exactMatchFields, true)) {
            return true;
        }

        if ($column === $query->getModel()->getKeyName() || str_ends_with($column, '_id')) {
            return true;
        }

        if ($query->getModel()->hasCast($column, ['bool', 'boolean'])) {
            return true;
        }

        if ($query->getModel()->hasCast($column, ['int', 'integer', 'real', 'float', 'double', 'decimal'])) {
            return true;
        }

        $cast = $query->getModel()->getCasts()[$column] ?? null;
        if (is_string($cast) && enum_exists($cast)) {
            return true;
        }

        if (in_array($columnType, ['boolean', 'bool', 'enum', 'set', 'uuid', 'ulid'], true)) {
            return true;
        }

        return false;
    }

    private function shouldUseOperatorFilter(Builder $query, string $column, ?string $columnType): bool
    {
        if ($this->shouldUseExactFilter($query, $column, $columnType)) {
            return false;
        }

        return $this->isNumericColumnType($columnType);
    }

    private function shouldUseDynamicOperatorForValue(Builder $query, string $column, ?string $columnType, mixed $value): bool
    {
        if (!$this->isNumericColumnType($columnType)) {
            return false;
        }

        if (!is_string($value) || !$this->hasOperatorPrefix($value)) {
            return false;
        }

        return $this->shouldUseExactFilter($query, $column, $columnType) || $this->shouldUseOperatorFilter($query, $column, $columnType);
    }

    private function shouldUseBeginsWithStrictFilter(Builder $query, string $column, ?string $columnType): bool
    {
        if (!$this->isStringColumnType($columnType)) {
            return false;
        }

        return $this->isIndexedColumn($query, $column);
    }

    private function isDateColumnType(?string $columnType): bool
    {
        return in_array($columnType, ['date', 'datetime', 'datetimetz', 'timestamp', 'timestampz'], true);
    }

    private function isNumericColumnType(?string $columnType): bool
    {
        return in_array($columnType, [
            'integer',
            'int',
            'bigint',
            'mediumint',
            'smallint',
            'tinyint',
            'decimal',
            'double',
            'float',
            'real',
            'numeric',
        ], true);
    }

    private function isStringColumnType(?string $columnType): bool
    {
        return in_array($columnType, [
            'string',
            'varchar',
            'char',
            'text',
            'tinytext',
            'mediumtext',
            'longtext',
        ], true);
    }

    private function isIndexedColumn(Builder $query, string $column): bool
    {
        return in_array($column, $this->getIndexedColumns($query), true);
    }

    /**
     * @return array<int, string>
     */
    private function getIndexedColumns(Builder $query): array
    {
        $cacheKey = $this->cacheKeyForBuilder($query);

        if (array_key_exists($cacheKey, $this->indexedColumnsCache)) {
            return $this->indexedColumnsCache[$cacheKey];
        }

        $model = $query->getModel();
        $schemaBuilder = $model->getConnection()->getSchemaBuilder();
        $table = $model->getTable();

        try {
            $indexes = $schemaBuilder->getIndexes($table);
        } catch (\Throwable) {
            $this->indexedColumnsCache[$cacheKey] = [];

            return [];
        }

        $columns = [];
        foreach ($indexes as $index) {
            $indexColumns = $index['columns'] ?? [];
            if (!is_array($indexColumns)) {
                continue;
            }

            foreach ($indexColumns as $indexColumn) {
                if (is_string($indexColumn)) {
                    $columns[] = $indexColumn;
                }
            }
        }

        $this->indexedColumnsCache[$cacheKey] = array_values(array_unique($columns));

        return $this->indexedColumnsCache[$cacheKey];
    }

    private function getColumnType(Builder $query, string $column): ?string
    {
        $columnTypes = $this->getColumnTypes($query);

        return $columnTypes[$column] ?? null;
    }

    /**
     * @return array<string, string>
     */
    private function getColumnTypes(Builder $query): array
    {
        $cacheKey = $this->cacheKeyForBuilder($query);

        if (array_key_exists($cacheKey, $this->columnTypeCache)) {
            return $this->columnTypeCache[$cacheKey];
        }

        $model = $query->getModel();
        $schemaBuilder = $model->getConnection()->getSchemaBuilder();
        $table = $model->getTable();

        $columnTypes = [];
        foreach ($schemaBuilder->getColumnListing($table) as $column) {
            if (!is_string($column)) {
                continue;
            }

            try {
                $columnTypes[$column] = $schemaBuilder->getColumnType($table, $column);
            } catch (\Throwable) {
                continue;
            }
        }

        $this->columnTypeCache[$cacheKey] = $columnTypes;

        return $columnTypes;
    }

    private function cacheKeyForBuilder(Builder $query): string
    {
        $model = $query->getModel();
        $connection = $model->getConnection();

        return "{$connection->getName()}:{$model->getTable()}";
    }

    /**
     * @return array<string, mixed>
     */
    private function sanitizeFilterBag(mixed $filters): array
    {
        return is_array($filters) ? $filters : [];
    }

    /**
     * @return array<int, string>
     */
    private function reservedQueryKeys(): array
    {
        $reservedKeys = [
            'page',
            $this->filterParameterName(),
            $this->sortParameterName(),
            (string) config('query-builder.parameters.include', 'include'),
            (string) config('query-builder.parameters.fields', 'fields'),
            (string) config('query-builder.parameters.append', 'append'),
        ];

        if (method_exists($this, 'additionalReservedQueryKeys')) {
            $reservedKeys = array_merge($reservedKeys, $this->additionalReservedQueryKeys());
        }

        return array_values(array_unique($reservedKeys));
    }

    private function filterParameterName(): string
    {
        return (string) config('query-builder.parameters.filter', 'filter');
    }

    private function sortParameterName(): string
    {
        return (string) config('query-builder.parameters.sort', 'sort');
    }
}
