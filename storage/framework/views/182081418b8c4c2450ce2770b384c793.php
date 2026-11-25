
<?php ($error = $errors->first($name)); ?>

<div class="form-group <?php echo e($col ? 'col-md-'.$col : ''); ?>">
    <?php if($label): ?>
        <label for="<?php echo e($id); ?>">
            <?php echo e($label); ?>

            <?php if($required): ?>
                <span class="text-danger" aria-hidden="true">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>

    <select id="<?php echo e($id); ?>" name="<?php echo e($name); ?>" <?php if($required): echo 'required'; endif; ?>
            <?php if($error): ?> aria-invalid="true" <?php endif; ?>
            <?php echo e($attributes->merge(['class' => 'form-control'.($error ? ' is-invalid' : '')])); ?>

            data-svo-select2="1" data-select2-url="<?php echo e(route('select2.list', ['model' => $model])); ?>"
            data-placeholder="<?php echo e($placeholder); ?>" data-theme="bootstrap4" style="width: 100%;">
        <option value=""></option>
        <?php $__currentLoopData = $selectedOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($option['id']); ?>" selected><?php echo e($option['text']); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <?php if($error): ?>
        <div class="invalid-feedback d-block"><?php echo e($error); ?></div>
    <?php endif; ?>

    <?php if($help): ?>
        <small class="form-text text-muted"><?php echo e($help); ?></small>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/select2.blade.php ENDPATH**/ ?>