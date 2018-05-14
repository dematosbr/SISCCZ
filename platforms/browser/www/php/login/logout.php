<?php
header("Content-Type: text/html; charset=utf-8");

$root = $_SERVER['DOCUMENT_ROOT'];
session_start('login');
$login = $_SESSION['usuario']; 
$fp2 = fopen($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', 'a');
$escreve2 = fwrite($fp2, '<b>[LOGOUT]</b><br> | DATA</b> ['.date('d/m/Y H:i:s').'] <br><b>[FIM]</b><br><hr>');
fclose($fp2);
session_destroy();
echo '<meta HTTP-EQUIV=refresh CONTENT=0;URL=../../index.html>';
?>