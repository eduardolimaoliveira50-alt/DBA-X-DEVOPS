
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label',
    'icon' => null,
    'type' => 'text',
    'value' => null,
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
    'label',
    'icon' => null,
    'type' => 'text',
    'value' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $id = 'campo-'.$name;
    $error = $errors->first($name);
?>

<div class="login-field">
    <label for="<?php echo e($id); ?>"><?php echo e($label); ?></label>
    <div class="login-input-wrap">
        <?php if($icon): ?>
            <span class="login-input-icon"><i class="<?php echo e($icon); ?>"></i></span>
        <?php endif; ?>
        <input id="<?php echo e($id); ?>" name="<?php echo e($name); ?>" type="<?php echo e($type); ?>"
               value="<?php echo e($type === 'password' ? '' : old($name, $value)); ?>"
               <?php if($error): ?> aria-invalid="true" aria-describedby="<?php echo e($id); ?>-erro" <?php endif; ?>
               <?php echo e($attributes->merge(['class' => $error ? 'has-error' : ''])); ?>>
        <?php if($type === 'password'): ?>
            <button type="button" class="login-password-toggle" data-password-toggle="#<?php echo e($id); ?>"
                    aria-label="Mostrar senha" title="Mostrar senha" aria-pressed="false">
                <i class="far fa-eye"></i>
            </button>
        <?php endif; ?>
    </div>
    
    <?php if($error): ?>
        <span class="sr-only" id="<?php echo e($id); ?>-erro"><?php echo e($error); ?></span>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/login-field.blade.php ENDPATH**/ ?>