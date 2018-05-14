<?php
header('Content-Type: text/html; charset=utf-8');

function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}
if ($_POST){
	session_start('login');
	$root = $_SERVER['DOCUMENT_ROOT'];
	$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
	include_once($consisccz);
	if (isset($_POST['matricula'])){
		try {
			$pesquisa_sql = $db_con->prepare('SELECT * FROM tbl_tecnologia_da_informacao_usuarios WHERE usuarios_matricula=:matricula');
			$pesquisa_sql->bindParam(':matricula', mb_strtoupper(retirarEspeciais($_POST['matricula']),'UTF-8'));   		
			if($pesquisa_sql->execute()){
				$data = $pesquisa_sql->rowCount();
		 		if ($data >= 1) {
		 			$linha_querypesquisa=$pesquisa_sql->fetch(PDO::FETCH_ASSOC);
					$resultado[] = array (
						'usuarios_usuario' => $linha_querypesquisa['usuarios_usuario'],
						'usuarios_nome' => $linha_querypesquisa['usuarios_nome'],
						'usuarios_apelido' => $linha_querypesquisa['usuarios_apelido'],
						'usuarios_sexo' => $linha_querypesquisa['usuarios_sexo'],
						'usuarios_matricula' => $linha_querypesquisa['usuarios_matricula'],
						'usuarios_n_acessos' => $linha_querypesquisa['usuarios_n_acessos'],
						'usuarios_data_ultimo_acesso' => implode('/',array_reverse(explode('-',$linha_querypesquisa['usuarios_data_ultimo_acesso']))));
					echo (json_encode($resultado));
				} else {
					echo 'usuario nao encontrado';
				}
		   	} else{
		    	echo 'Erro na busca SQL';
		   	} 
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	} 
	else if (isset($_POST['filtro'])){
		if ($_POST['filtro']=='INICIA'){
			$likeString = mb_strtoupper(retirarEspeciais($_POST['valorinputNome']),'UTF-8') . '%';
		} else {
			$likeString = '%%%' . mb_strtoupper(retirarEspeciais($_POST['valorinputNome']),'UTF-8') . '%';
		}
		try {
			$pesquisa_sql = $db_con->prepare('SELECT * FROM tbl_tecnologia_da_informacao_usuarios WHERE usuarios_nome LIKE ?'); 
			$pesquisa_sql->bindParam(1, $likeString);
			if($pesquisa_sql->execute()){
				$data = $pesquisa_sql->rowCount();
		 		if ($data >= 1) {
					while ($linha_querypesquisa=$pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
						$resultado[] = array (
							'usuarios_usuario' => $linha_querypesquisa['usuarios_usuario'],
							'usuarios_nome' => $linha_querypesquisa['usuarios_nome'],
							'usuarios_apelido' => $linha_querypesquisa['usuarios_apelido'],
							'usuarios_sexo' => $linha_querypesquisa['usuarios_sexo'],
							'usuarios_matricula' => $linha_querypesquisa['usuarios_matricula'],
							'usuarios_n_acessos' => $linha_querypesquisa['usuarios_n_acessos'],
							'usuarios_data_ultimo_acesso' => implode('/',array_reverse(explode('-',$linha_querypesquisa['usuarios_data_ultimo_acesso']))));
					}
					echo (json_encode($resultado));
				} else {
					echo 'usuario nao encontrado';
				}
		   	} else{
		    	echo 'Erro na busca SQL';
		   	} 
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	} else {
		try {
			$pesquisa_sql = $db_con->prepare('SELECT * FROM tbl_tecnologia_da_informacao_usuarios WHERE usuarios_usuario=:usuario');
			$pesquisa_sql->bindParam(':usuario', mb_strtoupper(retirarEspeciais($_POST['usuario']),'UTF-8'));   		
			if($pesquisa_sql->execute()){
				$data = $pesquisa_sql->rowCount();
		 		if ($data >= 1) {
		 			$linha_querypesquisa=$pesquisa_sql->fetch(PDO::FETCH_ASSOC);
					$resultado[] = array (
						'usuarios_usuario' => $linha_querypesquisa['usuarios_usuario'],
						'usuarios_senha' => $linha_querypesquisa['usuarios_senha'],
						'usuarios_nome' => $linha_querypesquisa['usuarios_nome'],
						'usuarios_apelido' => $linha_querypesquisa['usuarios_apelido'],
						'usuarios_sexo' => $linha_querypesquisa['usuarios_sexo'],
						'usuarios_matricula' => $linha_querypesquisa['usuarios_matricula'],
						'usuarios_n_acessos' => $linha_querypesquisa['usuarios_n_acessos'],
						'usuarios_data_ultimo_acesso' => implode('/',array_reverse(explode('-',$linha_querypesquisa['usuarios_data_ultimo_acesso']))));
					echo (json_encode($resultado));
				} else {
					echo 'usuario nao encontrado';
				}
		   	} else{
		    	echo 'Erro na busca SQL';
		   	} 
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}
} else {
	echo 'Parâmetro vazio.';
}
?>