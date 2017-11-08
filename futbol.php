<?php
error_reporting(0);
$idpartido=114;

require_once "lib/MySQL.lib.php";

$content_bbdd_host      ="hostingmysql299.nominalia.com";
$content_bbdd_user    	="JG26981260_1";
$content_bbdd_pass	="Apu3st3_";
$content_bbdd_bbdd	="apuestesuvida_com_1";

$ConnMySQL=new MySQL();
$ConnMySQL->db_connect($content_bbdd_host,$content_bbdd_user,$content_bbdd_pass,$content_bbdd_bbdd);


$csr_partido=$ConnMySQL->db_selectData("select campo, mapa, date_format(horario,'%d/%m/%Y %H:%i') as horario, competicion, arbitro, asistente1, asistente2, idequipocasa, idequipofuera, video from apuestesuvida_com_1.partidos where idpartido=" . $idpartido);


//var_dump($csr_partido);

$arr_minutero=array();
$csr_equipo_casa=$ConnMySQL->db_selectData("select idequipo, nombre, nombre_completo, escut from apuestesuvida_com_1.equipos where idequipo=" . $csr_partido[0]["idequipocasa"]);
$csr_equipo_fuera=$ConnMySQL->db_selectData("select idequipo, nombre, nombre_completo, escut from apuestesuvida_com_1.equipos where idequipo=" . $csr_partido[0]["idequipofuera"]);

$csr_jugadores_casa=$ConnMySQL->db_selectData("select j.apodo , a.titular, a.minutos, a.posicion, a.idjugador, a.idsuplente, 'N' as ckeck from apuestesuvida_com_1.alineacion a, apuestesuvida_com_1.jugadores j where a.idjugador=j.idjugador and a.idpartido=" . $idpartido . " and a.idequipo=" . $csr_partido[0]["idequipocasa"] . " order by a.posicion");
$csr_jugadores_fuera=$ConnMySQL->db_selectData("select j.apodo , a.titular, a.minutos, a.posicion, a.idjugador, a.idsuplente, 'N' as ckeck from apuestesuvida_com_1.alineacion a, apuestesuvida_com_1.jugadores j where a.idjugador=j.idjugador and a.idpartido=" . $idpartido . " and a.idequipo=" . $csr_partido[0]["idequipofuera"] . " order by a.posicion");

