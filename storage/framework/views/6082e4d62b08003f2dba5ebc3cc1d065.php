
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'route',
    'method' => 'POST',
    'label',
    'icon' => null,
    'variant' => 'outline-primary',
    'confirm' => null,
    'confirmTitle' => null,
    'confirmButton' => null,
    'confirmVariant' => 'primary',
    'tooltip' => null,
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
    'method' => 'POST',
    'label',
    'icon' => null,
    'variant' => 'outline-primary',
    'confirm' => null,
    'confirmTitle' => null,
    'confirmButton' => null,
    'confirmVariant' => 'primary',
    'tooltip' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<form method="POST" action="<?php echo e($route); ?>" class="d-inline-block"
      <?php if($confirm): ?>
          data-confirm="<?php echo e($confirm); ?>"
          <?php if($confirmTitle): ?> data-confirm-title="<?php echo e($confirmTitle); ?>" <?php endif; ?>
          <?php if($confirmButton): ?> data-confirm-button="<?php echo e($confirmButton); ?>" <?php endif; ?>
          data-confirm-variant="<?php echo e($confirmVariant); ?>"
      <?php endif; ?>>
    <?php echo csrf_field(); ?>
    <?php if(strtoupper($method) !== 'POST'): ?>
        <?php echo method_field(strtoupper($method)); ?>
    <?php endif; ?>
    <button type="submit" <?php echo e($attributes->merge(['class' => 'btn btn-sm btn-'.$variant])); ?>

            <?php if($tooltip): ?> data-toggle="tooltip" title="<?php echo e($tooltip); ?>" <?php endif; ?>>
        <?php if($icon): ?>
            <i class="<?php echo e($icon); ?> mr-1"></i>
        <?php endif; ?>
        <?php echo e($label); ?>

    </button>
</form>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/btn/confirm.blade.php ENDPATH**/ ?>