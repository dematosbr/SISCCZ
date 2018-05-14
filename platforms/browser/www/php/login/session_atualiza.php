<?php
header("Content-Type: text/html; charset=utf-8");

session_start('login');
if (isset($_SESSION['usuario'])) {
		$_SESSION['ultimo_acesso'] = date('Y-m-d');
}
?>