
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'col' => null,
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
    'label' => null,
    'options' => [],
    'selected' => null,
    'col' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $current = (string) old($name, $selected);
    $error = $errors->first($name);
?>

<div class="form-group <?php echo e($col ? 'col-md-'.$col : ''); ?>">
    <?php if($label): ?>
        <label class="d-block"><?php echo e($label); ?></label>
    <?php endif; ?>

    <div class="ca-segmented" role="radiogroup" <?php if($label): ?> aria-label="<?php echo e($label); ?>" <?php endif; ?>>
        <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php ($id = 'f-'.$name.'-'.$value); ?>
            <input type="radio" name="<?php echo e($name); ?>" id="<?php echo e($id); ?>" value="<?php echo e($value); ?>" <?php if($current === (string) $value): echo 'checked'; endif; ?>>
            <label for="<?php echo e($id); ?>">
                <?php if(! empty($option['icon'])): ?>
                    <i class="<?php echo e($option['icon']); ?>"></i>
                <?php endif; ?>
                <?php echo e($option['label'] ?? $value); ?>

            </label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if($error): ?>
        <div class="invalid-feedback d-block"><?php echo e($error); ?></div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/form/segmented.blade.php ENDPATH**/ ?>