<?php
header("Content-Type: text/html; charset=utf-8");


function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}

if($_POST) {
	session_start('login');
	$root = $_SERVER['DOCUMENT_ROOT'];
	$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
	require_once($consisccz);
	$bcrypt = $root.'/sisccz/php/login/bcrypt.php';
	require_once($bcrypt);
	try{
   		$pesquisa_sql = $db_con->prepare('SELECT * FROM tbl_tecnologia_da_informacao_usuarios WHERE usuarios_usuario = :usuario');
		$pesquisa_sql->bindParam(':usuario', retirarEspeciais(mb_strtoupper($_POST['usuario'],'UTF-8')));   		
	   	if($pesquisa_sql->execute()){
			$data = $pesquisa_sql->rowCount();
			if ($data==1) {
				$rs_conf_usuario=$pesquisa_sql->fetch(PDO::FETCH_ASSOC);
				$hash2 = $rs_conf_usuario['usuarios_senha'];
				if (Bcrypt::check($_POST['senha'], $hash2)) {
					$nome = $rs_conf_usuario['usuarios_nome'];	
					$acessos = (int)$rs_conf_usuario['usuarios_n_acessos'];
					$primeiro_acesso = (int)$rs_conf_usuario['usuarios_primeiro_acesso'];
					$_SESSION['usuario'] = $_POST['usuario'];
					$_SESSION['senha'] = $hash2;
					$_SESSION['nome'] = $nome;
					$_SESSION['genero'] = $rs_conf_usuario['usuarios_sexo'];
					$_SESSION['apelido'] = $rs_conf_usuario['usuarios_apelido'];
					$_SESSION['matricula'] = $rs_conf_usuario['usuarios_matricula'];
					$_SESSION['primeiro_acesso'] = $rs_conf_usuario['usuarios_primeiro_acesso'];
					$_SESSION['ultimo_acesso'] = $rs_conf_usuario['usuarios_data_ultimo_acesso'];
					$n_acessos = $acessos+1;
					$date = date ('Ymd');
					$atualiza_sql = $db_con->prepare('UPDATE tbl_tecnologia_da_informacao_usuarios SET usuarios_n_acessos=:n_acessos, usuarios_data_ultimo_acesso=:date WHERE usuarios_usuario=:usuario');
					$atualiza_sql->bindParam(':usuario', retirarEspeciais(mb_strtoupper($_POST['usuario'],'UTF-8')));   		
					$atualiza_sql->bindParam(':n_acessos',$n_acessos);
					$atualiza_sql->bindParam(':date', $date);   		
					$atualiza_sql->execute();
					//LOG
					$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario'].'.txt';
					$file_data = '<b>[LOGIN]</b> ['.date('d/m/Y H:i:s').']<hr><br>';
					if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
					file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario'].'.txt', $file_data);
					//LOG	
					echo 'efetuar login';
				} else {
					echo 'senha errada';
				}
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
	echo 'Parâmetro vazio.';
}
?>