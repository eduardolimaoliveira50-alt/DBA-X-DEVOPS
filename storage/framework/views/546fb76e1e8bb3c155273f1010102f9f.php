
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => 'Salvar',
    'icon' => 'fas fa-check',
    'variant' => 'primary',
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
    'label' => 'Salvar',
    'icon' => 'fas fa-check',
    'variant' => 'primary',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<button type="submit" <?php echo e($attributes->merge(['class' => 'btn btn-'.$variant])); ?>>
    <?php if($icon): ?>
        <i class="<?php echo e($icon); ?> mr-1"></i>
    <?php endif; ?>
    <?php echo e($label); ?>

</button>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/btn/submit.blade.php ENDPATH**/ ?>