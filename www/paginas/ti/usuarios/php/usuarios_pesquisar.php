<?php
header('Content-Type: text/html; charset=utf-8');

function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}
if ($_POST){
	session_start('login');
	$login = $_SESSION['usuario']; 
	$root = $_SERVER['DOCUMENT_ROOT'];
	$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
	include_once($consisccz);
	if (isset($_POST['filtro'])){
		$likeString = '%%%' . mb_strtoupper(retirarEspeciais($_POST['valorinputNome']),'UTF-8') . '%';
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
					//LOG
					$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario'].'.txt';
					$file_data = '<b>[PESQUISAR USUARIO]</b> ['.date('d/m/Y H:i:s').'] [PESQUISADO POR '.$login.']<hr><br>';
					if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
					file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario'].'.txt', $file_data);
					//LOG LOGIN
					$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
					$file_data = '<b>[PESQUISAR USUARIO]</b> ['.date('d/m/Y H:i:s').']['.$_POST['usuario'].']<hr><br>';
					if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
					file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', $file_data);
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