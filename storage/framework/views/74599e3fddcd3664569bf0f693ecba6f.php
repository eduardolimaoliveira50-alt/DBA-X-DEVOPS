<?php $__env->startSection('title', 'Início'); ?>

<?php
    $usuario = auth()->user();
    $primeiroNome = \Illuminate\Support\Str::before(trim($usuario->name), ' ');
    $n = fn ($v) => number_format((int) $v, 0, ',', '.');
?>

<?php $__env->startSection('content'); ?>
    <div class="ca-hero mt-3">
        <div class="ca-hero__eyebrow"><?php echo e(config('sistema.nome')); ?></div>
        <div class="ca-hero__title">Olá, <?php echo e($primeiroNome); ?></div>
        <p class="ca-hero__text">
            Entradas e saídas de material, saldo de cada produto e o que precisa de reposição.
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('movimentacoes.registrar')): ?> Registre uma movimentação e o saldo é atualizado na hora. <?php endif; ?>
        </p>
        <div class="ca-hero__chips">
            <?php $__currentLoopData = $usuario->getRoleNames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perfil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="ca-hero__chip"><i class="fas fa-id-badge"></i> <?php echo e(\App\Support\Perfis::ROLE_LABELS[$perfil] ?? $perfil); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="ca-hero__chip" data-toggle="tooltip" title="Commit publicado nesta instalação">
                <i class="fas fa-code-branch"></i> Versão <?php echo e(config('sistema.versao')); ?>

            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-xl-3 mb-4">
            <?php if (isset($component)) { $__componentOriginal7eb63bdf3faa86d7e1b98377393d1632 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.kpi','data' => ['label' => 'Produtos ativos','value' => $n($stats['produtos']),'icon' => 'fas fa-box-open','color' => 'primary','hint' => 'no catálogo do almoxarifado','href' => route('admin.produtos.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Produtos ativos','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($n($stats['produtos'])),'icon' => 'fas fa-box-open','color' => 'primary','hint' => 'no catálogo do almoxarifado','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.produtos.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632)): ?>
<?php $attributes = $__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632; ?>
<?php unset($__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7eb63bdf3faa86d7e1b98377393d1632)): ?>
<?php $component = $__componentOriginal7eb63bdf3faa86d7e1b98377393d1632; ?>
<?php unset($__componentOriginal7eb63bdf3faa86d7e1b98377393d1632); ?>
<?php endif; ?>
        </div>
        <div class="col-sm-6 col-xl-3 mb-4">
            <?php if (isset($component)) { $__componentOriginal7eb63bdf3faa86d7e1b98377393d1632 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.kpi','data' => ['label' => 'Abaixo do mínimo','value' => $n($stats['abaixo']),'icon' => 'fas fa-exclamation-triangle','color' => 'danger','hint' => 'precisam de reposição','href' => route('admin.produtos.index', ['situacao' => 'abaixo'])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Abaixo do mínimo','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($n($stats['abaixo'])),'icon' => 'fas fa-exclamation-triangle','color' => 'danger','hint' => 'precisam de reposição','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.produtos.index', ['situacao' => 'abaixo']))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632)): ?>
<?php $attributes = $__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632; ?>
<?php unset($__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7eb63bdf3faa86d7e1b98377393d1632)): ?>
<?php $component = $__componentOriginal7eb63bdf3faa86d7e1b98377393d1632; ?>
<?php unset($__componentOriginal7eb63bdf3faa86d7e1b98377393d1632); ?>
<?php endif; ?>
        </div>
        <div class="col-sm-6 col-xl-3 mb-4">
            <?php if (isset($component)) { $__componentOriginal7eb63bdf3faa86d7e1b98377393d1632 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.kpi','data' => ['label' => 'Entradas (30 dias)','value' => $n($stats['entradas']),'icon' => 'fas fa-arrow-down','color' => 'success','hint' => 'unidades recebidas','href' => route('admin.movimentacoes.index', ['tipo' => 'entrada'])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Entradas (30 dias)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($n($stats['entradas'])),'icon' => 'fas fa-arrow-down','color' => 'success','hint' => 'unidades recebidas','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.movimentacoes.index', ['tipo' => 'entrada']))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632)): ?>
<?php $attributes = $__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632; ?>
<?php unset($__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7eb63bdf3faa86d7e1b98377393d1632)): ?>
<?php $component = $__componentOriginal7eb63bdf3faa86d7e1b98377393d1632; ?>
<?php unset($__componentOriginal7eb63bdf3faa86d7e1b98377393d1632); ?>
<?php endif; ?>
        </div>
        <div class="col-sm-6 col-xl-3 mb-4">
            <?php if (isset($component)) { $__componentOriginal7eb63bdf3faa86d7e1b98377393d1632 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.kpi','data' => ['label' => 'Saídas (30 dias)','value' => $n($stats['saidas']),'icon' => 'fas fa-arrow-up','color' => 'accent','hint' => 'unidades entregues','href' => route('admin.movimentacoes.index', ['tipo' => 'saida'])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Saídas (30 dias)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($n($stats['saidas'])),'icon' => 'fas fa-arrow-up','color' => 'accent','hint' => 'unidades entregues','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.movimentacoes.index', ['tipo' => 'saida']))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632)): ?>
<?php $attributes = $__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632; ?>
<?php unset($__attributesOriginal7eb63bdf3faa86d7e1b98377393d1632); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7eb63bdf3faa86d7e1b98377393d1632)): ?>
<?php $component = $__componentOriginal7eb63bdf3faa86d7e1b98377393d1632; ?>
<?php unset($__componentOriginal7eb63bdf3faa86d7e1b98377393d1632); ?>
<?php endif; ?>
        </div>
    </div>

    <div class="row ca-cols-stretch">
        <div class="col-lg-7 mb-4">
            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Reposição urgente','icon' => 'fas fa-exclamation-triangle','count' => $stats['abaixo'] ?: null,'noPadding' => true,'class' => 'h-100','subtitle' => 'Os mais distantes do estoque mínimo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Reposição urgente','icon' => 'fas fa-exclamation-triangle','count' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['abaixo'] ?: null),'no-padding' => true,'class' => 'h-100','subtitle' => 'Os mais distantes do estoque mínimo']); ?>
                 <?php $__env->slot('tools', null, []); ?> 
                    <a href="<?php echo e(route('admin.produtos.index', ['situacao' => 'abaixo'])); ?>" class="btn btn-sm btn-outline-primary">Ver todos</a>
                 <?php $__env->endSlot(); ?>
                <?php if($criticos->isEmpty()): ?>
                    <?php if (isset($component)) { $__componentOriginal074a021b9d42f490272b5eefda63257c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal074a021b9d42f490272b5eefda63257c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty-state','data' => ['icon' => 'fas fa-check-circle','title' => 'Nada abaixo do mínimo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fas fa-check-circle','title' => 'Nada abaixo do mínimo']); ?>Todos os produtos ativos têm saldo suficiente. <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal074a021b9d42f490272b5eefda63257c)): ?>
<?php $attributes = $__attributesOriginal074a021b9d42f490272b5eefda63257c; ?>
<?php unset($__attributesOriginal074a021b9d42f490272b5eefda63257c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal074a021b9d42f490272b5eefda63257c)): ?>
<?php $component = $__componentOriginal074a021b9d42f490272b5eefda63257c; ?>
<?php unset($__componentOriginal074a021b9d42f490272b5eefda63257c); ?>
<?php endif; ?>
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => ['class' => 'table-sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'table-sm']); ?>
                         <?php $__env->slot('head', null, []); ?> <tr><th>Código</th><th>Produto</th><th class="text-right">Saldo</th><th class="text-right">Mínimo</th><th></th></tr> <?php $__env->endSlot(); ?>
                        <?php $__currentLoopData = $criticos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php if (isset($component)) { $__componentOriginal0d847ba36e2753628aa5c2876a4dde89 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d847ba36e2753628aa5c2876a4dde89 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.code','data' => ['value' => $produto->codigo,'copy' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('code'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($produto->codigo),'copy' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0d847ba36e2753628aa5c2876a4dde89)): ?>
<?php $attributes = $__attributesOriginal0d847ba36e2753628aa5c2876a4dde89; ?>
<?php unset($__attributesOriginal0d847ba36e2753628aa5c2876a4dde89); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0d847ba36e2753628aa5c2876a4dde89)): ?>
<?php $component = $__componentOriginal0d847ba36e2753628aa5c2876a4dde89; ?>
<?php unset($__componentOriginal0d847ba36e2753628aa5c2876a4dde89); ?>
<?php endif; ?></td>
                                <td>
                                    <div class="font-weight-bold"><?php echo e($produto->nome); ?></div>
                                    <div class="small text-muted"><?php echo e($produto->categoria?->nome); ?></div>
                                </td>
                                <td class="text-right"><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['color' => 'danger','dot' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'danger','dot' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?><?php echo e($n($produto->saldo)); ?> <?php echo e($produto->unidade_medida); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></td>
                                <td class="text-right text-muted"><?php echo e($n($produto->estoque_minimo)); ?></td>
                                <td class="text-right">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('movimentacoes.registrar')): ?>
                                        <a href="<?php echo e(route('admin.movimentacoes.create', ['tipo' => 'entrada', 'produto' => $produto->id])); ?>"
                                           class="btn btn-sm btn-outline-success" data-toggle="tooltip" title="Registrar entrada"><i class="fas fa-arrow-down"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $attributes = $__attributesOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $component = $__componentOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__componentOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
        </div>
        <div class="col-lg-5 mb-4">
            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Últimas movimentações','icon' => 'fas fa-exchange-alt','class' => 'h-100','subtitle' => 'O que entrou e saiu por último']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Últimas movimentações','icon' => 'fas fa-exchange-alt','class' => 'h-100','subtitle' => 'O que entrou e saiu por último']); ?>
                 <?php $__env->slot('tools', null, []); ?> 
                    <a href="<?php echo e(route('admin.movimentacoes.index')); ?>" class="btn btn-sm btn-outline-primary">Ver todas</a>
                 <?php $__env->endSlot(); ?>
                <?php if($ultimas->isEmpty()): ?>
                    <?php if (isset($component)) { $__componentOriginal074a021b9d42f490272b5eefda63257c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal074a021b9d42f490272b5eefda63257c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty-state','data' => ['icon' => 'fas fa-exchange-alt','title' => 'Nenhuma movimentação ainda']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fas fa-exchange-alt','title' => 'Nenhuma movimentação ainda']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal074a021b9d42f490272b5eefda63257c)): ?>
