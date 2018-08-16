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
	$pesquisa_sql = $db_con->prepare('SELECT * FROM tbl_tecnologia_da_informacao_sistemas_acesso WHERE tbl_tecnologia_da_informacao_sistemas_acesso_usuario=:usuario AND tbl_tecnologia_da_informacao_sistemas_acesso_codigo_funcao=:codigo_sistema');
	$pesquisa_sql->bindParam(':usuario', mb_strtoupper(retirarAcentos($_SESSION['usuario']))); 
	$pesquisa_sql->bindParam(':codigo_sistema', mb_strtoupper(retirarAcentos($_POST['codigo_sistema'])));   		
	if($pesquisa_sql->execute()){
		$data = $pesquisa_sql->rowCount();
	 	if ($data >= 1) {
	 		$linha_querypesquisa=$pesquisa_sql->fetch(PDO::FETCH_ASSOC);
			$resultado[] = array (
				'id' => $linha_querypesquisa['tbl_tecnologia_da_informacao_sistemas_acesso_id'],
				'usuario' => $linha_querypesquisa['tbl_tecnologia_da_informacao_sistemas_acesso_usuario'],
				'codigo_funcao' => $linha_querypesquisa['tbl_tecnologia_da_informacao_sistemas_acesso_codigo_funcao']
			);
			echo (json_encode($resultado));
		} else {
			echo 'nao encontrado';
		}	
	}
}
catch(PDOException $e){
	echo $e->getMessage();
}
?>
