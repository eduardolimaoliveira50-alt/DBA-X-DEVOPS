
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'route',
    'title' => 'Novo',
    'icon' => 'fas fa-plus',
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
    'title' => 'Novo',
    'icon' => 'fas fa-plus',
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
    <a href="<?php echo e($route); ?>" <?php echo e($attributes->merge(['class' => 'btn btn-primary btn-sm'])); ?>>
        <i class="<?php echo e($icon); ?> mr-1"></i> <?php echo e($title); ?>

    </a>
<?php endif; ?>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/btn/new.blade.php ENDPATH**/ ?>