<?php $attributes = $__attributesOriginal074a021b9d42f490272b5eefda63257c; ?>
<?php unset($__attributesOriginal074a021b9d42f490272b5eefda63257c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal074a021b9d42f490272b5eefda63257c)): ?>
<?php $component = $__componentOriginal074a021b9d42f490272b5eefda63257c; ?>
<?php unset($__componentOriginal074a021b9d42f490272b5eefda63257c); ?>
<?php endif; ?>
                <?php else: ?>
                    <ul class="ca-timeline">
                        <?php $__currentLoopData = $ultimas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <div class="ca-timeline__head">
                                    <i class="fas <?php echo e($mov->isEntrada() ? 'fa-arrow-down text-success' : 'fa-arrow-up text-danger'); ?>"></i>
                                    <strong class="small"><?php echo e($mov->isEntrada() ? 'Entrada' : 'Saída'); ?> de <?php echo e($n($mov->quantidade)); ?> <?php echo e($mov->produto?->unidade_medida); ?></strong>
                                    <span class="small text-muted ml-auto text-nowrap"><?php echo e($mov->created_at?->diffForHumans(short: true)); ?></span>
                                </div>
                                <div class="small text-muted"><?php echo e($mov->produto?->nome); ?><?php if($mov->user): ?> · <?php echo e($mov->user->name); ?><?php endif; ?></div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/home.blade.php ENDPATH**/ ?>