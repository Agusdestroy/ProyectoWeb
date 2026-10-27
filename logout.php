<?php
// 1. Retomar la sesión actual y destruirla
session_start();
session_destroy();

// 2. Volver al inicio
header("Location: index.php");
exit;
?>
