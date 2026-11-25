
<?php ($ambiente = config('sistema.ambientes')[app()->environment()] ?? null); ?>

<?php if($ambiente): ?>
    <span <?php echo e($attributes->merge(['class' => 'topbar-env-badge'])); ?> title="Ambiente de <?php echo e(mb_strtolower($ambiente)); ?>: os dados aqui não são de produção">
        <?php echo e($ambiente); ?>

    </span>
<?php endif; ?>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/env-badge.blade.php ENDPATH**/ ?>