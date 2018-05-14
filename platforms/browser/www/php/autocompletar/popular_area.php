<?php
if ($_POST){
	session_start('login');
	$root = $_SERVER['DOCUMENT_ROOT'];
	$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
	include_once($consisccz);
	try {
		switch ($_POST['campo']) {
			case 'area' :
				$pesquisa_sql = $db_con->prepare('SELECT DISTINCT tbl_cadastros_area_setor_sc_quadra_area 
				                                  FROM tbl_cadastros_area_setor_sc_quadra 
				                                  ORDER BY tbl_cadastros_area_setor_sc_quadra_area+0');
				if($pesquisa_sql->execute()){
					$data = $pesquisa_sql->rowCount();
					if ($data >= 1) {
						while ($linha_querypesquisa = $pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
							$resultado[] = array (
							'area' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_area']);
						}
						break;
			   		} else {
			    		echo 'Erro na busca SQL';
			   		} 
				} else {
					echo 'Erro na busca SQL';
				} 
			case 'setor' :
				$pesquisa_sql = $db_con->prepare('SELECT DISTINCT tbl_cadastros_area_setor_sc_quadra_setor 
				                                  FROM tbl_cadastros_area_setor_sc_quadra 
				                                  WHERE tbl_cadastros_area_setor_sc_quadra_area=:area
				                                  ORDER BY tbl_cadastros_area_setor_sc_quadra_setor+0');
				$pesquisa_sql->bindParam(':area', mb_strtoupper($_POST['area'])); 
				if($pesquisa_sql->execute()){
					$data = $pesquisa_sql->rowCount();
					if ($data >= 1) {
						while ($linha_querypesquisa = $pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
							$resultado[] = array (
							'setor' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_setor']);
						}
						break;
			   		} else {
			    		echo 'Erro na busca SQL';
			   		} 
				} else {
					echo 'Erro na busca SQL';
				} 
			case 'setor_censitario' :
				$pesquisa_sql = $db_con->prepare('SELECT DISTINCT tbl_cadastros_area_setor_sc_quadra_setor_censitario, SUBSTRING(tbl_cadastros_area_setor_sc_quadra_setor_censitario,13,3) as sc3d 
												  FROM tbl_cadastros_area_setor_sc_quadra WHERE tbl_cadastros_area_setor_sc_quadra_area=:area AND tbl_cadastros_area_setor_sc_quadra_setor=:setor 
												  ORDER BY tbl_cadastros_area_setor_sc_quadra_setor_censitario+0');
				$pesquisa_sql->bindParam(':area', mb_strtoupper($_POST['area'])); 
				$pesquisa_sql->bindParam(':setor', mb_strtoupper($_POST['setor'])); 
				if($pesquisa_sql->execute()){
					$data = $pesquisa_sql->rowCount();
					if ($data >= 1) {
						while ($linha_querypesquisa = $pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
							$resultado[] = array (
							'setor_censitario' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_setor_censitario'],
							'sc' => intval($linha_querypesquisa['sc3d']));
						}
						break;
			   		} else {
			    		echo 'Erro na busca SQL';
			   		} 
				} else {
					echo 'Erro na busca SQL';
				}
			case 'quadra' :
				$pesquisa_sql = $db_con->prepare('SELECT DISTINCT tbl_cadastros_area_setor_sc_quadra_quadra 
												  FROM tbl_cadastros_area_setor_sc_quadra 
												  WHERE tbl_cadastros_area_setor_sc_quadra_area=:area AND tbl_cadastros_area_setor_sc_quadra_setor=:setor 
												  ORDER BY tbl_cadastros_area_setor_sc_quadra_quadra+0');
				$pesquisa_sql->bindParam(':area', mb_strtoupper($_POST['area'])); 
				$pesquisa_sql->bindParam(':setor', mb_strtoupper($_POST['setor'])); 
				if($pesquisa_sql->execute()){
					$data = $pesquisa_sql->rowCount();
					if ($data >= 1) {
						while ($linha_querypesquisa = $pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
							$resultado[] = array (
							'quadra' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_quadra']);
						}
						break;
			   		} else {
			    		echo 'Erro na busca SQL';
			   		} 
				} else {
					echo 'Erro na busca SQL';
				}
			case 'quadra_setor_censitario' :
				$lsc = strlen ($_POST['setor_censitario']);
				$pesquisa_sql = $db_con->prepare('SELECT DISTINCT tbl_cadastros_area_setor_sc_quadra_quadra 
												  FROM tbl_cadastros_area_setor_sc_quadra 
												  WHERE tbl_cadastros_area_setor_sc_quadra_area=:area AND tbl_cadastros_area_setor_sc_quadra_setor=:setor AND RIGHT(tbl_cadastros_area_setor_sc_quadra_setor_censitario,:lsc)=:setor_censitario
												  ORDER BY tbl_cadastros_area_setor_sc_quadra_quadra+0');
				$pesquisa_sql->bindParam(':area', mb_strtoupper($_POST['area'])); 
				$pesquisa_sql->bindParam(':setor', mb_strtoupper($_POST['setor'])); 
				$pesquisa_sql->bindParam(':lsc', $lsc); 
				$pesquisa_sql->bindParam(':setor_censitario', mb_strtoupper($_POST['setor_censitario'])); 
				if($pesquisa_sql->execute()){
					$data = $pesquisa_sql->rowCount();
					if ($data >= 1) {
						while ($linha_querypesquisa = $pesquisa_sql->fetch(PDO::FETCH_ASSOC)){
							$resultado[] = array (
							'quadra_setor_censitario' => $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_quadra']);
						}
						break;
			   		} else {
			    		echo 'Erro na busca SQL';
			   		} 
				} else {
					echo 'Erro na busca SQL';
				}
				
		}
		echo (json_encode($resultado));
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
} else {
	echo 'Parâmetro vazio.';
}
?>