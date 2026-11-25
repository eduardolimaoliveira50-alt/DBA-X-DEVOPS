
<?php ($ambiente = config('sistema.ambientes')[app()->environment()] ?? null); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="<?php echo e(rescue(fn () => csrf_token(), '', false)); ?>">
    <meta name="color-scheme" content="light dark">
    <title><?php echo $__env->yieldContent('title', 'Entrar'); ?> · <?php echo e(config('sistema.nome')); ?></title>
    <script>
        (function () {
            try {
                var tema = localStorage.getItem('almoxarifado-theme');
                if (tema !== 'light' && tema !== 'dark') {
                    tema = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }
                document.documentElement.setAttribute('data-theme', tema);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
    <link rel="icon" href="<?php echo e(asset('favicon.svg')); ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/fontawesome-free/css/all.min.css')); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/login.js']); ?>
</head>
<body>
    <div class="login-page">
        <div class="bg-overlay" aria-hidden="true"></div>
        <div class="bg-pattern" aria-hidden="true"></div>
        <div class="deco-circle c1" aria-hidden="true"></div>
        <div class="deco-circle c2" aria-hidden="true"></div>

        <button type="button" class="login-theme-toggle" data-theme-toggle aria-label="Alternar tema" title="Alternar tema">
            <i class="fas fa-moon theme-toggle-btn__moon"></i>
            <i class="fas fa-sun theme-toggle-btn__sun"></i>
        </button>

        <main class="page">
            <div class="login-card <?php echo $__env->yieldContent('card_class'); ?>">
                <div class="login-header">
                    <div class="login-brand-mark" aria-hidden="true"><i class="<?php echo e(config('sistema.icone')); ?>"></i></div>
                    <h1><?php echo $__env->yieldContent('heading', config('sistema.nome')); ?></h1>
                    <p><?php echo $__env->yieldContent('subheading', config('sistema.descricao')); ?></p>
                    <?php if($ambiente): ?>
                        <span class="login-env"><i class="fas fa-flask"></i> <?php echo e($ambiente); ?></span>
                    <?php endif; ?>
                </div>

                <div class="login-body">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>

            <footer class="login-footer">
                <strong><?php echo e(config('sistema.nome')); ?></strong> · <?php echo e(config('sistema.orgao')); ?> · <?php echo e(config('sistema.unidade')); ?>

                © <?php echo e(date('Y')); ?><br>
                versão <?php echo e(config('sistema.versao')); ?>

            </footer>
        </main>
    </div>

    <?php echo $__env->yieldContent('apos_pagina'); ?>
</body>
</html>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/layouts/login.blade.php ENDPATH**/ ?>