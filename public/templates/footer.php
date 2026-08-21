<?php 
require_once __DIR__ . '/../../src/php/Flash.php';
$flash = get_flash(); 
?>

</main>
<script src="/js/bootstrap.bundle.js"></script>
<script src="/js/toast.js"></script>
<script src="/js/media.js"></script>
<script src="/js/dashboard.js"></script>
<?php if ($flash): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        show_message(<?= json_encode($flash['message']) ?>, <?= json_encode($flash['type']) ?>);
    });
</script>
<?php endif; ?>
</body>
</html>



