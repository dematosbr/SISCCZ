<?php
header("Content-Type: text/html; charset=utf-8");


function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}

session_start('login');
$root = $_SERVER['DOCUMENT_ROOT'];
$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
include_once($consisccz);
if(isset($_POST['estado'])){
	try{
		$pesquisa_sql = $db_con->prepare("SELECT tbl_cadastros_municipios_nome_municipio FROM tbl_cadastros_municipios WHERE tbl_cadastros_municipios_sigla_unidade_federativa=:estado");
		$pesquisa_sql->bindParam(':estado', $_POST['estado']);
		if($pesquisa_sql->execute()){
			while ($row=$pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
				$resultado[] = array (
					'municipio' => $row['tbl_cadastros_municipios_nome_municipio']);
			}
			echo (json_encode($resultado));
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
} else {
	try{
		$pesquisa_sql = $db_con->prepare("SELECT tbl_cadastros_municipios_nome_municipio FROM tbl_cadastros_municipios");
		if($pesquisa_sql->execute()){
			while ($row=$pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
				$resultado[] = array (
					'municipio' => $row['tbl_cadastros_municipios_nome_municipio']);
			}
			echo (json_encode($resultado));
		}
	}
	
	catch(PDOException $e){
		echo $e->getMessage();
	}
}
?>