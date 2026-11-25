
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'sub' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'name',
    'sub' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php ($initial = mb_strtoupper(mb_substr(trim((string) $name), 0, 1)) ?: '?'); ?>

<span <?php echo e($attributes->merge(['class' => 'ca-person'])); ?>>
    <span class="ca-person__avatar" aria-hidden="true"><?php echo e($initial); ?></span>
    <span>
        <span class="ca-person__name d-block"><?php echo e($name); ?></span>
        <?php if($sub): ?>
            <span class="ca-person__sub d-block"><?php echo e($sub); ?></span>
        <?php endif; ?>
    </span>
</span>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/person.blade.php ENDPATH**/ ?>