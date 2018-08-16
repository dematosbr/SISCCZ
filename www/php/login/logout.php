<?php
header("Content-Type: text/html; charset=utf-8");

$root = $_SERVER['DOCUMENT_ROOT'];
session_start('login');
$login = $_SESSION['usuario']; 
//LOG
$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
$file_data = '<b>[LOGOUT]</b> ['.date('d/m/Y H:i:s').']<hr><br>';
if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', $file_data);
//LOG
session_destroy();
echo '<meta HTTP-EQUIV=refresh CONTENT=0;URL=../..>';
?>