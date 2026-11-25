

<?php
    $logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout');
    if ($logout_url) {
        $logout_url = config('adminlte.use_route_url', false) ? route($logout_url) : url($logout_url);
    }

    $usuario = Auth::user();
    $inicial = mb_strtoupper(mb_substr(trim($usuario->name), 0, 1));
    $primeiroNome = \Illuminate\Support\Str::before(trim($usuario->name), ' ');
    $perfis = method_exists($usuario, 'getRoleNames') ? $usuario->getRoleNames() : collect();
?>

<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle usermenu-trigger-glass" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="usermenu-trigger__avatar" aria-hidden="true"><?php echo e($inicial); ?></span>
        <span class="d-none d-md-inline"><?php echo e($primeiroNome); ?></span>
    </a>

    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 shadow usermenu-panel">
        <li class="usermenu-panel__header text-white">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center mr-3 font-weight-bold usermenu-panel__avatar">
                    <?php echo e($inicial); ?>

                </div>
                <div class="min-w-0">
                    <div class="font-weight-bold usermenu-panel__name"><?php echo e($usuario->name); ?></div>
                    <div class="usermenu-panel__subtitle text-truncate"><?php echo e($usuario->email); ?></div>
                    <?php if($perfis->isNotEmpty()): ?>
                        <div class="usermenu-panel__roles">
                            <?php $__currentLoopData = $perfis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perfil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="usermenu-panel__role"><?php echo e(\App\Support\Perfis::ROLE_LABELS[$perfil] ?? $perfil); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </li>

        <?php echo $__env->renderEach('adminlte::partials.navbar.dropdown-item', $adminlte->menu("navbar-user"), 'item'); ?>

        <li class="usermenu-panel__actions">
            <a class="btn btn-block d-flex align-items-center justify-content-center mb-2 usermenu-panel__btn usermenu-panel__btn--light"
               href="<?php echo e(route('profile.edit')); ?>">
                <i class="fas fa-fw fa-user-circle mr-1"></i>
                Meu perfil
            </a>
            <a class="btn btn-block d-flex align-items-center justify-content-center usermenu-panel__btn usermenu-panel__btn--danger"
               href="#"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-fw fa-power-off mr-1"></i>
                Sair
            </a>

            <form id="logout-form" action="<?php echo e($logout_url); ?>" method="POST" style="display: none;">
                <?php if(config('adminlte.logout_method')): ?>
                    <?php echo e(method_field(config('adminlte.logout_method'))); ?>

                <?php endif; ?>
                <?php echo e(csrf_field()); ?>

            </form>
        </li>
    </ul>
</li>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/vendor/adminlte/partials/navbar/menu-item-dropdown-user-menu.blade.php ENDPATH**/ ?>