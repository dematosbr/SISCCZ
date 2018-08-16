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
	$bcrypt = $root."/sisccz/php/login/bcrypt.php";
	include($consisccz);
	include($bcrypt);
	try {
		$atualiza_sql = $db_con->prepare('UPDATE tbl_tecnologia_da_informacao_usuarios SET usuarios_senha=:senha, usuarios_primeiro_acesso=3 WHERE usuarios_usuario=:usuario');   	
		$atualiza_sql->bindParam(':usuario', mb_strtolower(retirarEspeciais($_POST['usuario2'])));
		$atualiza_sql->bindParam(':senha', Bcrypt::hash($_POST['senha']));
		$senha = Bcrypt::hash($_POST['senha']);
		if ($atualiza_sql->execute()) {
			echo 'editado';
			if ($_POST['usuario2']==$_SESSION['usuario']){
				$_SESSION['usuario'] = $_POST['usuario2'];
				$_SESSION['senha'] = $senha;
			}
			$query_parametros = ( 
			'[ USUÁRIO | <i>'.mb_strtoupper(retirarEspeciais($_POST['usuario2']),'UTF-8').'</i> ] '.
			'[ SENHA | <i>******</i> ] ');
			//LOG
			$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario2'].'.txt';
			$file_data = '<b>[EDITAR SENHA USUÁRIO]</b> ['.date('d/m/Y H:i:s').'] [EDITADO POR '.$login.']<br>'.$query_parametros.'<br><hr><br>';
			if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
			file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario2'].'.txt', $file_data);
			//LOG LOGIN
			$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
			$file_data = '<b>[EDITAR SENHA USUÁRIO]</b> ['.date('d/m/Y H:i:s').']<br>'.$query_parametros.'<hr><br>';
			if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
			file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', $file_data);
		} else {
			echo 'Ocorreu um erro.';
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
} else {
	echo 'Parâmetro vazio.';
}
?>