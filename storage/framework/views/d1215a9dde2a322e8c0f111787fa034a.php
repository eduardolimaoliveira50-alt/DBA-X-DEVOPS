
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'icon' => 'fas fa-inbox',
    'title',
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
    'icon' => 'fas fa-inbox',
    'title',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'ca-empty'])); ?>>
    <span class="ca-empty__icon"><i class="<?php echo e($icon); ?>"></i></span>
    <div class="ca-empty__title"><?php echo e($title); ?></div>
    <?php if($slot->isNotEmpty()): ?>
        <p class="ca-empty__text"><?php echo e($slot); ?></p>
    <?php endif; ?>
    <?php if(isset($actions)): ?>
        <div class="mt-3 d-flex justify-content-center flex-wrap" style="gap: .5rem;">
            <?php echo e($actions); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/empty-state.blade.php ENDPATH**/ ?>