$sizeof_csr_jugadores_casa=sizeof($csr_jugadores_casa);
$equipo_tit_casa="";
$equipo_supl_casa="";
$equipo_tit_fuera="";
$once_inicial_casa="";
$once_inicial_fuera="";
for($i=0;$i<$sizeof_csr_jugadores_casa; $i++){
	if($csr_jugadores_casa[$i]["posicion"]>=1 && $csr_jugadores_casa[$i]["posicion"]<=11){
		$suplente="";
		if($csr_jugadores_casa[$i]["idsuplente"]>0){
			$k=0;
			while($k<$sizeof_csr_jugadores_casa && $csr_jugadores_casa[$i]["idsuplente"]!=$csr_jugadores_casa[$k]["idjugador"]){
				$k++;
			}
			$csr_jugadores_casa[$k]["ckeck"]="S";
			$txt="";
			if(isset($minutero[$csr_jugadores_casa[$i]["minutos"]]["txt"])) $txt=$minutero[$csr_jugadores_casa[$i]["minutos"]]["txt"];
			$minutero[$csr_jugadores_casa[$i]["minutos"]]["txt"]=$txt . '<img alt="' . $csr_equipo_casa[0]["nombre"] . '" border="0" src="http://www.apuestesuvida.com/sants/cambio.jpg" />&nbsp;&nbsp;<img alt="' . $csr_equipo_casa[0]["nombre"] . '" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/' . $csr_equipo_casa[0]["escut"] . '" title="' . $csr_equipo_casa[0]["nombre"] . '" />&nbsp;Entra ' . $csr_jugadores_casa[$k]["apodo"] . ' per ' . $csr_jugadores_casa[$i]["apodo"] . '<br />';
			if($csr_jugadores_casa[$k]["posicion"]>=12 && $csr_jugadores_casa[$k]["ckeck"]=="S" && $csr_jugadores_casa[$k]["idsuplente"]>0){
				$j=0;
				while($j<$sizeof_csr_jugadores_casa && $csr_jugadores_casa[$k]["idsuplente"]!=$csr_jugadores_casa[$j]["idjugador"]){
					$j++;
				}
				$csr_jugadores_casa[$j]["ckeck"]="S";
				$suplente=" (" . $csr_jugadores_casa[$k]["apodo"] . " " . $csr_jugadores_casa[$i]["minutos"] . "')  (" . $csr_jugadores_casa[$j]["apodo"] . " " . (90- $csr_jugadores_casa[$j]["minutos"]) . "')";
				$txt="";
				if(isset($minutero[(90- $csr_jugadores_casa[$j]["minutos"])]["txt"])) $txt=$minutero[(90- $csr_jugadores_casa[$j]["minutos"])]["txt"];
				$minutero[(90- $csr_jugadores_casa[$j]["minutos"])]["txt"]=$txt . '<img alt="' . $csr_equipo_casa[0]["nombre"] . '" border="0" src="http://www.apuestesuvida.com/sants/cambio.jpg" />&nbsp;&nbsp;<img alt="' . $csr_equipo_casa[0]["nombre"] . '" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/' . $csr_equipo_casa[0]["escut"] . '" title="' . $csr_equipo_casa[0]["nombre"] . '" />&nbsp;Entra ' . $csr_jugadores_casa[$j]["apodo"] . ' per ' . $csr_jugadores_casa[$k]["apodo"] . '<br />';
			}else{
				$suplente=" (" . $csr_jugadores_casa[$k]["apodo"] . " " . $csr_jugadores_casa[$i]["minutos"] . "')";
			}

		}
		$equipo_tit_casa.=$csr_jugadores_casa[$i]["apodo"] . $suplente . ", ";
		$once_inicial_casa.=$csr_jugadores_casa[$i]["apodo"] . ", ";
	}
	if($csr_jugadores_casa[$i]["posicion"]>=12 && $csr_jugadores_casa[$i]["ckeck"]=="N") $equipo_supl_casa.=$csr_jugadores_casa[$i]["apodo"] . ", ";

}
$equipo_tit_casa=substr($equipo_tit_casa,0,-2);
$equipo_supl_casa=substr($equipo_supl_casa,0,-2);
$once_inicial_casa=substr($once_inicial_casa,0,-2);

