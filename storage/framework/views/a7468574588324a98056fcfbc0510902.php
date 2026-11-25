
<div class="table-responsive">
    <table <?php echo e($attributes->merge(['class' => 'table table-hover'])); ?>>
        <?php if(isset($head)): ?>
            <thead>
                <?php echo e($head); ?>

            </thead>
        <?php endif; ?>
        <tbody>
            <?php echo e($slot); ?>

        </tbody>
    </table>
</div>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/table.blade.php ENDPATH**/ ?>