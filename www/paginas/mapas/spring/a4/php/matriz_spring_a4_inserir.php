<?php
header("Content-Type: text/html; charset=utf-8");

function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}
if (isset($_FILES['arquivo'])) {
	if (is_uploaded_file($_FILES['arquivo']['tmp_name'])) {
        $name = $_FILES['arquivo']['name'];
		$tmpName  = $_FILES['arquivo']['tmp_name'];
		$size = intval($_FILES['arquivo']['size']);
        $mime = $_FILES['arquivo']['type'];
        
        $fp = fopen($tmpName, 'r');
    	$data = fread($fp, filesize($tmpName));
    	fclose($fp);
        
		session_start('login');
		$login = $_SESSION['usuario'];
		$root = $_SERVER['DOCUMENT_ROOT'];
		$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
		include_once($consisccz);
		try {
			$insere_sql = $db_con->prepare('INSERT INTO
												tbl_mapas_matriz_spring_a4
												(tbl_mapas_matriz_spring_nome, tbl_mapas_matriz_spring_mime, tbl_mapas_matriz_spring_size, tbl_mapas_matriz_spring_dados, tbl_mapas_matriz_spring_data, tbl_mapas_matriz_spring_usuario,
												 tbl_mapas_matriz_spring_area, tbl_mapas_matriz_spring_mapa, tbl_mapas_matriz_spring_lat1, tbl_mapas_matriz_spring_lat2, tbl_mapas_matriz_spring_lng1, 
												 tbl_mapas_matriz_spring_lng2) 
												VALUES 
												(:filename, :mime, :size, :data, :dataatual, :usuario, :area, :mapa, :lat1, :lat2, :lng1, :lng2)');
			$insere_sql->bindParam(':filename', $name );  
			$insere_sql->bindParam(':mime', $mime); 
			$insere_sql->bindParam(':size', $size); 
			$insere_sql->bindValue(':data', $data, PDO::PARAM_LOB);   		
			$insere_sql->bindParam(':dataatual', date('Y-m-d'));
			$insere_sql->bindParam(':usuario', $login);
			$insere_sql->bindParam(':area', mb_strtoupper(retirarEspeciais($_POST['area']),'UTF-8'));
			$insere_sql->bindParam(':mapa', mb_strtoupper(retirarEspeciais($_POST['mapa']),'UTF-8'));
			$insere_sql->bindParam(':lat1', mb_strtoupper(retirarEspeciais($_POST['lat1']),'UTF-8'));
			$insere_sql->bindParam(':lat2', mb_strtoupper(retirarEspeciais( $_POST['lat2']),'UTF-8'));
			$insere_sql->bindParam(':lng1', mb_strtoupper(retirarEspeciais($_POST['lng1']),'UTF-8'));
			$insere_sql->bindParam(':lng2', mb_strtoupper(retirarEspeciais($_POST['lng2']),'UTF-8'));
			if($insere_sql->execute()){
				echo 'inserido';
				$query_parametros = ( 
				'[ ÁREA | <i>'.mb_strtoupper(retirarEspeciais($_POST['area']),'UTF-8').'</i> ] '.
				'[ MAPA | <i>'.mb_strtoupper(retirarEspeciais($_POST['mapa']),'UTF-8').'</i> ] '.
				'[ LATITUDE 1 | <i>'.mb_strtoupper(retirarEspeciais($_POST['lat1']),'UTF-8').'</i> ] '.
				'[ LONGITUDE 1 | <i>'.mb_strtoupper(retirarEspeciais($_POST['lng1']),'UTF-8').'</i> ] '.
				'[ LATITUDE 2 | <i>'.mb_strtoupper(retirarEspeciais($_POST['lat2']),'UTF-8').'</i> ] '.
				'[ LONGITUDE 2 | <i>'.mb_strtoupper(retirarEspeciais($_POST['lng2'],'UTF-8')).'</i> ]');
				//LOG
				$filename = $root.'/sisccz/paginas/mapas/spring/a4/logs/'.$name.'.txt';
				$file_data = '<b>[INSERIR MATRIZ A4]</b> ['.date('d/m/Y H:i:s').'] [INSERIDO POR '.$login.']<br>'.$query_parametros.'<br><hr><br>';
				if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
				file_put_contents($root.'/sisccz/paginas/mapas/spring/a4/logs/'.$name.'.txt', $file_data);
				//LOG LOGIN
				$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
				$file_data = '<b>[INSERIR MATRIZ A4]</b> ['.date('d/m/Y H:i:s').']<br>'.$query_parametros.'<hr><br>';
				if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
				file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', $file_data);
		   	} else {
		    	echo 'Ocorreu um erro';
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