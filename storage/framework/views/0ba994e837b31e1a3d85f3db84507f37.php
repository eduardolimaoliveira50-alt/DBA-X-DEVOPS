
<?php
    $messages = array_filter([
        'success' => session('success') ?? session('status'),
        'error' => session('error') ?? ($errors->any() ? 'Confira os campos destacados: '.$errors->first() : null),
        'warning' => session('warning'),
        'info' => session('info'),
    ]);
?>

<?php if($messages): ?>
    <script type="application/json" id="flash-messages"><?php echo json_encode($messages, 15, 512) ?></script>
<?php endif; ?>
<?php /**PATH C:\Users\Administrador\almoxarifado\resources\views/components/flash-messages.blade.php ENDPATH**/ ?>