$sizeof_csr_jugadores_fuera=sizeof($csr_jugadores_fuera);
$equipo_tit_fuera="";
$equipo_supl_fuera="";
$equipo_tit_fuera="";
$once_inicial_fuera="";
$once_inicial_fuera="";
for($i=0;$i<$sizeof_csr_jugadores_fuera; $i++){
	if($csr_jugadores_fuera[$i]["posicion"]>=1 && $csr_jugadores_fuera[$i]["posicion"]<=11){
		$suplente="";
		if($csr_jugadores_fuera[$i]["idsuplente"]>0){
			$k=0;
			while($k<$sizeof_csr_jugadores_fuera && $csr_jugadores_fuera[$i]["idsuplente"]!=$csr_jugadores_fuera[$k]["idjugador"]){
				$k++;
			}
			$csr_jugadores_fuera[$k]["ckeck"]="S";
			$txt="";
			if(isset($minutero[$csr_jugadores_fuera[$i]["minutos"]]["txt"])) $txt=$minutero[$csr_jugadores_fuera[$i]["minutos"]]["txt"];
			$minutero[$csr_jugadores_fuera[$i]["minutos"]]["txt"]=$txt . '<img alt="' . $csr_equipo_fuera[0]["nombre"] . '" border="0" src="http://www.apuestesuvida.com/sants/cambio.jpg" />&nbsp;&nbsp;<img alt="' . $csr_equipo_fuera[0]["nombre"] . '" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/' . $csr_equipo_fuera[0]["escut"] . '" title="' . $csr_equipo_fuera[0]["nombre"] . '" />&nbsp;Entra ' . $csr_jugadores_fuera[$k]["apodo"] . ' per ' . $csr_jugadores_fuera[$i]["apodo"] . '<br />';
			if($csr_jugadores_fuera[$k]["posicion"]>=12 && $csr_jugadores_fuera[$k]["ckeck"]=="S" && $csr_jugadores_fuera[$k]["idsuplente"]>0){
				$j=0;
				while($j<$sizeof_csr_jugadores_fuera && $csr_jugadores_fuera[$k]["idsuplente"]!=$csr_jugadores_fuera[$j]["idjugador"]){
					$j++;
				}
				$csr_jugadores_fuera[$j]["ckeck"]="S";
				$suplente=" (" . $csr_jugadores_fuera[$k]["apodo"] . " " . $csr_jugadores_fuera[$i]["minutos"] . "')  (" . $csr_jugadores_fuera[$j]["apodo"] . " " . (90- $csr_jugadores_fuera[$j]["minutos"]) . "')";
				$txt="";
				if(isset($minutero[(90- $csr_jugadores_fuera[$j]["minutos"])]["txt"])) $txt=$minutero[(90- $csr_jugadores_fuera[$j]["minutos"])]["txt"];
				$minutero[(90- $csr_jugadores_fuera[$j]["minutos"])]["txt"]=$txt . '<img alt="' . $csr_equipo_fuera[0]["nombre"] . '" border="0" src="http://www.apuestesuvida.com/sants/cambio.jpg" />&nbsp;&nbsp;<img alt="' . $csr_equipo_fuera[0]["nombre"] . '" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/' . $csr_equipo_fuera[0]["escut"] . '" title="' . $csr_equipo_fuera[0]["nombre"] . '" />&nbsp;Entra ' . $csr_jugadores_fuera[$j]["apodo"] . ' per ' . $csr_jugadores_fuera[$k]["apodo"] . '<br />';
			}else{
				$suplente=" (" . $csr_jugadores_fuera[$k]["apodo"] . " " . $csr_jugadores_fuera[$i]["minutos"] . "')";
			}
		}
		$equipo_tit_fuera.=$csr_jugadores_fuera[$i]["apodo"] . $suplente . ", ";
		$once_inicial_fuera.=$csr_jugadores_fuera[$i]["apodo"] . ", ";
	}
	if($csr_jugadores_fuera[$i]["posicion"]>=12 && $csr_jugadores_fuera[$i]["ckeck"]=="N") $equipo_supl_fuera.=$csr_jugadores_fuera[$i]["apodo"] . ", ";

}
$equipo_tit_fuera=substr($equipo_tit_fuera,0,-2);
$equipo_supl_fuera=substr($equipo_supl_fuera,0,-2);
$once_inicial_fuera=substr($once_inicial_fuera,0,-2);

$csr_gols=$ConnMySQL->db_selectData("select g.idjugador, g.minutos, g.gfgc, j.apodo, e.nombre, e.escut, a.idequipo from apuestesuvida_com_1.goles g, apuestesuvida_com_1.jugadores j, apuestesuvida_com_1.alineacion a, apuestesuvida_com_1.equipos e where g.idjugador=j.idjugador and a.idjugador = j.idjugador and a.idpartido = g.idpartido and a.idequipo = e.idequipo and g.gfgc in ('gf','gc','gp') and g.idpartido=" . $idpartido . " order by g.minutos");

$sizeof_csr_gols=sizeof($csr_gols);
$resul_c=0;
$resul_f=0;
for($i=0; $i<$sizeof_csr_gols; $i++){
	if($csr_gols[$i]["idequipo"]==$csr_equipo_casa[0]["idequipo"]){
		 if($csr_gols[$i]["gfgc"]=="gc") $resul_f++; else $resul_c++;
	}
	if($csr_gols[$i]["idequipo"]==$csr_equipo_fuera[0]["idequipo"]){

		 if($csr_gols[$i]["gfgc"]=="gc") $resul_c++; else $resul_f++;
	}
}
$csr_targetes=$ConnMySQL->db_selectData("select t.idjugador, t.minutos, t.targeta, j.apodo, e.nombre, e.escut, a.idequipo from apuestesuvida_com_1.tarjetas t, apuestesuvida_com_1.jugadores j, apuestesuvida_com_1.alineacion a, apuestesuvida_com_1.equipos e where t.idjugador=j.idjugador and a.idjugador = j.idjugador and a.idpartido = t.idpartido and a.idequipo = e.idequipo and t.idpartido=" . $idpartido . " order by t.minutos");
$arr_mapa=explode(",", $csr_partido[0]["mapa"]);
?>
<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(0, 0, 0); font-weight: bold;">
	El partit
