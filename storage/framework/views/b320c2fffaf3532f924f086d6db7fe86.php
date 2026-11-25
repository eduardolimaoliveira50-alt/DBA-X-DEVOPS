
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label',
    'value',
    'icon' => 'fas fa-chart-line',
    'color' => 'primary',
    'hint' => null,
    'href' => null,
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
    'label',
    'value',
    'icon' => 'fas fa-chart-line',
    'color' => 'primary',
    'hint' => null,
    'href' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($href): ?>
    <a href="<?php echo e($href); ?>" <?php echo e($attributes->merge(['class' => 'ca-kpi ca-kpi--'.$color.' d-block'])); ?>>
<?php else: ?>
    <div <?php echo e($attributes->merge(['class' => 'ca-kpi ca-kpi--'.$color])); ?>>
<?php endif; ?>
    <span class="ca-kpi__icon"><i class="<?php echo e($icon); ?>"></i></span>
    <div class="ca-kpi__label"><?php echo e($label); ?></div>
    <div class="ca-kpi__value"><?php echo e($value); ?></div>
    <?php if($hint): ?>
        <div class="ca-kpi__hint"><?php echo e($hint); ?></div>
    <?php endif; ?>
<?php if($href): ?>
    </a>
<?php else: ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/kpi.blade.php ENDPATH**/ ?>