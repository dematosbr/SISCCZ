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
			$pesquisa_sql = $db_con->prepare('SELECT 
												tbl_mapas_matriz_spring_id,
												tbl_mapas_matriz_spring_nome,
												tbl_mapas_matriz_spring_mime,
												tbl_mapas_matriz_spring_size,
												tbl_mapas_matriz_spring_data,
												tbl_mapas_matriz_spring_usuario,
												tbl_mapas_matriz_spring_area,
												tbl_mapas_matriz_spring_mapa,
												tbl_mapas_matriz_spring_lat1,
												tbl_mapas_matriz_spring_lat2,
												tbl_mapas_matriz_spring_lng1,
												tbl_mapas_matriz_spring_lng2,
												tbl_mapas_matriz_spring_lat_centroide,
												tbl_mapas_matriz_spring_lng_centroide
											  FROM tbl_mapas_matriz_spring_a4 where tbl_mapas_matriz_spring_area = :area ');
			$pesquisa_sql->bindParam(':area', $_POST['area']);
			if($pesquisa_sql->execute()){
				$data = $pesquisa_sql->rowCount();
				if ($data >= 1){
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
							'clng' => $linha_querypesquisa['tbl_mapas_matriz_spring_lng_centroide'],
						);
					}
					echo (json_encode($resultado));
					//LOG LOGIN
					$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
					$file_data = '<b>[PESQUISAR MATRIZ A4 [TODAS]]</b> ['.date('d/m/Y H:i:s').']<hr><br>';
					if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
					file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', $file_data);
				} else {
					echo 'nenhum resultasdo encotrado';
				}
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
