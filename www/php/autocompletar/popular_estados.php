<?php
header("Content-Type: text/html; charset=utf-8");


function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}


$_POST['estados']='a';
if ($_POST){
	session_start('login');
	$root = $_SERVER['DOCUMENT_ROOT'];
	$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
	include_once($consisccz);
	if (isset($_POST['estados'])){
		try {
			$pesquisa_sql = $db_con->prepare('SELECT DISTINCT tbl_cadastros_estados_sigla_unidade_federativa 
			                                  FROM tbl_cadastros_estados
			                                  ORDER BY tbl_cadastros_estados_sigla_unidade_federativa');
			if($pesquisa_sql->execute()){
				$data = $pesquisa_sql->rowCount();
				if ($data >= 1) {
					while ($linha_querypesquisa = $pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
						$resultado[] = array (
						'estado' => $linha_querypesquisa['tbl_cadastros_estados_sigla_unidade_federativa']);
					}
		   		} else {
		    		echo 'Erro na busca SQL';
		   		} 
			} else {
				echo 'Erro na busca SQL';
			}
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}
	echo (json_encode($resultado));
} else {
	echo 'Parâmetro vazio.';
}
?>