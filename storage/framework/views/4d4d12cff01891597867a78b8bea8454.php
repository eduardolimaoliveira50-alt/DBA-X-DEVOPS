
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'col' => null,
    'icon' => null,
    'help' => null,
    'required' => false,
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
    'placeholder' => null,
    'col' => null,
    'icon' => null,
    'help' => null,
    'required' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $key = trim(str_replace(['[]', '[', ']'], ['', '.', ''], $name), '.');
    $id = 'f-'.str_replace('.', '-', $key);
    $error = $errors->first($key);
    $current = (string) old($key, $selected);
?>

<div class="form-group <?php echo e($col ? 'col-md-'.$col : ''); ?>">
    <?php if($label): ?>
        <label for="<?php echo e($id); ?>">
            <?php echo e($label); ?>

            <?php if($required): ?>
                <span class="text-danger" aria-hidden="true">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>

    <?php if($icon): ?>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="<?php echo e($icon); ?>"></i></span>
            </div>
    <?php endif; ?>

    <select name="<?php echo e($name); ?>" id="<?php echo e($id); ?>" <?php if($required): echo 'required'; endif; ?>
            <?php if($error): ?> aria-invalid="true" <?php endif; ?>
            <?php echo e($attributes->merge(['class' => 'custom-select'.($error ? ' is-invalid' : '')])); ?>>
        <?php if(! is_null($placeholder)): ?>
            <option value=""><?php echo e($placeholder); ?></option>
        <?php endif; ?>
        <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($value); ?>" <?php if($current !== '' && $current === (string) $value): echo 'selected'; endif; ?>><?php echo e($text); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <?php if($icon): ?>
            <?php if($error): ?>
                <div class="invalid-feedback"><?php echo e($error); ?></div>
            <?php endif; ?>
        </div>
    <?php elseif($error): ?>
        <div class="invalid-feedback"><?php echo e($error); ?></div>
    <?php endif; ?>

    <?php if($help): ?>
        <small class="form-text text-muted"><?php echo e($help); ?></small>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/form/select.blade.php ENDPATH**/ ?>