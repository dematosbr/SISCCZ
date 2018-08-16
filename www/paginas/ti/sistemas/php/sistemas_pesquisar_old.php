<?php
header("Content-Type: text/html; charset=utf-8");

function retirarAcentos($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}

session_start('login');
$root = $_SERVER['DOCUMENT_ROOT'];
$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
include_once($consisccz);
try {
	if (isset($_POST['menu'])){
		$pesquisa_sql = $db_con->prepare('SELECT * FROM tbl_tecnologia_da_informacao_sistemas_funcoes WHERE tbl_tecnologia_da_informacao_sistemas_funcoes_menu_1=:menu AND tbl_tecnologia_da_informacao_sistemas_funcoes_menu_2=:submenu ORDER BY tbl_tecnologia_da_informacao_sistemas_funcoes_menu_1, tbl_tecnologia_da_informacao_sistemas_funcoes_menu_2, tbl_tecnologia_da_informacao_sistemas_funcoes_codigo_funcao');
		$pesquisa_sql->bindParam(':menu', $_POST['menu']);
		$pesquisa_sql->bindParam(':submenu', $_POST['submenu']);
	} else {
		$pesquisa_sql = $db_con->prepare('SELECT * FROM tbl_tecnologia_da_informacao_sistemas_funcoes ORDER BY tbl_tecnologia_da_informacao_sistemas_funcoes_menu_1, tbl_tecnologia_da_informacao_sistemas_funcoes_menu_2, tbl_tecnologia_da_informacao_sistemas_funcoes_codigo_funcao');
	}
	if($pesquisa_sql->execute()){
		while ($linha_querypesquisa=$pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
			$resultado[] = array (
				'id' => $linha_querypesquisa['tbl_tecnologia_da_informacao_sistemas_funcoes_id'],
				'menu_1' => $linha_querypesquisa['tbl_tecnologia_da_informacao_sistemas_funcoes_menu_1'],
				'menu_2' => $linha_querypesquisa['tbl_tecnologia_da_informacao_sistemas_funcoes_menu_2'],
				'codigo_funcao' => $linha_querypesquisa['tbl_tecnologia_da_informacao_sistemas_funcoes_codigo_funcao'],
				'funcao' => ucwords(strtolower($linha_querypesquisa['tbl_tecnologia_da_informacao_sistemas_funcoes_funcao']))
			);
		}
		echo (json_encode($resultado));
	}
}
catch(PDOException $e){
	echo $e->getMessage();
}
?>
