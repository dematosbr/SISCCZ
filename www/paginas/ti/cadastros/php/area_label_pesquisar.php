<?php
header('Content-Type: text/html; charset=utf-8');

function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}
session_start('login');
$root = $_SERVER['DOCUMENT_ROOT'];
$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
include_once($consisccz);
$pesquisa_sql = $db_con->prepare('SELECT tbl_cadastros_area_setor_area,
										 		  tbl_cadastros_area_setor_latitude,
										 		  tbl_cadastros_area_setor_longitude
                                  FROM tbl_cadastros_area_setor');
if($pesquisa_sql->execute()){
	$data = $pesquisa_sql->rowCount();
	if ($data >= 1) {
		while ($linha_querypesquisa = $pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
			$resultado[] = array (
				'area' => $linha_querypesquisa['tbl_cadastros_area_setor_area'],
				'latitude' => $linha_querypesquisa['tbl_cadastros_area_setor_latitude'],
				'longitude' => $linha_querypesquisa['tbl_cadastros_area_setor_longitude']
				);
		}
		echo (json_encode($resultado));
	} else {
 		echo 'Erro na busca SQL';
	} 
} else {
	echo 'Erro na busca SQL';
} 
?>