</div>
<div style="width: 100%;">
	<div style="float: left; font-size: 130%; font-weight: bold; width: 100%;">
		<img alt="<?php echo $csr_equipo_casa[0]["nombre"] ?>" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/<?php echo $csr_equipo_casa[0]["escut"] ?>" title="<?php echo $csr_equipo_casa[0]["nombre"] ?>" /> <?php echo $csr_equipo_casa[0]["nombre"] . " " . $resul_c ?><br />
		<img alt="<?php echo $csr_equipo_fuera[0]["nombre"] ?>" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/<?php echo $csr_equipo_fuera[0]["escut"] ?>" title="<?php echo $csr_equipo_fuera[0]["nombre"] ?>" /> <?php echo $csr_equipo_fuera[0]["nombre"] . " " . $resul_f ?>
	</div>
</div>
<div style="height: 5px; width: 100%; clear:both;"></div>
<div style="width: 100%;">
<img border="0" src="http://www.apuestesuvida.com/sants/camps.jpg" />&nbsp;

<a href="http://www.google.es/maps/ms?msid=207276383644634853213.00046e7e96c6081a2b0af&amp;msa=0&amp;spn=0.012885,0.01929&amp;ll=<?php echo $arr_mapa[0]; ?>,<?php echo $arr_mapa[1]; ?>&amp;z=<?php echo $arr_mapa[2]; ?>" target="_blank"><?php echo $csr_partido[0]["campo"]; ?></a><br /><br />
<b>Data:</b> <?php echo $csr_partido[0]["horario"]; ?><br /><br />
<b>Competicio:</b> <?php echo $csr_partido[0]["competicion"]; ?><br />
<br />
<b>Col-legiats:</b> <?php echo $csr_partido[0]["arbitro"] . ", " . $csr_partido[0]["asistente1"] . ", " . $csr_partido[0]["asistente2"]; ?>.<br /><br />
<b>Equips:</b><br />
<img alt="<?php echo $csr_equipo_casa[0]["nombre"] ?>" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/<?php echo $csr_equipo_casa[0]["escut"] ?>" title="<?php echo $csr_equipo_casa[0]["nombre"] ?>" />&nbsp;<b>Titulars:</b> <?php echo $equipo_tit_casa; ?>.<br />
<img alt="<?php echo $csr_equipo_casa[0]["nombre"] ?>" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/<?php echo $csr_equipo_casa[0]["escut"] ?>" title="<?php echo $csr_equipo_casa[0]["nombre"] ?>" />&nbsp;<b>Suplents:</b> <?php echo $equipo_supl_casa; ?>.<br />
<br />
<img alt="<?php echo $csr_equipo_fuera[0]["nombre"] ?>" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/<?php echo $csr_equipo_fuera[0]["escut"] ?>" title="<?php echo $csr_equipo_fuera[0]["nombre"] ?>" />&nbsp;<b>Titulars:</b> <?php echo $equipo_tit_fuera; ?>.<br />
<img alt="<?php echo $csr_equipo_fuera[0]["nombre"] ?>" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/<?php echo $csr_equipo_fuera[0]["escut"] ?>" title="<?php echo $csr_equipo_fuera[0]["nombre"] ?>" />&nbsp;<b>Suplents:</b> <?php echo $equipo_supl_fuera; ?>.<br />
<br />
<b>Gols:</b><br />
<?php
$gol_c=0;
$gol_f=0;
$gol_c_aux=0;
$gol_f_aux=0;
$txt="";
if(isset($minutero[0]["txt"])) $txt=$minutero[0]["txt"];
$minutero[0]["txt"]=$txt . '<img border="0" src="http://www.apuestesuvida.com/sants/chronometer.png" />&nbsp;&nbsp;' . $csr_equipo_casa[0]["nombre"] . ' ' . $gol_c . ' - ' . $csr_equipo_fuera[0]["nombre"] . ' ' . $gol_f . '<br />';
for($i=0; $i<$sizeof_csr_gols; $i++){
	if($csr_gols[$i]["idequipo"]==$csr_equipo_casa[0]["idequipo"]){
		 if($csr_gols[$i]["gfgc"]=="gc") $gol_f++; else $gol_c++;
	}
	if($csr_gols[$i]["idequipo"]==$csr_equipo_fuera[0]["idequipo"]){
		 if($csr_gols[$i]["gfgc"]=="gc") $gol_c++; else $gol_f++;
	}
	$txt="";
	if(isset($minutero[$csr_gols[$i]["minutos"]]["txt"])) $txt=$minutero[$csr_gols[$i]["minutos"]]["txt"];
	$propia_puerta="";
	if($csr_gols[$i]["gfgc"]=="gc") $propia_puerta="(p.p.)";

	$minutero[$csr_gols[$i]["minutos"]]["txt"]=$txt . '<img alt="' . $csr_gols[$i]["nombre"] . '" border="0" src="http://www.apuestesuvida.com/sants/gol.jpg" />&nbsp;&nbsp;<img alt="' . $csr_gols[$i]["nombre"] . '" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/' . $csr_gols[$i]["escut"] . '" title="' . $csr_gols[$i]["nombre"] . '" />&nbsp;' . $csr_gols[$i]["apodo"] . ' ' . $propia_puerta . '<br />';

	if($csr_gols[$i]["minutos"]<=45){
		$gol_c_aux=$gol_c;
		$gol_f_aux=$gol_f;
	}
	$min_ant=$csr_gols[$i]["minutos"];
?>
<img alt="<?php echo $csr_gols[$i]["nombre"] ?>" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/<?php echo $csr_gols[$i]["escut"] ?>" title="<?php echo $csr_gols[$i]["nombre"]?>" />&nbsp;
<?php
$txt_penal="";
if($csr_gols[$i]["gfgc"]=='gp') $txt_penal=" (penalty)";
echo $gol_c . "-" . $gol_f . " " . $csr_gols[$i]["apodo"] . " (" . $csr_gols[$i]["minutos"] . "') " . $txt_penal .  $propia_puerta;
?> <br />
<?php }
$txt="";
if(isset($minutero[45]["txt"])) $txt=$minutero[45]["txt"];
$minutero[45]["txt"]=$txt . '<img border="0" src="http://www.apuestesuvida.com/sants/chronometer.png" />&nbsp;&nbsp;' . $csr_equipo_casa[0]["nombre"] . ' ' . $gol_c_aux . ' - ' . $csr_equipo_fuera[0]["nombre"] . ' ' . $gol_f_aux . '<br />';
$txt="";
if(isset($minutero[90]["txt"])) $txt=$minutero[90]["txt"];
$minutero[90]["txt"]=$txt . '<img border="0" src="http://www.apuestesuvida.com/sants/chronometer.png" />&nbsp;&nbsp;' . $csr_equipo_casa[0]["nombre"] . ' ' . $gol_c . ' - ' . $csr_equipo_fuera[0]["nombre"] . ' ' . $gol_f . '<br />';
?>
<br />
<b>Targetes:</b><br />
<?php
$sizeof_csr_targetes=sizeof($csr_targetes);
for($i=0; $i<$sizeof_csr_targetes; $i++){
	$txt="";
	if(isset($minutero[$csr_targetes[$i]["minutos"]]["txt"])) $txt=$minutero[$csr_targetes[$i]["minutos"]]["txt"];
	$minutero[$csr_targetes[$i]["minutos"]]["txt"]=$txt . '<img border="0" src="http://www.apuestesuvida.com/sants/' . $csr_targetes[$i]["targeta"] . '.jpg" />&nbsp;&nbsp;<img alt="' . $csr_targetes[$i]["nombre"] . '" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/' . $csr_targetes[$i]["escut"] . '" title="' . $csr_targetes[$i]["nombre"] . '" />&nbsp;' . $csr_targetes[$i]["apodo"] .'<br />';

?>
<img alt="<?php echo $csr_targetes[$i]["nombre"] ?>" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/<?php echo $csr_targetes[$i]["escut"] ?>" title="<?php echo $csr_targetes[$i]["nombre"] ?>" />&nbsp;<img border="0" src="http://www.apuestesuvida.com/sants/<?php echo $csr_targetes[$i]["targeta"] ?>.jpg" />&nbsp;<?php echo $csr_targetes[$i]["apodo"] . " (" . $csr_targetes[$i]["minutos"] . "')"; ?> <br/>
<?php } ?>
<div style="height: 15px; width: 100%; clear:both;"></div>
<!-- cronologia-->
<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(0, 0, 0); font-weight: bold;">Cronologia</div>
<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(208, 208, 208); font-weight: bold; height: 5px; width: 100%; clear:both;"></div>
<b>Onze inicial:</b> <img alt="<?php echo $csr_equipo_casa[0]["nombre"] ?>" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/<?php echo $csr_equipo_casa[0]["escut"] ?>" title="<?php echo $csr_equipo_casa[0]["nombre"] ?>" /> <?php echo $once_inicial_casa; ?>.
<div style="height: 5px; width: 100%; clear:both;"></div>
<b>Onze inicial:</b> <img alt="<?php echo $csr_equipo_fuera[0]["nombre"] ?>" border="0" height="12" src="http://www.apuestesuvida.com/sants/mini/<?php echo $csr_equipo_fuera[0]["escut"] ?>" title="<?php echo $csr_equipo_fuera[0]["nombre"] ?>" /> <?php echo $once_inicial_fuera; ?>.
<?php
ksort($minutero);
foreach($minutero as $minut=>$valor){
?>
<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(208, 208, 208); font-weight: bold; height: 5px; width: 100%; clear:both;"></div>
<span style='font:18px arial;float:left; width:10%;'><?php echo $minut; ?></span>
<span style='float:right;width:90%;'>
<?php echo $valor["txt"]; ?>
</span>
<?php
}

