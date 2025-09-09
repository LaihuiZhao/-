// JavaScript Document
<script>
    if (<?= isset($error) ? 'true' : 'false' ?>) {
        alert('<?= $error ?>');
        window.location.href = 'login.php';
    }
</script>