<?php
header("Content-Type: text/html; charset=utf-8");


function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}
if ($_POST){
	session_start("login"); 
	$root   = $_SERVER['DOCUMENT_ROOT'];
	$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
	$bcrypt = $root."/sisccz/php/login/bcrypt.php";
	include($consisccz);
	include($bcrypt);
	try {
		$atualiza_sql = $db_con->prepare('UPDATE tbl_tecnologia_da_informacao_usuarios SET usuarios_nome=:nome, usuarios_apelido=:apelido, usuarios_sexo=:sexo, usuarios_matricula=:matricula, usuarios_primeiro_acesso=2 WHERE usuarios_usuario=:usuario');   	
		$atualiza_sql->bindParam(':usuario', mb_strtolower(retirarEspeciais($_POST['usuario'])));
		$atualiza_sql->bindParam(':nome', mb_strtoupper(retirarEspeciais($_POST['nome'])));
		$atualiza_sql->bindParam(':apelido', ucwords(strtolower($_POST['apelido'])));
		$atualiza_sql->bindParam(':matricula', mb_strtoupper(retirarEspeciais($_POST['matricula'])));
		$atualiza_sql->bindParam(':sexo', mb_strtoupper(retirarEspeciais($_POST['sexo'])));
		$senha = $_SESSION['senha'];
		if ($_POST['senha']!=""){
			$atualiza_sql = $db_con->prepare('UPDATE tbl_tecnologia_da_informacao_usuarios SET usuarios_nome=:nome, usuarios_apelido=:apelido, usuarios_sexo=:sexo, usuarios_matricula=:matricula, usuarios_senha=:senha, usuarios_primeiro_acesso=2 WHERE usuarios_usuario=:usuario');   	
			$atualiza_sql->bindParam(':usuario', mb_strtolower(retirarEspeciais($_POST['usuario'])));
			$atualiza_sql->bindParam(':nome', mb_strtoupper(retirarEspeciais($_POST['nome'])));
			$atualiza_sql->bindParam(':apelido', ucwords(strtolower($_POST['apelido'])));
			$atualiza_sql->bindParam(':matricula', mb_strtoupper(retirarEspeciais($_POST['matricula'])));
			$atualiza_sql->bindParam(':sexo', mb_strtoupper(retirarEspeciais($_POST['sexo'])));
			$atualiza_sql->bindParam(':senha', Bcrypt::hash($_POST['senha']));
			$senha = Bcrypt::hash($_POST['senha']);
		}
		if ($atualiza_sql->execute()) {
			echo 'editado';
			$_SESSION['usuario'] = $_POST['usuario'];
			$_SESSION['senha'] = $senha;
			$_SESSION['nome'] = $_POST['nome'];
			$_SESSION['genero'] = $_POST['sexo'];
			$_SESSION['apelido'] = $_POST['apelido'];
			$_SESSION['matricula'] = $_POST['matricula'];
			$_SESSION['primeiro_acesso'] = 2;
			$_SESSION['ultimo_acesso'] = date('Y-m-d');
			$query_parametros = ( 
			'[ USUÁRIO | <i>'.mb_strtoupper(retirarEspeciais($_POST['usuario']),'UTF-8').'</i> ] '.
			'[ SENHA | <i>******</i> ] '.
			'[ NOME | <i>'.mb_strtoupper(retirarEspeciais($_POST['nome']),'UTF-8').'</i> ] '.
			'[ SEXO | <i>'.mb_strtoupper(retirarEspeciais($_POST['sexo']),'UTF-8').'</i> ] '.
			'[ APELIDO | <i>'.mb_strtoupper(retirarEspeciais($_POST['apelido']),'UTF-8').'</i> ] '.
			'[ MATRÍCULA | <i>'.mb_strtoupper(retirarEspeciais($_POST['matricula'],'UTF-8')).'</i> ]');
			//LOG LOGIN
			$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario'].'.txt';
			$file_data = '<b>[EDITAR PERFIL USUARIO]</b> ['.date('d/m/Y H:i:s').']<br>'.$query_parametros.'<hr><br>';
			if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
			file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario'].'.txt', $file_data);
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