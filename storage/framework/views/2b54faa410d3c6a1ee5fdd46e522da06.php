
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'route',
    'label' => 'Editar',
    'can' => null,
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
    'route',
    'label' => 'Editar',
    'can' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(! $can || auth()->user()?->can($can)): ?>
    <a href="<?php echo e($route); ?>" <?php echo e($attributes->merge(['class' => 'btn btn-outline-primary btn-sm'])); ?>>
        <i class="fas fa-pen mr-1"></i> <?php echo e($label); ?>

    </a>
<?php endif; ?>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/btn/edit.blade.php ENDPATH**/ ?>