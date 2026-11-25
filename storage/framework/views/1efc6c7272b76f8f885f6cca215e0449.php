
<?php $layoutHelper = app('JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper'); ?>

<?php ( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') ); ?>

<?php if(config('adminlte.use_route_url', false)): ?>
    <?php ( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' ); ?>
<?php else: ?>
    <?php ( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' ); ?>
<?php endif; ?>

<a href="<?php echo e($dashboard_url); ?>"
    <?php if($layoutHelper->isLayoutTopnavEnabled()): ?>
        class="navbar-brand <?php echo e(config('adminlte.classes_brand')); ?>"
    <?php else: ?>
        class="brand-link <?php echo e(config('adminlte.classes_brand')); ?>"
    <?php endif; ?>
    title="<?php echo e(config('sistema.nome')); ?>">

    <span class="brand-mark" aria-hidden="true"><i class="<?php echo e(config('sistema.icone')); ?>"></i></span>

    <span class="brand-text-stack">
        <span class="brand-text-stack__title"><?php echo e(config('sistema.nome')); ?></span>
        <span class="brand-text-stack__subtitle"><?php echo e(config('sistema.orgao')); ?> · <?php echo e(config('sistema.unidade')); ?></span>
    </span>
</a>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/vendor/adminlte/partials/common/brand-logo-xs.blade.php ENDPATH**/ ?>