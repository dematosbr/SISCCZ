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
				' | <i>usuário : </i>'.mb_strtoupper(retirarEspeciais($_POST['usuario']),'UTF-8').
				' | <i>senha : </i> ******'.
				' | <i>nome : </i>'.mb_strtoupper(retirarEspeciais($_POST['nome']),'UTF-8').
				' | <i>sexo : </i>'.mb_strtoupper(retirarEspeciais($_POST['sexo']),'UTF-8').
				' | <i>apelido : </i>'.mb_strtoupper(retirarEspeciais($_POST['apelido']),'UTF-8').
				' | <i>matricula : </i>'.mb_strtoupper(retirarEspeciais($_POST['matricula'],'UTF-8'))
			);
			$fp = fopen($root.'/sisccz/paginas/ti/usuarios/logs/'.$_POST['usuario'].'.txt', 'a');
			$escreve = fwrite($fp, '<b>[INSERIR USUARIO]</b><br>'.$query_parametros.'<br><b> | INSERIDO POR </b>['.$login.']<br><b> | DATA</b> ['.date('d/m/Y H:i:s').'] <br><b>[FIM]</b><br><hr>');
			fclose($fp);
			$fp2 = fopen($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', 'a');
			$escreve2 = fwrite($fp2, '<b>[INSERIR USUARIO]</b><br>'.$query_parametros.'<br><b> | DATA</b> ['.date('d/m/Y H:i:s').'] <br><b>[FIM]</b><br><hr>');
			fclose($fp2);
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