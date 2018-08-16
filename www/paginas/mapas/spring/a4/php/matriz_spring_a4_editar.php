<?php
header("Content-Type: text/html; charset=utf-8");

function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}
if (isset($_POST['id'])){
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
				$atualiza_sql = $db_con->prepare('UPDATE
													tbl_mapas_matriz_spring_a4
												  SET
													tbl_mapas_matriz_spring_nome=:filename, 
													tbl_mapas_matriz_spring_mime=:mime, 
													tbl_mapas_matriz_spring_size=:size, 
													tbl_mapas_matriz_spring_dados=:data, 
													tbl_mapas_matriz_spring_data=:dataatual, 
													tbl_mapas_matriz_spring_usuario=:usuario,
													tbl_mapas_matriz_spring_lat1=:lat1, 
													tbl_mapas_matriz_spring_lat2=:lat2, 
													tbl_mapas_matriz_spring_lng1=:lng1, 
													tbl_mapas_matriz_spring_lng2=:lng2
												  WHERE
												    tbl_mapas_matriz_spring_id=:id');
				$atualiza_sql->bindParam(':filename', $name );  
				$atualiza_sql->bindParam(':mime', $mime); 
				$atualiza_sql->bindParam(':size', $size); 
				$atualiza_sql->bindValue(':data', $data, PDO::PARAM_LOB);   		
				$atualiza_sql->bindParam(':dataatual', date('Y-m-d'));
				$atualiza_sql->bindParam(':usuario', $login);
				$atualiza_sql->bindParam(':lat1', $_POST['lat1']);
				$atualiza_sql->bindParam(':lat2', $_POST['lat2']);
				$atualiza_sql->bindParam(':lng1', $_POST['lng1']);
				$atualiza_sql->bindParam(':lng2', $_POST['lng2']);
				$atualiza_sql->bindParam(':id', $_POST['id']);
				
				if($atualiza_sql->execute()){
					echo 'editado';
					$query_parametros = ( 
					'[ ÁREA | <i>'.mb_strtoupper(retirarEspeciais($_POST['area']),'UTF-8').'</i> ] '.
					'[ MAPA | <i>'.mb_strtoupper(retirarEspeciais($_POST['mapa']),'UTF-8').'</i> ] '.
					'[ LATITUDE 1 | <i>'.mb_strtoupper(retirarEspeciais($_POST['lat1']),'UTF-8').'</i> ] '.
					'[ LONGITUDE 1 | <i>'.mb_strtoupper(retirarEspeciais($_POST['lng1']),'UTF-8').'</i> ] '.
					'[ LATITUDE 2 | <i>'.mb_strtoupper(retirarEspeciais($_POST['lat2']),'UTF-8').'</i> ] '.
					'[ LONGITUDE 2 | <i>'.mb_strtoupper(retirarEspeciais($_POST['lng2'],'UTF-8')).'</i> ]');
					//LOG
					$filename = $root.'/sisccz/paginas/mapas/spring/a4/logs/'.$name.'.txt';
					$file_data = '<b>[EDITAR MATRIZ A4]</b> ['.date('d/m/Y H:i:s').'] [EDITADO POR '.$login.']<br>'.$query_parametros.'<br><hr><br>';
					if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
					file_put_contents($root.'/sisccz/paginas/mapas/spring/a4/logs/'.$name.'.txt', $file_data);
					//LOG LOGIN
					$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
					$file_data = '<b>[EDITAR MATRIZ A4]</b> ['.date('d/m/Y H:i:s').']<br>'.$query_parametros.'<hr><br>';
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
		session_start('login');
		$login = $_SESSION['usuario'];
		$root = $_SERVER['DOCUMENT_ROOT'];
		$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
		$name = mb_strtoupper(retirarEspeciais($_POST['nome']),'UTF-8');
		include_once($consisccz);
		try {
			$atualiza_sql = $db_con->prepare('UPDATE
												tbl_mapas_matriz_spring_a4
											  SET
												tbl_mapas_matriz_spring_data=:dataatual, 
												tbl_mapas_matriz_spring_usuario=:usuario,
												tbl_mapas_matriz_spring_lat1=:lat1, 
												tbl_mapas_matriz_spring_lat2=:lat2, 
												tbl_mapas_matriz_spring_lng1=:lng1, 
												tbl_mapas_matriz_spring_lng2=:lng2
											  WHERE
											    tbl_mapas_matriz_spring_id=:id');
			$atualiza_sql->bindParam(':dataatual', date('Y-m-d'));
			$atualiza_sql->bindParam(':usuario', $login);
			$atualiza_sql->bindParam(':lat1', $_POST['lat1']);
			$atualiza_sql->bindParam(':lat2', $_POST['lat2']);
			$atualiza_sql->bindParam(':lng1', $_POST['lng1']);
			$atualiza_sql->bindParam(':lng2', $_POST['lng2']);
			$atualiza_sql->bindParam(':id', $_POST['id']);
			
			if($atualiza_sql->execute()){
				echo 'editado';
				$query_parametros = ( 
				'[ ÁREA | <i>'.mb_strtoupper(retirarEspeciais($_POST['area']),'UTF-8').'</i> ] '.
				'[ MAPA | <i>'.mb_strtoupper(retirarEspeciais($_POST['mapa']),'UTF-8').'</i> ] '.
				'[ LATITUDE 1 | <i>'.mb_strtoupper(retirarEspeciais($_POST['lat1']),'UTF-8').'</i> ] '.
				'[ LONGITUDE 1 | <i>'.mb_strtoupper(retirarEspeciais($_POST['lng1']),'UTF-8').'</i> ] '.
				'[ LATITUDE 2 | <i>'.mb_strtoupper(retirarEspeciais($_POST['lat2']),'UTF-8').'</i> ] '.
				'[ LONGITUDE 2 | <i>'.mb_strtoupper(retirarEspeciais($_POST['lng2'],'UTF-8')).'</i> ]');
				//LOG
				$filename = $root.'/sisccz/paginas/mapas/spring/a4/logs/'.$name.'.txt';
				$file_data = '<b>[EDITAR MATRIZ A4]</b> ['.date('d/m/Y H:i:s').'] [EDITADO POR '.$login.']<br>'.$query_parametros.'<br><hr><br>';
				if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
				file_put_contents($root.'/sisccz/paginas/mapas/spring/a4/logs/'.$name.'.txt', $file_data);
				//LOG LOGIN
				$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
				$file_data = '<b>[EDITAR MATRIZ A4]</b> ['.date('d/m/Y H:i:s').']<br>'.$query_parametros.'<hr><br>';
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