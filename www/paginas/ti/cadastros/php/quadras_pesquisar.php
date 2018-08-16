<?php
header("Content-Type: text/html; charset=utf-8");

function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}
if ($_POST){
	session_start('login');
	$login = $_SESSION['usuario']; 
	$root = $_SERVER['DOCUMENT_ROOT'];
	$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
	include_once($consisccz);
	if (isset($_POST['lista'])){
		try {
			$pesquisa_sql = $db_con->prepare('SELECT * FROM tbl_cadastros_area_setor_sc_quadra ORDER BY tbl_cadastros_area_setor_sc_quadra_area+0, tbl_cadastros_area_setor_sc_quadra_quadra+0');
			if($pesquisa_sql->execute()){
				$data = $pesquisa_sql->rowCount();
		 		if ($data >= 1) {
					while ($linha_querypesquisa=$pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
						if ($linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_label_q_latitude']==NULL){
							$linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_label_q_latitude']=" ";
						}
						if ($linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_label_q_longitude']==NULL){
							$linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_label_q_longitude']=" ";
						}
						$resultado[] = array (
							'id' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_id'],
							'area' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_area'],
							'setor' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_setor'],
							'sc' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_setor_censitario'],
							'quadra' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_quadra'],
							'terreo' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_terreo'],
							'primeiro_andar' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_primeiro_andar'],
							'acima_primeiro_andar_tr' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_acima_primeiro_andar_tr'],
							'acima_primeiro_andar_ntr' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_acima_primeiro_andar_ntr'],
							'comercial_terreo' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_comercial_terreo'],
							'comercial_primeiro_andar' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_comercial_primeiro_andar'],
							'terreno_baldio' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_terreno_baldio'],
							'jardim' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_jardim'],
							'obra' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_obra'],
							'ie' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_i_e'],
							'pe' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_p_e'],
							'qtib' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_quantidade_ib'],
							'total' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_quantidade_imovel_trabalhado'],
							'latitude' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_label_q_latitude'],
							'longitude' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_label_q_longitude']
						);					
					}
					echo (json_encode($resultado));
					//LOG LOGIN
					$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
					$file_data = '<b>[CADASTRO PESQUISAR QUADRAS[LISTA]]</b> ['.date('d/m/Y H:i:s').']<hr><br>';
					if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
					file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', $file_data);
				} else {
					echo 'nenhum resultado encontrado';
				}
		   	} else{
		    	echo 'Erro na busca SQL';
		   	} 
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	} 
	if (isset($_POST['filtro'])){
		try {
			$var_select = 'SELECT * FROM tbl_cadastros_area_setor_sc_quadra '.$_POST['filtro'].' ORDER BY tbl_cadastros_area_setor_sc_quadra_area+0, tbl_cadastros_area_setor_sc_quadra_quadra+0';
			$pesquisa_sql = $db_con->prepare($var_select);
			if($pesquisa_sql->execute()){
				$data = $pesquisa_sql->rowCount();
		 		if ($data >= 1) {
					while ($linha_querypesquisa=$pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
						$resultado[] = array (
							'id' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_id'],
							'area' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_area'],
							'setor' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_setor'],
							'sc' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_setor_censitario'],
							'quadra' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_quadra'],
							'terreo' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_terreo'],
							'primeiro_andar' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_primeiro_andar'],
							'acima_primeiro_andar_tr' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_acima_primeiro_andar_tr'],
							'acima_primeiro_andar_ntr' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_acima_primeiro_andar_ntr'],
							'comercial_terreo' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_comercial_terreo'],
							'comercial_primeiro_andar' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_comercial_primeiro_andar'],
							'terreno_baldio' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_terreno_baldio'],
							'jardim' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_jardim'],
							'obra' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_obra'],
							'ie' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_i_e'],
							'pe' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_p_e'],
							'qtib' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_quantidade_ib'],
							'total' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_quantidade_imovel_trabalhado'],
							'latitude' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_label_q_latitude'],
							'longitude' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_label_q_longitude']);
					}
					echo (json_encode($resultado));
					//LOG LOGIN
					$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
					$file_data = '<b>[CADASTRO PESQUISAR QUADRAS[FILTRO]]</b> ['.date('d/m/Y H:i:s').']<hr><br>';
					if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
					file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', $file_data);
				} else {
					echo 'nenhum resultado encontrado';
				}
		   	} else{
		    	echo 'Erro na busca SQL';
		   	} 
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	} 
	
} else {
	echo 'Parâmetro vazio.';
}
