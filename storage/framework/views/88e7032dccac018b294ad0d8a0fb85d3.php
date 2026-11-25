<?php $__env->startSection('title', 'Entrar'); ?>

<?php $__env->startSection('content'); ?>
    <div class="step-badge"><i class="fas fa-lock"></i> Acesso ao portal</div>

    <?php if($errors->any()): ?>
        <div class="login-error-box" role="alert">
            <i class="fas fa-times-circle"></i>
            <span><?php echo e($errors->first()); ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>

        <?php if (isset($component)) { $__componentOriginale70b1b88752b254ead36f2c325a4c4c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale70b1b88752b254ead36f2c325a4c4c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.login-field','data' => ['name' => 'email','label' => 'E-mail','icon' => 'fas fa-envelope','type' => 'email','autocomplete' => 'username','placeholder' => 'nome@almoxarifado.test','required' => true,'autofocus' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('login-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'email','label' => 'E-mail','icon' => 'fas fa-envelope','type' => 'email','autocomplete' => 'username','placeholder' => 'nome@almoxarifado.test','required' => true,'autofocus' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale70b1b88752b254ead36f2c325a4c4c9)): ?>
<?php $attributes = $__attributesOriginale70b1b88752b254ead36f2c325a4c4c9; ?>
<?php unset($__attributesOriginale70b1b88752b254ead36f2c325a4c4c9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale70b1b88752b254ead36f2c325a4c4c9)): ?>
<?php $component = $__componentOriginale70b1b88752b254ead36f2c325a4c4c9; ?>
<?php unset($__componentOriginale70b1b88752b254ead36f2c325a4c4c9); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginale70b1b88752b254ead36f2c325a4c4c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale70b1b88752b254ead36f2c325a4c4c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.login-field','data' => ['name' => 'password','label' => 'Senha','icon' => 'fas fa-key','type' => 'password','autocomplete' => 'current-password','placeholder' => '••••••••','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('login-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'password','label' => 'Senha','icon' => 'fas fa-key','type' => 'password','autocomplete' => 'current-password','placeholder' => '••••••••','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale70b1b88752b254ead36f2c325a4c4c9)): ?>
<?php $attributes = $__attributesOriginale70b1b88752b254ead36f2c325a4c4c9; ?>
<?php unset($__attributesOriginale70b1b88752b254ead36f2c325a4c4c9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale70b1b88752b254ead36f2c325a4c4c9)): ?>
<?php $component = $__componentOriginale70b1b88752b254ead36f2c325a4c4c9; ?>
<?php unset($__componentOriginale70b1b88752b254ead36f2c325a4c4c9); ?>
<?php endif; ?>

        <div class="login-row">
            <label class="login-check">
                <input type="checkbox" name="remember" value="1" <?php if(old('remember')): echo 'checked'; endif; ?>>
                Manter conectado neste computador
            </label>
        </div>

        <button type="submit" class="login-btn-entrar">
            <span class="btn-spinner" aria-hidden="true"></span>
            Entrar
            <i class="fas fa-arrow-right"></i>
        </button>

        <?php if(config('sistema.demo')): ?>
            <div class="login-security-note">
                <i class="fas fa-flask"></i>
                <span>Demonstração (dados fictícios): <strong>admin@almoxarifado.test</strong>, <strong>almoxarife@almoxarifado.test</strong>
                    ou <strong>consulta@almoxarifado.test</strong>, senha <strong>almox2026</strong>.</span>
            </div>
        <?php endif; ?>

        <div class="login-security-note">
            <i class="fas fa-shield-alt"></i>
            <span>Acesso registrado. As contas são criadas pela gestão do sistema.</span>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.login', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/auth/login.blade.php ENDPATH**/ ?>