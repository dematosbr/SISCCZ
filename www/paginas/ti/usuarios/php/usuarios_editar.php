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
	try {
		$atualiza_sql = $db_con->prepare('UPDATE tbl_tecnologia_da_informacao_usuarios SET usuarios_nome=:nome, usuarios_apelido=:apelido, usuarios_sexo=:sexo, usuarios_matricula=:matricula WHERE usuarios_usuario=:usuario2');   	
		$atualiza_sql->bindParam(':usuario2', mb_strtolower(retirarEspeciais($_POST['usuario2'])));
		$atualiza_sql->bindParam(':nome', mb_strtoupper(retirarEspeciais($_POST['nome'])));
		$atualiza_sql->bindParam(':apelido', ucwords(strtolower($_POST['apelido'])));
		$atualiza_sql->bindParam(':matricula', mb_strtoupper(retirarEspeciais($_POST['matricula'])));
		$atualiza_sql->bindParam(':sexo', mb_strtoupper(retirarEspeciais($_POST['sexo'])));
		if ($atualiza_sql->execute()) {
			echo 'editado';
			if ($_POST['usuario2']==$_SESSION['usuario']){
				$_SESSION['usuario'] = $_POST['usuario'];
				$_SESSION['nome'] = $_POST['nome'];
				$_SESSION['genero'] = $_POST['sexo'];
				$_SESSION['apelido'] = $_POST['apelido'];
				$_SESSION['matricula'] = $_POST['matricula'];
			}
			$query_parametros = ( 
			'[ USUÁRIO | <i>'.mb_strtoupper(retirarEspeciais($_POST['usuario2']),'UTF-8').'</i> ] '.
			'[ SENHA | <i>******</i> ] '.
			'[ NOME | <i>'.mb_strtoupper(retirarEspeciais($_POST['nome']),'UTF-8').'</i> ] '.
			'[ SEXO | <i>'.mb_strtoupper(retirarEspeciais($_POST['sexo']),'UTF-8').'</i> ] '.
			'[ APELIDO | <i>'.mb_strtoupper(retirarEspeciais($_POST['apelido']),'UTF-8').'</i> ] '.
			'[ MATRÍCULA | <i>'.mb_strtoupper(retirarEspeciais($_POST['matricula'],'UTF-8')).'</i> ]');
			//LOG
			$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario2'].'.txt';
			$file_data = '<b>[EDITAR DADOS USUARIO]</b> ['.date('d/m/Y H:i:s').'] [EDITADO POR '.$login.']<br>'.$query_parametros.'<br><hr><br>';
			if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
			file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario2'].'.txt', $file_data);
			//LOG LOGIN
			$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
			$file_data = '<b>[EDITAR DADOS USUARIO]</b> ['.date('d/m/Y H:i:s').']<br>'.$query_parametros.'<hr><br>';
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