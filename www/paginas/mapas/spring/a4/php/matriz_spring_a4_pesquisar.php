<?php
header("Content-Type: text/html; charset=utf-8");


function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}
session_start('login');
$login = $_SESSION['usuario'];
$root = $_SERVER['DOCUMENT_ROOT'];
$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
include_once($consisccz);
if ($_POST){
	try {
		if (isset($_POST['area'])){
			$pesquisa_sql = $db_con->prepare('SELECT * FROM tbl_mapas_matriz_spring_a4 WHERE tbl_mapas_matriz_spring_area=:area ORDER BY tbl_mapas_matriz_spring_mapa');
			$pesquisa_sql->bindParam(':area', $_POST['area']);
			if($pesquisa_sql->execute()){
				while ($linha_querypesquisa=$pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
					$resultado[] = array (
						'id' => $linha_querypesquisa['tbl_mapas_matriz_spring_id'],
						'nome' => $linha_querypesquisa['tbl_mapas_matriz_spring_nome'],
						'mime' => $linha_querypesquisa['tbl_mapas_matriz_spring_mime'],
						'size' => $linha_querypesquisa['tbl_mapas_matriz_spring_size'],
						'data' => implode('/',array_reverse(explode('-',$linha_querypesquisa['tbl_mapas_matriz_spring_data']))),
						'usuario' => $linha_querypesquisa['tbl_mapas_matriz_spring_usuario'],
						'area' => $linha_querypesquisa['tbl_mapas_matriz_spring_area'],
						'mapa' => $linha_querypesquisa['tbl_mapas_matriz_spring_mapa'],
						'lat1' => $linha_querypesquisa['tbl_mapas_matriz_spring_lat1'],
						'lat2' => $linha_querypesquisa['tbl_mapas_matriz_spring_lat2'],
						'lng1' => $linha_querypesquisa['tbl_mapas_matriz_spring_lng1'],
						'lng2' => $linha_querypesquisa['tbl_mapas_matriz_spring_lng2'],
						'clat' => $linha_querypesquisa['tbl_mapas_matriz_spring_lat_centroide'],
						'clng' => $linha_querypesquisa['tbl_mapas_matriz_spring_lng_centroide']
					);
				}
				echo (json_encode($resultado));
				//LOG LOGIN
				$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
				$file_data = '<b>[PESQUISAR MATRIZ A4 POR AREA]</b> ['.date('d/m/Y H:i:s').']<hr><br>';
				if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
				file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', $file_data);
			}
		}
		if (isset($_POST['asm'])){
			$pesquisa_sql2 = $db_con->prepare('SELECT * FROM tbl_mapas_matriz_spring_a4 WHERE tbl_mapas_matriz_spring_id=:id ORDER BY tbl_mapas_matriz_spring_mapa');
			$pesquisa_sql2->bindParam(':id', $_POST['ID']);
			if($pesquisa_sql2->execute()){
				$linha_querypesquisa=$pesquisa_sql2->fetch(PDO::FETCH_ASSOC);
				$size = $linha_querypesquisa['tbl_mapas_matriz_spring_size'];
				$base = log($size) / log(1024);
				$suffix = array("", "k", "M", "G", "T")[floor($base)];
				$size2 = intval(pow(1024, $base - floor($base))) . $suffix;				
				$resultado[] = array (
					'id' => $linha_querypesquisa['tbl_mapas_matriz_spring_id'],
					'nome' => $linha_querypesquisa['tbl_mapas_matriz_spring_nome'],
					'mime' => $linha_querypesquisa['tbl_mapas_matriz_spring_mime'],
					'size' => $size2,
					'data' => implode('/',array_reverse(explode('-',$linha_querypesquisa['tbl_mapas_matriz_spring_data']))),
					'usuario' => $linha_querypesquisa['tbl_mapas_matriz_spring_usuario'],
					'area' => $linha_querypesquisa['tbl_mapas_matriz_spring_area'],
					'mapa' => $linha_querypesquisa['tbl_mapas_matriz_spring_mapa'],
					'lat1' => $linha_querypesquisa['tbl_mapas_matriz_spring_lat1'],
					'lat2' => $linha_querypesquisa['tbl_mapas_matriz_spring_lat2'],
					'lng1' => $linha_querypesquisa['tbl_mapas_matriz_spring_lng1'],
					'lng2' => $linha_querypesquisa['tbl_mapas_matriz_spring_lng2'],
					'clat' => $linha_querypesquisa['tbl_mapas_matriz_spring_lat_centroide'],
					'clng' => $linha_querypesquisa['tbl_mapas_matriz_spring_lng_centroide']
				);
				echo (json_encode($resultado));
				//LOG
				$filename = $root.'/sisccz/paginas/mapas/spring/a4/logs/'.$linha_querypesquisa['tbl_mapas_matriz_spring_nome'].'.txt';
				$file_data = '<b>[PESQUISAR MATRIZ A4]</b> ['.date('d/m/Y H:i:s').'] [PESQUISADO POR '.$login.']<hr><br>';
				if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
				file_put_contents($root.'/sisccz/paginas/mapas/spring/a4/logs/'.$linha_querypesquisa['tbl_mapas_matriz_spring_nome'].'.txt', $file_data);
				//LOG LOGIN
				$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
				$file_data = '<b>[PESQUISAR MATRIZ A4]</b> ['.date('d/m/Y H:i:s').']<br>['.$linha_querypesquisa['tbl_mapas_matriz_spring_nome'].']<hr><br>';
				if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
				file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', $file_data);
			}
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
} else {
	echo 'Parâmetro vazio.';
}

?>