?>

<!-- cronologia-->
<!--
<div style="height: 15px; width: 100%; clear:both;"></div>
<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(0, 0, 0); font-weight: bold;">
	Videos (<a href="http://www.youtube.com/user/uesantsfan">mes videos</a>)
</div>
<div style="height: 5px; width: 100%; clear:both;"></div>
<div style="text-align: center; width: 100%;">
	<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $csr_partido[0]["video"] ?>" frameborder="0" allowfullscreen id="video_principal"></iframe>
</div>

<div style="height: 5px; width: 100%; clear:both;"></div>
<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(0, 0, 0); font-weight: bold;">Comentari</div>
<div style="width: 100%;">
Partit força igualat pero que a la fi ha estat el Sants qui ha perdut dos punts, no tant per la falta de davanters, gairabé tots lesionats, sino per la falta de intensitat i contundencia per matar els partits.<br/>
Al inici del partir les oportunitats han estat per l'Igualada, i així després de diverses arribades Eloi engalta un xut gairabe imparable. Llavors la reacció santsenca ha estat inmediata i fruit de una jugada de Crivillés ha arribat una falta a prop de l'area que Gaudioso ha transformat magistralment.<br/>
Semblava que amb l'empat arribariem al mig temps quan després d'un xoc de Cano amb Picolo, el jugador de l'Igualada a començat a increpar a Picolo, al arbitre, i al públic i ha acabat encarat amb ell. Aquesta estupidessa li ha valgut l'expulsió directa. I si no fos prou deixar al equip amb deu, a la següent jugada Chicho ha fet un gran control a una gran pasada i ha marcat el segon gol.<br/>
La segona part el Sants s'ha dedicat a mantenir el resultat e intentar de tant en tant xutar sense sort. Aquesta falta de contundencia ha arribat una contra de l'Igualada que Cura ha tingut que acabar amb penal i vermella directa. Erik ha posat el 2 a 2 al marcador al minut 85.<br/>
Totes les jugades d'atac que no s'habien vist en tot el partit, s'han vist d'aqui al final per totes dues bandes. Per part del Sants tot acababa a peus de Chicho qui en la última jugada ha xutat al pal aixecant la pilota per sobre el porter, el rebot l'ha xutat molt fort picant els ferros laterals i al tornar a entrar la pilota al camp la ficat d'un tercer xut dins de la porteria. Ell tenia clar que no era gol pero el arbitre marxava cap al mig camp, i quasi li provoca un cobriment de cor els jugadors de l'equip iguladí, per sort per ells, el arbitre asistent li ha aclarat tot.<br/>
El Sants no juga malament, ni ha perdut massa punts, pero en general li falta un punt d'intesitat i mes contundencia de cara a gol. Esperarem que tornin tots els lesionats.<br/>
</div>
-->
</div>
<div style="text-align: center;">
<img alt="Rapitenca - Sants" border="0" src="http://www.apuestesuvida.com/sants/2017-2018/sants-rapitenca.jpg" style="height: auto; max-width: 676px; width: 100%;" /></div>
