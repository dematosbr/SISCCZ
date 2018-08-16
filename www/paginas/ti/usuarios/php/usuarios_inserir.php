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
		$insere_sql= $db_con->prepare('INSERT INTO 
										tbl_tecnologia_da_informacao_usuarios 
											(usuarios_usuario, usuarios_senha, usuarios_nome, usuarios_apelido, usuarios_matricula, usuarios_sexo, usuarios_primeiro_acesso) 
										VALUES 
											(:usuario, :senha, :nome, :apelido, :matricula, :sexo, 1)');
		$insere_sql->bindParam(':usuario', mb_strtolower(retirarEspeciais($_POST['usuario']),'UTF-8'));
		$insere_sql->bindParam(':senha', Bcrypt::hash($_POST['senha']));
		$insere_sql->bindParam(':nome', mb_strtoupper(retirarEspeciais($_POST['nome']),'UTF-8'));
		$insere_sql->bindParam(':apelido', ucwords(mb_strtolower($_POST['apelido'],'UTF-8')));
		$insere_sql->bindParam(':matricula', mb_strtoupper(retirarEspeciais($_POST['matricula']),'UTF-8'));
		$insere_sql->bindParam(':sexo', mb_strtoupper(retirarEspeciais( $_POST['sexo']),'UTF-8'));
		if ($insere_sql->execute()) {
			echo 'inserido';
			$query_parametros = ( 
			'[ USUÁRIO | <i>'.mb_strtoupper(retirarEspeciais($_POST['usuario']),'UTF-8').'</i> ] '.
			'[ SENHA | <i>******</i> ] '.
			'[ NOME | <i>'.mb_strtoupper(retirarEspeciais($_POST['nome']),'UTF-8').'</i> ] '.
			'[ SEXO | <i>'.mb_strtoupper(retirarEspeciais($_POST['sexo']),'UTF-8').'</i> ] '.
			'[ APELIDO | <i>'.mb_strtoupper(retirarEspeciais($_POST['apelido']),'UTF-8').'</i> ] '.
			'[ MATRÍCULA | <i>'.mb_strtoupper(retirarEspeciais($_POST['matricula'],'UTF-8')).'</i> ]');
			//LOG
			$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario'].'.txt';
			$file_data = '<b>[INSERIR USUARIO]</b> ['.date('d/m/Y H:i:s').'] [INSERIDO POR '.$login.']<br>'.$query_parametros.'<br><hr><br>';
			if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
			file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario'].'.txt', $file_data);
			//LOG LOGIN
			$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
			$file_data = '<b>[INSERIR USUARIO]</b> ['.date('d/m/Y H:i:s').']<br>'.$query_parametros.'<hr><br>';
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