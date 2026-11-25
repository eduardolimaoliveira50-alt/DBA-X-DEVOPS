<?php

namespace App\View\Components;

use App\Contracts\HasSelect2List;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Lista com busca no servidor (padrão do SVO: data-svo-select2 + POST select2/{model}).
 *   <x-select2 name="municipality_id" model="municipality" label="Município" :selected="$unit->municipality_id" required />
 * Só a opção escolhida vem renderizada; o resto vem da busca.
 */
class Select2 extends Component
{
    /** @var array<int, array{id: mixed, text: string}> */
    public array $selectedOptions = [];

    public string $current;

    public function __construct(
        public string $name,
        public string $model,
        public ?string $label = null,
        public ?string $placeholder = 'Digite para buscar',
        public mixed $selected = null,
        public ?string $col = null,
        public ?string $help = null,
        public bool $required = false,
        public ?string $id = null,
    ) {
        $this->id ??= 'f-'.str_replace(['[', ']', '.'], ['-', '', '-'], $name);
        $this->current = (string) old($name, $selected);

        $modelClass = config('select2.models.'.$model);
        if ($this->current === '' || ! is_numeric($this->current) || ! $modelClass || ! is_subclass_of($modelClass, HasSelect2List::class)) {
            return;
        }

        $item = $modelClass::query()->find($this->current); 
        if ($item) {
            $this->selectedOptions[] = ['id' => $item->getKey(), 'text' => (string) ($item->select2_label ?? $item->name)];
        }
    }

    public function render(): View
    {
        return view('components.select2');
    }
}
