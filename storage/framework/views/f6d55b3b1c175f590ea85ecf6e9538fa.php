<?php $__env->startSection('title', 'Fornecedores'); ?>

<?php $__env->startSection('content_header'); ?>
    <?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => 'Fornecedores','icon' => 'fas fa-truck','crumbs' => ['Estoque', 'Fornecedores'],'subtitle' => 'Quem entrega o material. Toda entrada no estoque indica o fornecedor.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Fornecedores','icon' => 'fas fa-truck','crumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Estoque', 'Fornecedores']),'subtitle' => 'Quem entrega o material. Toda entrada no estoque indica o fornecedor.']); ?>
         <?php $__env->slot('aside', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal8d28c46304aa68e73efb0df04bec20c1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d28c46304aa68e73efb0df04bec20c1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.btn.new','data' => ['route' => route('admin.fornecedores.create'),'title' => 'Novo fornecedor','can' => 'fornecedores.gerenciar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('btn.new'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.fornecedores.create')),'title' => 'Novo fornecedor','can' => 'fornecedores.gerenciar']); ?>
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
    <?php if($totalSemCnpj > 0): ?>
        <div class="ca-callout ca-callout--accent mb-3">
            <i class="fas fa-id-card"></i>
            <div>
                <strong><?php echo e($totalSemCnpj); ?> fornecedor(es) sem CNPJ</strong>, vindos do cadastro antigo.
                <a href="<?php echo e(route('admin.fornecedores.index', ['sem_cnpj' => 1])); ?>">Ver a lista</a> para completar.
            </div>
        </div>
    <?php endif; ?>

    <form method="GET" action="<?php echo e(route('admin.fornecedores.index')); ?>" class="mb-3" data-submit-once="false">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
            <input type="search" name="busca" value="<?php echo e($busca); ?>" class="form-control" aria-label="Buscar fornecedor"
                   placeholder="Buscar por razão social ou CNPJ">
            <?php if($semCnpj): ?>
                <input type="hidden" name="sem_cnpj" value="1">
            <?php endif; ?>
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <?php if($busca !== '' || $semCnpj): ?>
                    <a href="<?php echo e(route('admin.fornecedores.index')); ?>" class="btn btn-outline-secondary">Limpar</a>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => $semCnpj ? 'Sem CNPJ' : 'Cadastrados','icon' => 'fas fa-list-ul','count' => $fornecedores->total(),'noPadding' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($semCnpj ? 'Sem CNPJ' : 'Cadastrados'),'icon' => 'fas fa-list-ul','count' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($fornecedores->total()),'no-padding' => true]); ?>
        <?php if($fornecedores->isEmpty()): ?>
            <?php if (isset($component)) { $__componentOriginal074a021b9d42f490272b5eefda63257c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal074a021b9d42f490272b5eefda63257c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty-state','data' => ['icon' => 'fas fa-truck','title' => 'Nenhum fornecedor encontrado']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fas fa-truck','title' => 'Nenhum fornecedor encontrado']); ?>Confira o que foi digitado. <?php echo $__env->renderComponent(); ?>
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
                        <th>Fornecedor</th>
                        <th>CNPJ</th>
                        <th>Contato</th>
                        <th class="text-center">Entradas</th>
                        <th class="text-right">Ações</th>
                    </tr>
                 <?php $__env->endSlot(); ?>

                <?php $__currentLoopData = $fornecedores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fornecedor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <span class="ca-person">
                                <span class="ca-person__avatar"><i class="fas fa-truck"></i></span>
                                <span class="ca-person__name"><?php echo e($fornecedor->razao_social); ?></span>
                            </span>
                        </td>
                        <td class="text-nowrap">
                            <?php if($fornecedor->cnpj): ?>
                                <?php echo e(\App\Support\Cnpj::format($fornecedor->cnpj)); ?>

                            <?php else: ?>
                                <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['color' => 'warning','icon' => 'fas fa-exclamation-circle']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'warning','icon' => 'fas fa-exclamation-circle']); ?>pendente <?php echo $__env->renderComponent(); ?>
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
                        <td class="small">
                            <div><?php echo e($fornecedor->email ?? '—'); ?></div>
                            <div class="text-muted"><?php echo e($fornecedor->telefone ?? ''); ?></div>
                        </td>
                        <td class="text-center"><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['color' => $fornecedor->movimentacoes_count ? 'primary' : 'muted','dot' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($fornecedor->movimentacoes_count ? 'primary' : 'muted'),'dot' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?><?php echo e(number_format($fornecedor->movimentacoes_count, 0, ',', '.')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></td>
                        <td class="text-right text-nowrap">
                            <?php if (isset($component)) { $__componentOriginalafff3ec7080c879e2a19819877781dfa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalafff3ec7080c879e2a19819877781dfa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.btn.edit','data' => ['route' => route('admin.fornecedores.edit', $fornecedor),'can' => 'fornecedores.gerenciar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('btn.edit'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.fornecedores.edit', $fornecedor)),'can' => 'fornecedores.gerenciar']); ?>
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
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fornecedores.gerenciar')): ?>
                                <?php if (isset($component)) { $__componentOriginale5b6f642786a9fe3cb09af334eefd88d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale5b6f642786a9fe3cb09af334eefd88d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.btn.confirm','data' => ['route' => route('admin.fornecedores.destroy', $fornecedor),'method' => 'DELETE','label' => 'Excluir','icon' => 'fas fa-trash-alt','variant' => 'soft-danger','confirm' => 'Excluir o fornecedor '.$fornecedor->razao_social.'? As entradas antigas continuam mostrando o nome.','confirmTitle' => 'Excluir fornecedor','confirmButton' => 'Sim, excluir','confirmVariant' => 'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('btn.confirm'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.fornecedores.destroy', $fornecedor)),'method' => 'DELETE','label' => 'Excluir','icon' => 'fas fa-trash-alt','variant' => 'soft-danger','confirm' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Excluir o fornecedor '.$fornecedor->razao_social.'? As entradas antigas continuam mostrando o nome.'),'confirm-title' => 'Excluir fornecedor','confirm-button' => 'Sim, excluir','confirm-variant' => 'danger']); ?>
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

        <?php if($fornecedores->hasPages()): ?>
             <?php $__env->slot('footer', null, []); ?> 
                <?php echo e($fornecedores->links()); ?>

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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/admin/fornecedores/index.blade.php ENDPATH**/ ?>