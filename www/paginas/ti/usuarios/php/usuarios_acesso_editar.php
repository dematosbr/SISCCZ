<?php
header('Content-Type: text/html; charset=utf-8');

function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}
if ($_POST){
	session_start("login"); 
	$login = $_SESSION['usuario']; 
	$root   = $_SERVER['DOCUMENT_ROOT'];
	$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
	include($consisccz);
	if($_POST['opcao']=='inserir'){
		try {
			$insere_sql= $db_con->prepare('INSERT INTO 
											tbl_tecnologia_da_informacao_sistemas_acesso 
												(tbl_tecnologia_da_informacao_sistemas_acesso_usuario, tbl_tecnologia_da_informacao_sistemas_acesso_codigo_funcao) 
											VALUES 
												(:usuario, :funcao)');
			$insere_sql->bindParam(':usuario', mb_strtolower(retirarEspeciais($_POST['usuario'])));
			$insere_sql->bindParam(':funcao', mb_strtoupper(retirarEspeciais($_POST['funcao'])));
			$insere_sql->execute();
			$query_parametros = ( 
			'[ USUÁRIO | <i>'.mb_strtoupper(retirarEspeciais($_POST['usuario']),'UTF-8').'</i> ] '.
			'[ FUNÇÃO | <i>'.mb_strtoupper(retirarEspeciais($_POST['funcao'],'UTF-8')).'</i> ]');
			//LOG
			$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario'].'.txt';
			$file_data = '<b>[EDITAR USUÁRIO INSERIR ACESSO]</b> ['.date('d/m/Y H:i:s').'] [EDITADO POR '.$login.']<br>'.$query_parametros.'<br><hr><br>';
			if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
			file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario'].'.txt', $file_data);
			//LOG LOGIN
			$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
			$file_data = '<b>[EDITAR USUÁRIO INSERIR ACESSO]</b> ['.date('d/m/Y H:i:s').']<br>'.$query_parametros.'<hr><br>';
			if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
			file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', $file_data);
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	} else {
		try {
			$delete_sql= $db_con->prepare('DELETE FROM tbl_tecnologia_da_informacao_sistemas_acesso WHERE tbl_tecnologia_da_informacao_sistemas_acesso_usuario=:usuario AND tbl_tecnologia_da_informacao_sistemas_acesso_codigo_funcao=:funcao'); 
			$delete_sql->bindParam(':usuario', mb_strtolower(retirarEspeciais($_POST['usuario'])));
			$delete_sql->bindParam(':funcao', mb_strtoupper(retirarEspeciais($_POST['funcao'])));
			$delete_sql->execute();
			$query_parametros = ( 
			'[ USUÁRIO | <i>'.mb_strtoupper(retirarEspeciais($_POST['usuario']),'UTF-8').'</i> ] '.
			'[ FUNÇÃO | <i>'.mb_strtoupper(retirarEspeciais($_POST['funcao'],'UTF-8')).'</i> ]');
			//LOG
			$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario'].'.txt';
			$file_data = '<b>[EDITAR USUÁRIO EXCLUIR ACESSO]</b> ['.date('d/m/Y H:i:s').'] [EDITADO POR '.$login.']<br>'.$query_parametros.'<br><hr><br>';
			if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
			file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario'].'.txt', $file_data);
			//LOG LOGIN
			$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
			$file_data = '<b>[EDITAR USUÁRIO EXCLUIR ACESSO]</b> ['.date('d/m/Y H:i:s').']<br>'.$query_parametros.'<hr><br>';
			if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
			file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', $file_data);
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}
} else {
	echo 'Parâmetro vazio.';
}
?>