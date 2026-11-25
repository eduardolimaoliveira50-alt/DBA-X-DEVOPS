
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'action',
    'method' => 'POST',
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
    'action',
    'method' => 'POST',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php ($method = strtoupper($method)); ?>

<form action="<?php echo e($action); ?>" method="<?php echo e($method === 'GET' ? 'GET' : 'POST'); ?>" <?php echo e($attributes); ?>>
    <?php if($method !== 'GET'): ?>
        <?php echo csrf_field(); ?>
    <?php endif; ?>
    <?php if(! in_array($method, ['GET', 'POST'], true)): ?>
        <?php echo method_field($method); ?>
    <?php endif; ?>

    <?php echo e($slot); ?>

</form>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/form/form.blade.php ENDPATH**/ ?>