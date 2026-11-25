
<footer class="main-footer app-footer">
    <span class="app-footer__brand">
        <i class="<?php echo e(config('sistema.icone')); ?>"></i>
        <span>
            <strong><?php echo e(config('sistema.nome')); ?></strong>
            <span class="d-none d-lg-inline">· <?php echo e(config('sistema.descricao')); ?></span>
        </span>
    </span>
    <span class="app-footer__meta">
        <span class="d-none d-sm-inline"><?php echo e(config('sistema.orgao')); ?> · <?php echo e(config('sistema.unidade')); ?> © <?php echo e(date('Y')); ?></span>
        <span class="app-footer__sep d-none d-md-inline">|</span>
        <span class="d-none d-md-inline">versão <?php echo e(config('sistema.versao')); ?></span>
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
    </span>
</footer>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/footer.blade.php ENDPATH**/ ?>