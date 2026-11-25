
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'count' => null,
    'accent' => null,
    'noPadding' => false,
    'collapsible' => false,
    'collapsed' => false,
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
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'count' => null,
    'accent' => null,
    'noPadding' => false,
    'collapsible' => false,
    'collapsed' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $classes = 'card';
    if ($accent) {
        $classes .= ' card-accent card-accent--'.$accent;
    }
    if ($collapsed) {
        $classes .= ' collapsed-card';
    }
?>

<div <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php if($title || isset($tools)): ?>
        <div class="card-header d-flex flex-wrap align-items-center"
             <?php if($collapsible): ?> data-card-widget="collapse" style="cursor: pointer;" <?php endif; ?>>
            
            <div class="d-flex align-items-center mr-auto py-1" style="flex: 1 1 0; min-width: 0;">
                <?php if($icon): ?>
                    <span class="card-icon"><i class="<?php echo e($icon); ?>"></i></span>
                <?php endif; ?>
                <div>
                    <h3 class="card-title mb-0"><?php echo e($title); ?></h3>
                    <?php if($subtitle): ?>
                        <div class="small text-muted"><?php echo e($subtitle); ?></div>
                    <?php endif; ?>
                </div>
                <?php if(! is_null($count)): ?>
                    <span class="card-count"><?php echo e($count); ?></span>
                <?php endif; ?>
            </div>

            <div class="card-tools d-flex align-items-center flex-wrap" style="gap: .4rem;">
                <?php echo e($tools ?? ''); ?>

                <?php if($collapsible): ?>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" aria-label="Recolher ou expandir">
                        <i class="fas <?php echo e($collapsed ? 'fa-plus' : 'fa-minus'); ?>"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="card-body <?php echo e($noPadding ? 'p-0' : ''); ?>">
        <?php echo e($slot); ?>

    </div>

    <?php if(isset($footer)): ?>
        <div class="card-footer">
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/card.blade.php ENDPATH**/ ?>