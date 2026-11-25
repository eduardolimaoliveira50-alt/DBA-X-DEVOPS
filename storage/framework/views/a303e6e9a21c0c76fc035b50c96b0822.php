
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value',
    'copy' => true,
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
    'value',
    'copy' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<span <?php echo e($attributes->merge(['class' => 'ca-code'])); ?>>
    <span><?php echo e($value); ?></span>
    <?php if($copy && filled($value)): ?>
        <button type="button" class="ca-code__copy" data-copy="<?php echo e($value); ?>" data-toggle="tooltip" title="Copiar" aria-label="Copiar <?php echo e($value); ?>">
            <i class="far fa-copy"></i>
        </button>
    <?php endif; ?>
</span>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/code.blade.php ENDPATH**/ ?>