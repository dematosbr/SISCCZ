<?php
header("Content-Type: text/html; charset=utf-8");

session_start('login');
if (isset($_SESSION['usuario'])) {
	$resultado[] = array (
		'usuario' =>$_SESSION['usuario'],
		'nome' => ucwords(strtolower($_SESSION['nome'])),
		'genero' => $_SESSION['genero'],
		'apelido' => $_SESSION['apelido'],
		'matricula' => $_SESSION['matricula'],
		'ultimo_acesso' => $_SESSION['ultimo_acesso'],
		'primeiro_acesso' => $_SESSION['primeiro_acesso']);
	echo (json_encode($resultado));
}
?>