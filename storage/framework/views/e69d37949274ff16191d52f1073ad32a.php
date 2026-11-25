<?php $__env->startSection('title', 'Produtos'); ?>

<?php ($n = fn ($v) => number_format((int) $v, 0, ',', '.')); ?>

<?php $__env->startSection('content_header'); ?>
    <?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => 'Produtos','icon' => 'fas fa-box-open','crumbs' => ['Estoque', 'Produtos'],'subtitle' => 'O catálogo do almoxarifado: código, saldo atual e estoque mínimo de cada material.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Produtos','icon' => 'fas fa-box-open','crumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Estoque', 'Produtos']),'subtitle' => 'O catálogo do almoxarifado: código, saldo atual e estoque mínimo de cada material.']); ?>
         <?php $__env->slot('aside', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal8d28c46304aa68e73efb0df04bec20c1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d28c46304aa68e73efb0df04bec20c1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.btn.new','data' => ['route' => route('admin.produtos.create'),'title' => 'Novo produto','can' => 'produtos.gerenciar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('btn.new'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.produtos.create')),'title' => 'Novo produto','can' => 'produtos.gerenciar']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d28c46304aa68e73efb0df04bec20c1)): ?>
<?php $attributes = $__attributesOriginal8d28c46304aa68e73efb0df04bec20c1; ?>
<?php unset($__attributesOriginal8d28c46304aa68e73efb0df04bec20c1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d28c46304aa68e73efb0df04bec20c1)): ?>
<?php $component = $__componentOriginal8d28c46304aa68e73efb0df04bec20c1; ?>
<?php unset($__componentOriginal8d28c46304aa68e73efb0df04bec20c1); ?>
<?php endif; ?>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $attributes = $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $component = $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <form method="GET" action="<?php echo e(route('admin.produtos.index')); ?>" class="mb-3" data-submit-once="false">
        <div class="form-row">
            <div class="col-lg-6 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
                    <input type="search" name="busca" value="<?php echo e($busca); ?>" class="form-control" aria-label="Buscar produto"
                           placeholder="Buscar por código ou nome">
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-2">
                <select name="categoria" class="custom-select" aria-label="Categoria" data-autosubmit onchange="this.form.submit()">
                    <option value="">Todas as categorias</option>
                    <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($id); ?>" <?php if((int) request('categoria') === $id): echo 'selected'; endif; ?>><?php echo e($nome); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-sm-6 col-lg-3 mb-2 d-flex" style="gap: .5rem;">
                <select name="situacao" class="custom-select" aria-label="Situação" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="abaixo" <?php if(request('situacao') === 'abaixo'): echo 'selected'; endif; ?>>Abaixo do mínimo</option>
                    <option value="inativos" <?php if(request('situacao') === 'inativos'): echo 'selected'; endif; ?>>Inativos</option>
                </select>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
        <?php if($busca !== '' || request('categoria') || request('situacao')): ?>
            <a href="<?php echo e(route('admin.produtos.index')); ?>" class="btn btn-link btn-sm px-0"><i class="fas fa-times mr-1"></i> Limpar filtros</a>
        <?php endif; ?>
    </form>

    <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => $busca !== '' ? 'Encontrados' : 'Cadastrados','icon' => 'fas fa-list-ul','count' => $n($produtos->total()),'noPadding' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($busca !== '' ? 'Encontrados' : 'Cadastrados'),'icon' => 'fas fa-list-ul','count' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($n($produtos->total())),'no-padding' => true]); ?>
        <?php if($produtos->isEmpty()): ?>
            <?php if (isset($component)) { $__componentOriginal074a021b9d42f490272b5eefda63257c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal074a021b9d42f490272b5eefda63257c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty-state','data' => ['icon' => 'fas fa-box-open','title' => 'Nenhum produto encontrado']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fas fa-box-open','title' => 'Nenhum produto encontrado']); ?>
                Confira o que foi digitado ou tire os filtros.
                 <?php $__env->slot('actions', null, []); ?> 
                    <?php if (isset($component)) { $__componentOriginal8d28c46304aa68e73efb0df04bec20c1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d28c46304aa68e73efb0df04bec20c1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.btn.new','data' => ['route' => route('admin.produtos.create'),'title' => 'Cadastrar produto','can' => 'produtos.gerenciar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('btn.new'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.produtos.create')),'title' => 'Cadastrar produto','can' => 'produtos.gerenciar']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d28c46304aa68e73efb0df04bec20c1)): ?>
<?php $attributes = $__attributesOriginal8d28c46304aa68e73efb0df04bec20c1; ?>
<?php unset($__attributesOriginal8d28c46304aa68e73efb0df04bec20c1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d28c46304aa68e73efb0df04bec20c1)): ?>
<?php $component = $__componentOriginal8d28c46304aa68e73efb0df04bec20c1; ?>
<?php unset($__componentOriginal8d28c46304aa68e73efb0df04bec20c1); ?>
<?php endif; ?>
                 <?php $__env->endSlot(); ?>
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
            <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                 <?php $__env->slot('head', null, []); ?> 
                    <tr>
                        <th>Código</th>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th class="text-right">Saldo</th>
                        <th class="text-right">Mínimo</th>
                        <th>Situação</th>
                        <th class="text-right">Ações</th>
                    </tr>
                 <?php $__env->endSlot(); ?>

                <?php $__currentLoopData = $produtos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-nowrap"><?php if (isset($component)) { $__componentOriginal0d847ba36e2753628aa5c2876a4dde89 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d847ba36e2753628aa5c2876a4dde89 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.code','data' => ['value' => $produto->codigo]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('code'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($produto->codigo)]); ?>
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
                            <span class="ca-person">
                                <span class="ca-person__avatar"><i class="fas fa-box"></i></span>
                                <span class="ca-person__name"><?php echo e($produto->nome); ?></span>
                            </span>
                        </td>
                        <td class="small"><?php echo e($produto->categoria?->nome); ?></td>
                        <td class="text-right text-nowrap font-weight-bold"><?php echo e($n($produto->saldo)); ?> <span class="small text-muted"><?php echo e($produto->unidade_medida); ?></span></td>
                        <td class="text-right text-muted"><?php echo e($n($produto->estoque_minimo)); ?></td>
                        <td>
                            <?php if(! $produto->ativo): ?>
                                <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['color' => 'muted']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'muted']); ?>Inativo <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                            <?php elseif($produto->abaixoDoMinimo()): ?>
                                <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['color' => 'danger','icon' => 'fas fa-exclamation-triangle']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'danger','icon' => 'fas fa-exclamation-triangle']); ?>Repor <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                            <?php else: ?>
                                <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['color' => 'success']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'success']); ?>Normal <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-right text-nowrap">
                            <a href="<?php echo e(route('admin.movimentacoes.index', ['produto' => $produto->id])); ?>" class="btn btn-sm btn-outline-secondary"
                               data-toggle="tooltip" title="Movimentações deste produto"><i class="fas fa-exchange-alt"></i></a>
                            <?php if (isset($component)) { $__componentOriginalafff3ec7080c879e2a19819877781dfa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalafff3ec7080c879e2a19819877781dfa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.btn.edit','data' => ['route' => route('admin.produtos.edit', $produto),'can' => 'produtos.gerenciar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('btn.edit'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.produtos.edit', $produto)),'can' => 'produtos.gerenciar']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalafff3ec7080c879e2a19819877781dfa)): ?>
<?php $attributes = $__attributesOriginalafff3ec7080c879e2a19819877781dfa; ?>
<?php unset($__attributesOriginalafff3ec7080c879e2a19819877781dfa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalafff3ec7080c879e2a19819877781dfa)): ?>
<?php $component = $__componentOriginalafff3ec7080c879e2a19819877781dfa; ?>
<?php unset($__componentOriginalafff3ec7080c879e2a19819877781dfa); ?>
<?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('produtos.gerenciar')): ?>
                                <?php if (isset($component)) { $__componentOriginale5b6f642786a9fe3cb09af334eefd88d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale5b6f642786a9fe3cb09af334eefd88d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.btn.confirm','data' => ['route' => route('admin.produtos.destroy', $produto),'method' => 'DELETE','label' => 'Excluir','icon' => 'fas fa-trash-alt','variant' => 'soft-danger','confirm' => 'Excluir o produto '.$produto->codigo.'? As movimentações continuam no histórico.','confirmTitle' => 'Excluir produto','confirmButton' => 'Sim, excluir','confirmVariant' => 'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('btn.confirm'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.produtos.destroy', $produto)),'method' => 'DELETE','label' => 'Excluir','icon' => 'fas fa-trash-alt','variant' => 'soft-danger','confirm' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Excluir o produto '.$produto->codigo.'? As movimentações continuam no histórico.'),'confirm-title' => 'Excluir produto','confirm-button' => 'Sim, excluir','confirm-variant' => 'danger']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale5b6f642786a9fe3cb09af334eefd88d)): ?>
<?php $attributes = $__attributesOriginale5b6f642786a9fe3cb09af334eefd88d; ?>
<?php unset($__attributesOriginale5b6f642786a9fe3cb09af334eefd88d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale5b6f642786a9fe3cb09af334eefd88d)): ?>
<?php $component = $__componentOriginale5b6f642786a9fe3cb09af334eefd88d; ?>
<?php unset($__componentOriginale5b6f642786a9fe3cb09af334eefd88d); ?>
<?php endif; ?>
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

        <?php if($produtos->hasPages()): ?>
             <?php $__env->slot('footer', null, []); ?> 
                <?php echo e($produtos->links()); ?>

             <?php $__env->endSlot(); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/admin/produtos/index.blade.php ENDPATH**/ ?>