
<div class="row ca-cols-stretch">
    <div class="<?php echo e(isset($aside) ? 'col-lg-8' : 'col-12'); ?>">
        <?php echo e($slot); ?>

    </div>

    <?php if(isset($aside)): ?>
        <div class="col-lg-4">
            <?php echo e($aside); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/form-layout.blade.php ENDPATH**/ ?>