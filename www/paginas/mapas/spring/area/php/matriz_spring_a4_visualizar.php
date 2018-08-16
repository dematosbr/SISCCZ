<?php
header("Content-Type: text/html; charset=utf-8");

function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}
session_start('login');
$login = $_SESSION['usuario'];
$root = $_SERVER['DOCUMENT_ROOT'];
$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
include_once($consisccz);
if ($_GET){
	try {
		if (isset($_GET['id'])){
			$pesquisa_sql = $db_con->prepare('SELECT * FROM tbl_mapas_matriz_spring_a4 WHERE tbl_mapas_matriz_spring_id=:id');
			$pesquisa_sql->bindParam(':id', $_GET['id']);
		}
		if($pesquisa_sql->execute()){
			$linha_querypesquisa=$pesquisa_sql->fetch(PDO::FETCH_ASSOC);
            header("Content-Type: ". $linha_querypesquisa['tbl_mapas_matriz_spring_mime']);
            header("Content-Length: ". $linha_querypesquisa['tbl_mapas_matriz_spring_size']);
            header("Content-Disposition: inline; filename=". $linha_querypesquisa['tbl_mapas_matriz_spring_nome']);
            echo $linha_querypesquisa['tbl_mapas_matriz_spring_dados'];
			//LOG
			$filename = $root.'/sisccz/paginas/mapas/spring/a4/logs/'.$linha_querypesquisa['tbl_mapas_matriz_spring_nome'].'.txt';
			$file_data = '<b>[VISUALIZAR MATRIZ A4]</b> ['.date('d/m/Y H:i:s').'] [VISUALIZADO POR '.$login.']<hr><br>';
			if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
			file_put_contents($root.'/sisccz/paginas/mapas/spring/a4/logs/'.$linha_querypesquisa['tbl_mapas_matriz_spring_nome'].'.txt', $file_data);
			//LOG LOGIN
			$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
			$file_data = '<b>[VISUALIZAR MATRIZ A4]</b> ['.date('d/m/Y H:i:s').']'.$linha_querypesquisa['tbl_mapas_matriz_spring_nome'].'<hr><br>';
			if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
			file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', $file_data);
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
} else {
	echo 'Parâmetro vazio.';
}

?>
