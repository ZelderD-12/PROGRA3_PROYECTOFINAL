<?php
session_start();

// Limpiar sesión PHP
$_SESSION = array();
session_destroy();

// Limpiar sessionStorage y redirigir
echo "<script>
    sessionStorage.clear();
    window.location.href = '../../index.php';
</script>";
exit();
?>