<?php
header('Content-Type: text/html; charset=utf-8');

function retirarEspeciais($texto){
  $caracteres_especiais = array ("'",'"');
  return str_replace($caracteres_especiais,"",$texto); 
}
session_start('login');
$login = $_SESSION['usuario']; 
$root = $_SERVER['DOCUMENT_ROOT'];
$consisccz = $root.'/sisccz/php/conexao/consisccz.php';
include_once($consisccz);
$atualizado=0;
$inserido=0;
$AREAS=0;
$SETORES=0;
$CENSITARIOS=0;
$QUADRAS=0;
$TERREO=0;
$PRIMEIROANDAR=0;
$ACIMAPANDARTR=0;
$ACIMAPANDARNTR=0;
$COMERCIAL=0;
$COMERCIALPANDAR=0;
$TB=0;
$JARDIM=0;
$OBRA=0;
$IE=0;
$PE=0;
$TOTAL=0;
if (isset($_FILES['arquivo'])) {
	if (is_uploaded_file($_FILES['arquivo']['tmp_name'])) {
		$handle = fopen($_FILES['arquivo']['tmp_name'], "r");
		$linha1 = fgetcsv($handle, 0, ";");
		if ($linha1[0]=='MUNICIPIO'){
			while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
				if ($data[0] != 'MUNICIPIO'){
					$area= $data[1];
					$sc=$data[2];
					$quadra=$data[4];
					$terreo=$data[8];
					$ate_1=$data[9];
					$acima_1_trab=$data[10];
					$acima_1_nao=$data[11];
					$com_terreo=$data[12];
					$com_andar_1=$data[13];
					$jardim=$data[14];
					$baldio=$data[15];
					$obra=$data[16];
					$qt_ib=$terreo+$ate_1+$acima_1_trab+$com_terreo+$com_andar_1+$obra;
					$total=$terreo+$ate_1+$acima_1_trab+$acima_1_nao+$com_terreo+$com_andar_1+$baldio+$jardim+$obra;
					try {
						$pesquisa_sql = $db_con->prepare('SELECT tbl_cadastros_area_setor_sc_quadra_id
														  FROM tbl_cadastros_area_setor_sc_quadra 
														  WHERE tbl_cadastros_area_setor_sc_quadra_area=:area AND 
														  		tbl_cadastros_area_setor_sc_quadra_setor_censitario=:sc AND 
														  		tbl_cadastros_area_setor_sc_quadra_quadra=:quadra');
						$pesquisa_sql->bindParam(':area', retirarEspeciais($area));
						$pesquisa_sql->bindParam(':sc', retirarEspeciais($sc));
						$pesquisa_sql->bindParam(':quadra', retirarEspeciais($quadra));
						if($pesquisa_sql->execute()){
							$data_pesquisa = $pesquisa_sql->rowCount();
			 				if ($data_pesquisa >= 1) {
			 					$linha_querypesquisa=$pesquisa_sql->fetch(PDO::FETCH_ASSOC);
			 					$id = $linha_querypesquisa['tbl_cadastros_area_setor_sc_quadra_id'];
			 					$atualiza_sql = $db_con->prepare('UPDATE
			 														tbl_cadastros_area_setor_sc_quadra
			 													  SET
			 														tbl_cadastros_area_setor_sc_quadra_area=:area, tbl_cadastros_area_setor_sc_quadra_setor_censitario=:sc,
			 														tbl_cadastros_area_setor_sc_quadra_quadra=:quadra, tbl_cadastros_area_setor_sc_quadra_terreo=:terreo, tbl_cadastros_area_setor_sc_quadra_primeiro_andar=:ate_1,
			 														tbl_cadastros_area_setor_sc_quadra_acima_primeiro_andar_tr=:acima_1_trab, tbl_cadastros_area_setor_sc_quadra_acima_primeiro_andar_ntr=:acima_1_nao,
			 														tbl_cadastros_area_setor_sc_quadra_comercial_terreo=:com_terreo, tbl_cadastros_area_setor_sc_quadra_comercial_primeiro_andar=:com_andar_1,
			 														tbl_cadastros_area_setor_sc_quadra_terreno_baldio=:baldio, tbl_cadastros_area_setor_sc_quadra_jardim=:jardim,
			 														tbl_cadastros_area_setor_sc_quadra_obra=:obra, tbl_cadastros_area_setor_sc_quadra_quantidade_ib=:qt_ib,
			 														tbl_cadastros_area_setor_sc_quadra_quantidade_imovel_trabalhado=:total
 																  WHERE
			 														tbl_cadastros_area_setor_sc_quadra_id=:id');
								$atualiza_sql->bindParam(':id', retirarEspeciais($id));
								$atualiza_sql->bindParam(':area', retirarEspeciais($area));
								$atualiza_sql->bindParam(':sc', retirarEspeciais($sc));
								$atualiza_sql->bindParam(':quadra', retirarEspeciais($quadra));
								$atualiza_sql->bindParam(':terreo', retirarEspeciais($terreo));
								$atualiza_sql->bindParam(':ate_1', retirarEspeciais($ate_1));
								$atualiza_sql->bindParam(':acima_1_trab', retirarEspeciais($acima_1_trab));
								$atualiza_sql->bindParam(':acima_1_nao', retirarEspeciais($acima_1_nao));
								$atualiza_sql->bindParam(':com_terreo', retirarEspeciais($com_terreo));
								$atualiza_sql->bindParam(':com_andar_1', retirarEspeciais($com_andar_1));
								$atualiza_sql->bindParam(':baldio', retirarEspeciais($baldio));
								$atualiza_sql->bindParam(':jardim', retirarEspeciais($jardim));
								$atualiza_sql->bindParam(':obra', retirarEspeciais($obra));
								$atualiza_sql->bindParam(':qt_ib', retirarEspeciais($qt_ib));
								$atualiza_sql->bindParam(':total', retirarEspeciais($total));
								$atualiza_sql->execute();
			 					$atualizado++;
							} else {
			 					$insere_sql = $db_con->prepare('INSERT INTO 
			 														tbl_cadastros_area_setor_sc_quadra
			 															(tbl_cadastros_area_setor_sc_quadra_area, 
			 															 tbl_cadastros_area_setor_sc_quadra_setor_censitario,
			 															 tbl_cadastros_area_setor_sc_quadra_quadra, 
			 															 tbl_cadastros_area_setor_sc_quadra_terreo, 
			 															 tbl_cadastros_area_setor_sc_quadra_primeiro_andar,
			 															 tbl_cadastros_area_setor_sc_quadra_acima_primeiro_andar_tr, 
			 															 tbl_cadastros_area_setor_sc_quadra_acima_primeiro_andar_ntr,
			 															 tbl_cadastros_area_setor_sc_quadra_comercial_terreo, 
			 															 tbl_cadastros_area_setor_sc_quadra_comercial_primeiro_andar,
			 															 tbl_cadastros_area_setor_sc_quadra_terreno_baldio, 
			 															 tbl_cadastros_area_setor_sc_quadra_jardim,
			 															 tbl_cadastros_area_setor_sc_quadra_obra, 
			 															 tbl_cadastros_area_setor_sc_quadra_quantidade_ib,
			 															 tbl_cadastros_area_setor_sc_quadra_quantidade_imovel_trabalhado)
			 														VALUES
			 															(:area, :sc, :quadra, :terreo, :ate_1, :acima_1_trab, :acima_1_nao, :com_terreo, :com_andar_1, :baldio, :jardim, :obra, :qt_ib, :total)');
								$insere_sql->bindParam(':area', retirarEspeciais($area));
								$insere_sql->bindParam(':sc', retirarEspeciais($sc));
								$insere_sql->bindParam(':quadra', retirarEspeciais($quadra));
								$insere_sql->bindParam(':terreo', retirarEspeciais($terreo));
								$insere_sql->bindParam(':ate_1', retirarEspeciais($ate_1));
								$insere_sql->bindParam(':acima_1_trab', retirarEspeciais($acima_1_trab));
								$insere_sql->bindParam(':acima_1_nao', retirarEspeciais($acima_1_nao));
								$insere_sql->bindParam(':com_terreo', retirarEspeciais($com_terreo));
								$insere_sql->bindParam(':com_andar_1', retirarEspeciais($com_andar_1));
								$insere_sql->bindParam(':baldio', retirarEspeciais($baldio));
								$insere_sql->bindParam(':jardim', retirarEspeciais($jardim));
								$insere_sql->bindParam(':obra', retirarEspeciais($obra));
								$insere_sql->bindParam(':qt_ib', retirarEspeciais($qt_ib));
								$insere_sql->bindParam(':total', retirarEspeciais($total));
								$insere_sql->execute();
								$inserido++;
							}
					   	} else{
					    	echo 'Erro na busca SQL';
					   	} 
					}
					catch(PDOException $e){
						echo $e->getMessage();
					}
				}
			}
			if ($atualizado > 0 || $inserido > 0){
						$pesquisa_sql2 = $db_con->prepare('
									SELECT 
									COUNT(DISTINCT tbl_cadastros_area_setor_sc_quadra_area) as AREAS,
									COUNT(DISTINCT tbl_cadastros_area_setor_sc_quadra_setor_censitario) as CENSITARIOS,
									COUNT(tbl_cadastros_area_setor_sc_quadra_quadra) as QUADRAS,
									SUM(tbl_cadastros_area_setor_sc_quadra_terreo) AS TERREO,
									SUM(tbl_cadastros_area_setor_sc_quadra_primeiro_andar) AS PRIMEIROANDAR,
									SUM(tbl_cadastros_area_setor_sc_quadra_acima_primeiro_andar_tr) AS ACIMAPANDARTR,
									SUM(tbl_cadastros_area_setor_sc_quadra_acima_primeiro_andar_ntr) AS ACIMAPANDARNTR,
									SUM(tbl_cadastros_area_setor_sc_quadra_comercial_terreo) AS COMERCIAL,
									SUM(tbl_cadastros_area_setor_sc_quadra_comercial_primeiro_andar) AS COMERCIALPANDAR,
									SUM(tbl_cadastros_area_setor_sc_quadra_terreno_baldio) AS TB,
									SUM(tbl_cadastros_area_setor_sc_quadra_jardim) AS JARDIM,
									SUM(tbl_cadastros_area_setor_sc_quadra_obra) AS OBRA,
									SUM(tbl_cadastros_area_setor_sc_quadra_quantidade_ib) AS IB,
									SUM(tbl_cadastros_area_setor_sc_quadra_quantidade_imovel_trabalhado) AS TOTAL
									FROM tbl_cadastros_area_setor_sc_quadra;			
								');
								if($pesquisa_sql2->execute()){
									$data_pesquisa = $pesquisa_sql2->rowCount();
							 		if ($data_pesquisa >= 1) {
										while ($linha_querypesquisa2=$pesquisa_sql2->fetch(PDO::FETCH_ASSOC)){
											$resultado[] = array (
												$AREAS=$AREAS+$linha_querypesquisa2['AREAS'],
												$CENSITARIOS=$CENSITARIOS+$linha_querypesquisa2['CENSITARIOS'],
												$QUADRAS=$QUADRAS+$linha_querypesquisa2['QUADRAS'],
												$TERREO=$TERREO+$linha_querypesquisa2['TERREO'],
												$PRIMEIROANDAR=$PRIMEIROANDAR+$linha_querypesquisa2['PRIMEIROANDAR'],
												$ACIMAPANDARTR=$ACIMAPANDARTR+$linha_querypesquisa2['ACIMAPANDARTR'],
												$ACIMAPANDARNTR=$ACIMAPANDARNTR+$linha_querypesquisa2['ACIMAPANDARNTR'],
												$COMERCIAL=$COMERCIAL+$linha_querypesquisa2['COMERCIAL'],
												$COMERCIALPANDAR=$COMERCIALPANDAR+$linha_querypesquisa2['COMERCIALPANDAR'],
												$TB=$TB+$linha_querypesquisa2['TB'],
												$JARDIM=$JARDIM+$linha_querypesquisa2['JARDIM'],
												$OBRA=$OBRA+$linha_querypesquisa2['OBRA'],
												$IB=$IB+$linha_querypesquisa2['IB'],
												$TOTAL=$TOTAL+$linha_querypesquisa2['TOTAL']);
										}
									}
								}
								
			 					$insere_sql2 = $db_con->prepare('INSERT INTO 
			 														tbl_cadastros_area_setor_sc_quadra_historico
			 															(tbl_cadastros_area_setor_sc_quadra_data, tbl_cadastros_area_setor_sc_quadra_area, tbl_cadastros_area_setor_sc_quadra_setor_censitario,
			 															 tbl_cadastros_area_setor_sc_quadra_quadra, tbl_cadastros_area_setor_sc_quadra_terreo, tbl_cadastros_area_setor_sc_quadra_primeiro_andar,
			 															 tbl_cadastros_area_setor_sc_quadra_acima_primeiro_andar_tr, tbl_cadastros_area_setor_sc_quadra_acima_primeiro_andar_ntr,
			 															 tbl_cadastros_area_setor_sc_quadra_comercial_terreo, tbl_cadastros_area_setor_sc_quadra_comercial_primeiro_andar,
			 															 tbl_cadastros_area_setor_sc_quadra_terreno_baldio, tbl_cadastros_area_setor_sc_quadra_jardim,
			 															 tbl_cadastros_area_setor_sc_quadra_obra, tbl_cadastros_area_setor_sc_quadra_quantidade_ib, 
			 															 tbl_cadastros_area_setor_sc_quadra_quantidade_imovel_trabalhado)
			 														VALUES
			 															(:data, :area, :sc, :quadra, :terreo, :ate_1, :acima_1_trab, :acima_1_nao, :com_terreo, :com_andar_1, :baldio, :jardim, :obra, :qt_ib, :total)');
								$insere_sql2->bindParam(':data', date('Y-m-d'));
								$insere_sql2->bindParam(':area', retirarEspeciais($AREAS));
								$insere_sql2->bindParam(':sc', retirarEspeciais($CENSITARIOS));
								$insere_sql2->bindParam(':quadra', retirarEspeciais($QUADRAS));
								$insere_sql2->bindParam(':terreo', retirarEspeciais($TERREO));
								$insere_sql2->bindParam(':ate_1', retirarEspeciais($PRIMEIROANDAR));
								$insere_sql2->bindParam(':acima_1_trab', retirarEspeciais($ACIMAPANDARTR));
								$insere_sql2->bindParam(':acima_1_nao', retirarEspeciais($ACIMAPANDARNTR));
								$insere_sql2->bindParam(':com_terreo', retirarEspeciais($COMERCIAL));
								$insere_sql2->bindParam(':com_andar_1', retirarEspeciais($COMERCIALPANDAR));
								$insere_sql2->bindParam(':baldio', retirarEspeciais($TB));
								$insere_sql2->bindParam(':jardim', retirarEspeciais($JARDIM));
								$insere_sql2->bindParam(':obra', retirarEspeciais($OBRA));
								$insere_sql2->bindParam(':qt_ib', retirarEspeciais($IB));
								$insere_sql2->bindParam(':total', retirarEspeciais($TOTAL));
								$insere_sql2->execute();
			
				echo 'Cadastro atualizado com sucesso.'."\n".'Quadras atualizadas: '.$atualizado.'.'."\n".'Quadras inseridas: '.$inserido.'.';
				//LOG LOGIN
				$filename = $root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt';
				$file_data = '<b>[CADASTRO ATUALIZAR QUADRAS]</b> ['.date('d/m/Y H:i:s').']<br>ATUALIZADAS: '.$atualizado.' INSERIDAS: '.$inserido.'<hr><br>';
				if (file_exists($filename)){ $file_data .= file_get_contents($filename);}
				file_put_contents($root.'/sisccz/paginas/ti/usuarios/logs/'.$login.'.txt', $file_data);
			} else {
				echo 'Arquivo inconsistente [1].';
			}
		} else {
			echo 'Arquivo inconsistente [2].';
		}
	} else {
		echo 'Arquivo inconsistente [3].';
	}
} else {
	echo 'Pârametro vazio.';
}
?>