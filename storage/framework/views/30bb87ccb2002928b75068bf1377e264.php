
<?php $layoutHelper = app('JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper'); ?>

<?php ($currentPageTitle = trim(View::yieldContent('title')) ?: config('sistema.nome')); ?>
<?php ($currentDateTime = now()->format('d/m/Y H:i')); ?>

<nav class="main-header navbar
    topbar-glass
    <?php echo e(config('adminlte.classes_topnav_nav', 'navbar-expand')); ?>

    <?php echo e(config('adminlte.classes_topnav', 'navbar-white navbar-light')); ?>">

    
    <ul class="navbar-nav">
        <?php echo $__env->make('adminlte::partials.navbar.menu-item-left-sidebar-toggler', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->renderEach('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item'); ?>

        <?php echo $__env->yieldContent('content_top_nav_left'); ?>

        <li class="nav-item d-none d-md-flex align-items-center">
            <div class="topbar-context-chip">
                <span class="topbar-context-chip__eyebrow"><?php echo e(config('sistema.nome')); ?></span>
                <span class="topbar-context-chip__title"><?php echo e($currentPageTitle); ?></span>
            </div>
        </li>
    </ul>

    
    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-none d-sm-flex align-items-center mr-2">
            <?php if (isset($component)) { $__componentOriginal3a8dd4e0b43f47f230baf21d487d0941 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3a8dd4e0b43f47f230baf21d487d0941 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.env-badge','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('env-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3a8dd4e0b43f47f230baf21d487d0941)): ?>
<?php $attributes = $__attributesOriginal3a8dd4e0b43f47f230baf21d487d0941; ?>
<?php unset($__attributesOriginal3a8dd4e0b43f47f230baf21d487d0941); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3a8dd4e0b43f47f230baf21d487d0941)): ?>
<?php $component = $__componentOriginal3a8dd4e0b43f47f230baf21d487d0941; ?>
<?php unset($__componentOriginal3a8dd4e0b43f47f230baf21d487d0941); ?>
<?php endif; ?>
        </li>

        <li class="nav-item d-none d-lg-flex align-items-center mr-2">
            <div class="topbar-meta-pill">
                <span class="topbar-meta-pill__label">Agora</span>
                <span class="topbar-meta-pill__value" data-topbar-datetime><?php echo e($currentDateTime); ?></span>
            </div>
        </li>

        <li class="nav-item d-flex align-items-center mr-2">
            <button type="button" class="nav-link theme-toggle-btn" data-theme-toggle
                    aria-label="Alternar tema" title="Alternar tema">
                <i class="fas fa-moon theme-toggle-btn__moon"></i>
                <i class="fas fa-sun theme-toggle-btn__sun"></i>
            </button>
        </li>

        <?php echo $__env->yieldContent('content_top_nav_right'); ?>

        <?php echo $__env->renderEach('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item'); ?>

        <?php if(Auth::user()): ?>
            <?php if(config('adminlte.usermenu_enabled')): ?>
                <?php echo $__env->make('adminlte::partials.navbar.menu-item-dropdown-user-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php else: ?>
                <?php echo $__env->make('adminlte::partials.navbar.menu-item-logout-link', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($layoutHelper->isRightSidebarEnabled()): ?>
            <?php echo $__env->make('adminlte::partials.navbar.menu-item-right-sidebar-toggler', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>
    </ul>

</nav>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/vendor/adminlte/partials/navbar/navbar.blade.php ENDPATH**/ ?>