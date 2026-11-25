<?php $__env->startSection('content'); ?>
    <div class="error-body">
        <div class="error-code"><?php echo $__env->yieldContent('code'); ?></div>
        <p class="error-text"><?php echo $__env->yieldContent('message'); ?></p>
        <div class="error-actions">
            <?php if (! empty(trim($__env->yieldContent('actions')))): ?>
                <?php echo $__env->yieldContent('actions'); ?>
            <?php else: ?>
                <a class="primary" href="<?php echo e(url('/')); ?>"><i class="fas fa-home"></i> Ir para o início</a>
                <a class="secondary" href="#" onclick="history.back(); return false;"><i class="fas fa-arrow-left"></i> Voltar</a>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.login', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/errors/layout.blade.php ENDPATH**/ ?>