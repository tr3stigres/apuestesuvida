<?php
error_reporting(0);
$idpartido=114;
$partidos_a_controlar=5;
$idpartido_inicio_liga=105;

require_once "lib/MySQL.lib.php";

$content_bbdd_host      ="hostingmysql299.nominalia.com";
$content_bbdd_user    	="JG26981260_1";
$content_bbdd_pass	="Apu3st3_";
$content_bbdd_bbdd	="apuestesuvida_com_1";

$ConnMySQL=new MySQL();
$ConnMySQL->db_connect($content_bbdd_host,$content_bbdd_user,$content_bbdd_pass,$content_bbdd_bbdd);

$csr_partido=$ConnMySQL->db_selectData("select campo, direccion, mapa, date_format(horario,'%d/%m/%Y %H:%i') as horario, competicion, arbitro, asistente1, asistente2, idequipocasa, idequipofuera, video from apuestesuvida_com_1.partidos where idpartido=" . $idpartido);
$csr_equipo_casa=$ConnMySQL->db_selectData("select idequipo, nombre, nombre_completo, fundacio, web, escut, samarreta, pantalo, mitgetes from apuestesuvida_com_1.equipos where idequipo=" . $csr_partido[0]["idequipocasa"]);
$csr_equipo_fuera=$ConnMySQL->db_selectData("select idequipo, nombre, nombre_completo, fundacio, web, escut, samarreta, pantalo, mitgetes from apuestesuvida_com_1.equipos where idequipo=" . $csr_partido[0]["idequipofuera"]);

$csr_jugadors=$ConnMySQL->db_selectData("select j.idjugador, j.apodo, a.titular, a.minutos from jugadores j, equipos e, alineacion a where a.idequipo = e.idequipo and a.idjugador = j.idjugador and e.idequipo=1 and a.posicion not in (1,13) and a.idpartido>=" . ($idpartido-$partidos_a_controlar) . " and a.idpartido<=" . ($idpartido-1));
$arr_puntos=array();
$arr_estadisticas=array();
$sizeof_csr_jugadors=sizeof($csr_jugadors);
for($i=0;$i<$sizeof_csr_jugadors;$i++){
	if(!isset($arr_puntos[$csr_jugadors[$i]["idjugador"]]["apodo"]))$arr_puntos[$csr_jugadors[$i]["idjugador"]]["apodo"]=$csr_jugadors[$i]["apodo"];
	if(!isset($arr_puntos[$csr_jugadors[$i]["idjugador"]]["puntos"])) $arr_puntos[$csr_jugadors[$i]["idjugador"]]["puntos"]=0;

	if($csr_jugadors[$i]["titular"]=="S") $arr_puntos[$csr_jugadors[$i]["idjugador"]]["puntos"]+=2;
	if($csr_jugadors[$i]["titular"]=="N") $arr_puntos[$csr_jugadors[$i]["idjugador"]]["puntos"]+=1;

	if($csr_jugadors[$i]["minutos"]>=1 and $csr_jugadors[$i]["minutos"]<=45) $arr_puntos[$csr_jugadors[$i]["idjugador"]]["puntos"]+=1;
	if($csr_jugadors[$i]["minutos"]>45) $arr_puntos[$csr_jugadors[$i]["idjugador"]]["puntos"]+=2;
}

$csr_jugadors=$ConnMySQL->db_selectData("select j.idjugador, j.apodo, a.titular, a.minutos from jugadores j, equipos e, alineacion a where a.idequipo = e.idequipo and a.idjugador = j.idjugador and e.idequipo=1 and a.posicion not in (1,13) and a.idpartido>=" . $idpartido_inicio_liga);
$sizeof_csr_jugadors=sizeof($csr_jugadors);
for($i=0;$i<$sizeof_csr_jugadors;$i++){
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["convocado"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["convocado"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["titular"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["titular"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["suplente"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["suplente"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["minutos"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["minutos"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["ta"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["ta"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["tr"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["tr"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["gf"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["gf"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["gc"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["gc"]=0;
	if($csr_jugadors[$i]["titular"]=="S"){
		$arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["convocado"]++;
		$arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["titular"]++;
	}
	if($csr_jugadors[$i]["titular"]=="N"){
		$arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["convocado"]++;
		$arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["suplente"]++;
	}

	$arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["apodo"]=$csr_jugadors[$i]["apodo"];
	$arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["minutos"]+=$csr_jugadors[$i]["minutos"];
}



$csr_targes=$ConnMySQL->db_selectData("select j.idjugador, t.targeta from jugadores j, equipos e, alineacion a, tarjetas t where a.idequipo = e.idequipo and a.idjugador = j.idjugador and t.idpartido = a.idpartido and t.idjugador = a.idjugador and e.idequipo=1 and a.posicion not in (1,13) and a.idpartido>=" . ($idpartido-$partidos_a_controlar) . " and a.idpartido<=" . ($idpartido-1));
$sizeof_csr_targes=sizeof($csr_targes);
for($i=0;$i<$sizeof_csr_targes;$i++){
	if($csr_targes[$i]["targeta"]=="ta1" || $csr_targes[$i]["targeta"]=="ta2") $arr_puntos[$csr_targes[$i]["idjugador"]]["puntos"]-=1;
	if($csr_targes[$i]["targeta"]=="tr") $arr_puntos[$csr_targes[$i]["idjugador"]]["puntos"]-=3;
}

$csr_targes=$ConnMySQL->db_selectData("select j.idjugador, t.targeta from jugadores j, equipos e, alineacion a, tarjetas t where a.idequipo = e.idequipo and a.idjugador = j.idjugador and t.idpartido = a.idpartido and t.idjugador = a.idjugador and e.idequipo=1 and a.posicion not in (1,13) and a.idpartido>=" . $idpartido_inicio_liga);
$sizeof_csr_targes=sizeof($csr_targes);
for($i=0;$i<$sizeof_csr_targes;$i++){
	if($csr_targes[$i]["targeta"]=="ta1" || $csr_targes[$i]["targeta"]=="ta2"){
		if($csr_targes[$i]["targeta"]=="ta1"){
			$arr_estadisticas[$csr_targes[$i]["idjugador"]]["ta"]++;
		}elseif ($csr_targes[$i]["targeta"]=="ta2"){
			$arr_estadisticas[$csr_targes[$i]["idjugador"]]["ta"]=$arr_estadisticas[$csr_targes[$i]["idjugador"]]["ta"]+2;
		}
	}
	if($csr_targes[$i]["targeta"]=="tr") $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["tr"]++;

}


$csr_goles=$ConnMySQL->db_selectData("select j.idjugador, g.gfgc, count(*) as goles from jugadores j, equipos e, alineacion a, goles g where a.idequipo = e.idequipo and a.idjugador = j.idjugador and g.idpartido = a.idpartido and g.idjugador = a.idjugador and e.idequipo=1 and a.posicion not in (1,13) and a.idpartido>=" . ($idpartido-$partidos_a_controlar) . " and a.idpartido<=" . ($idpartido-1) . " group by j.idjugador, g.gfgc, g.idpartido");
$sizeof_csr_goles=sizeof($csr_goles);
for($i=0;$i<$sizeof_csr_goles;$i++){
	if($csr_goles[$i]["gfgc"]=="gf" || $csr_goles[$i]["gfgc"]=="gp"){
		if($csr_goles[$i]["goles"]>=3){
			$arr_puntos[$csr_goles[$i]["idjugador"]]["puntos"]+=3;
		}else{
			$arr_puntos[$csr_goles[$i]["idjugador"]]["puntos"]+=$csr_goles[$i]["goles"];
		}
	}
	if($csr_goles[$i]["gfgc"]=="gc") $arr_puntos[$csr_goles[$i]["idjugador"]]["puntos"]+=($csr_goles[$i]["goles"]*-1);
}

$csr_goles=$ConnMySQL->db_selectData("select j.idjugador, g.gfgc, count(*) as goles from jugadores j, equipos e, alineacion a, goles g where a.idequipo = e.idequipo and a.idjugador = j.idjugador and g.idpartido = a.idpartido and g.idjugador = a.idjugador and e.idequipo=1 and a.posicion not in (1,13) and a.idpartido>=" . $idpartido_inicio_liga . " group by j.idjugador, g.gfgc, g.idpartido");
$sizeof_csr_goles=sizeof($csr_goles);
for($i=0;$i<$sizeof_csr_goles;$i++){
	if($csr_goles[$i]["gfgc"]=="gf" || $csr_goles[$i]["gfgc"]=="gp"){
		$arr_estadisticas[$csr_goles[$i]["idjugador"]]["gf"]=$arr_estadisticas[$csr_goles[$i]["idjugador"]]["gf"]+$csr_goles[$i]["goles"];
	}elseif($csr_goles[$i]["gfgc"]=="gc") $arr_estadisticas[$csr_goles[$i]["idjugador"]]["gc"]=$arr_estadisticas[$csr_goles[$i]["idjugador"]]["gc"]+$csr_goles[$i]["goles"];
}


$csr_jugadors=$ConnMySQL->db_selectData("select j.idjugador, j.apodo, a.titular, a.minutos from jugadores j, equipos e, alineacion a where a.idequipo = e.idequipo and a.idjugador = j.idjugador and e.idequipo=1 and a.posicion in (1,13) and a.idpartido>=" . ($idpartido-$partidos_a_controlar) . " and a.idpartido<=" . ($idpartido-1));

$sizeof_csr_jugadors=sizeof($csr_jugadors);
for($i=0;$i<$sizeof_csr_jugadors;$i++){
	if(!isset($arr_puntos[$csr_jugadors[$i]["idjugador"]]["apodo"]))$arr_puntos[$csr_jugadors[$i]["idjugador"]]["apodo"]=$csr_jugadors[$i]["apodo"];
	if(!isset($arr_puntos[$csr_jugadors[$i]["idjugador"]]["puntos"])) $arr_puntos[$csr_jugadors[$i]["idjugador"]]["puntos"]=0;

	if($csr_jugadors[$i]["titular"]=="S") $arr_puntos[$csr_jugadors[$i]["idjugador"]]["puntos"]+=2;
	if($csr_jugadors[$i]["titular"]=="N") $arr_puntos[$csr_jugadors[$i]["idjugador"]]["puntos"]+=1;

	if($csr_jugadors[$i]["minutos"]>=1 and $csr_jugadors[$i]["minutos"]<=45) $arr_puntos[$csr_jugadors[$i]["idjugador"]]["puntos"]+=1;
	if($csr_jugadors[$i]["minutos"]>45) $arr_puntos[$csr_jugadors[$i]["idjugador"]]["puntos"]+=2;
}


$csr_jugadors=$ConnMySQL->db_selectData("select j.idjugador, j.apodo, a.titular, a.minutos from jugadores j, equipos e, alineacion a where a.idequipo = e.idequipo and a.idjugador = j.idjugador and e.idequipo=1 and a.posicion in (1,13) and a.idpartido>=" . $idpartido_inicio_liga);

$sizeof_csr_jugadors=sizeof($csr_jugadors);
for($i=0;$i<$sizeof_csr_jugadors;$i++){
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["convocado"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["convocado"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["titular"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["titular"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["suplente"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["suplente"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["minutos"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["minutos"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["ta"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["ta"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["tr"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["tr"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["gf"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["gf"]=0;
	if(!isset($arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["gc"])) $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["gc"]=0;
	if($csr_jugadors[$i]["titular"]=="S"){
		$arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["convocado"]++;
		$arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["titular"]++;
	}
	if($csr_jugadors[$i]["titular"]=="N"){
		$arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["convocado"]++;
		$arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["suplente"]++;
	}
	$arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["apodo"]=$csr_jugadors[$i]["apodo"];
	$arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["minutos"]+=$csr_jugadors[$i]["minutos"];

}



$csr_targes=$ConnMySQL->db_selectData("select j.idjugador, t.targeta from jugadores j, equipos e, alineacion a, tarjetas t where a.idequipo = e.idequipo and a.idjugador = j.idjugador and t.idpartido = a.idpartido and t.idjugador = a.idjugador and e.idequipo=1 and a.posicion in (1,13) and a.idpartido>=" . ($idpartido-$partidos_a_controlar) . " and a.idpartido<=" . ($idpartido-1));
$sizeof_csr_targes=sizeof($csr_targes);
for($i=0;$i<$sizeof_csr_targes;$i++){
	if($csr_targes[$i]["targeta"]=="ta1" || $csr_targes[$i]["targeta"]=="ta2") $arr_puntos[$csr_targes[$i]["idjugador"]]["puntos"]-=1;
	if($csr_targes[$i]["targeta"]=="tr") $arr_puntos[$csr_targes[$i]["idjugador"]]["puntos"]-=3;
}


$csr_targes=$ConnMySQL->db_selectData("select j.idjugador, t.targeta from jugadores j, equipos e, alineacion a, tarjetas t where a.idequipo = e.idequipo and a.idjugador = j.idjugador and t.idpartido = a.idpartido and t.idjugador = a.idjugador and e.idequipo=1 and a.posicion in (1,13) and a.idpartido>=" . $idpartido_inicio_liga);
$sizeof_csr_targes=sizeof($csr_targes);
for($i=0;$i<$sizeof_csr_targes;$i++){
	if($csr_targes[$i]["targeta"]=="ta1" || $csr_targes[$i]["targeta"]=="ta2"){
		if($csr_targes[$i]["targeta"]=="ta1"){
			$arr_estadisticas[$csr_targes[$i]["idjugador"]]["ta"]++;
		}elseif ($csr_targes[$i]["targeta"]=="ta2"){
			$arr_estadisticas[$csr_targes[$i]["idjugador"]]["ta"]=$arr_estadisticas[$csr_targes[$i]["idjugador"]]["ta"]+2;
		}
	}
	if($csr_targes[$i]["targeta"]=="tr") $arr_estadisticas[$csr_jugadors[$i]["idjugador"]]["tr"]++;

}


$csr_goles=$ConnMySQL->db_selectData("select j.idjugador, g.gfgc, count(*) as goles from jugadores j, equipos e, alineacion a, goles g where a.idequipo = e.idequipo and a.idjugador = j.idjugador and g.idpartido = a.idpartido and g.idjugador = a.idjugador and e.idequipo=1 and a.posicion in (1,13) and a.idpartido>=" . ($idpartido-$partidos_a_controlar) . " and a.idpartido<=" . ($idpartido-1) . " group by j.idjugador, g.gfgc, g.idpartido");
$sizeof_csr_goles=sizeof($csr_goles);
for($i=0;$i<$sizeof_csr_goles;$i++){
	if($csr_goles[$i]["gfgc"]=="p"){
		if($csr_goles[$i]["goles"]==0){
			$arr_puntos[$csr_goles[$i]["idjugador"]]["puntos"]+=3;
		}else{
			$arr_puntos[$csr_goles[$i]["idjugador"]]["puntos"]+=$csr_goles[$i]["goles"];
		}
	}
	if($csr_goles[$i]["gfgc"]=="gf") $arr_puntos[$csr_goles[$i]["idjugador"]]["puntos"]+=3;

}

$csr_goles=$ConnMySQL->db_selectData("select j.idjugador, g.gfgc, count(*) as goles from jugadores j, equipos e, alineacion a, goles g where a.idequipo = e.idequipo and a.idjugador = j.idjugador and g.idpartido = a.idpartido and g.idjugador = a.idjugador and e.idequipo=1 and a.posicion in (1,13) and a.idpartido>=" . $idpartido_inicio_liga . " group by j.idjugador, g.gfgc, g.idpartido");
$sizeof_csr_goles=sizeof($csr_goles);
for($i=0;$i<$sizeof_csr_goles;$i++){
	if($csr_goles[$i]["gfgc"]=="p"){
		$arr_estadisticas[$csr_goles[$i]["idjugador"]]["gc"]=$arr_estadisticas[$csr_goles[$i]["idjugador"]]["gc"]+$csr_goles[$i]["goles"];
	}elseif($csr_goles[$i]["gfgc"]=="gf") $arr_estadisticas[$csr_goles[$i]["idjugador"]]["gf"]=$arr_estadisticas[$csr_goles[$i]["idjugador"]]["gf"]+$csr_goles[$i]["goles"];

}


foreach ($arr_puntos as $key => $row) {
    $pts[$key]  = $row['puntos'];
}
array_multisort($pts, SORT_DESC, $arr_puntos);


foreach ($arr_estadisticas as $key => $row) {
    $est[$key]  = $row['minutos'];
}
array_multisort($est, SORT_DESC, $arr_estadisticas);

/*
$sizeof_arr_estadisticas=sizeof($arr_estadisticas);
for($i=0;$i<$sizeof_arr_estadisticas;$i++){

}
*/

?>


<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(0, 0, 0); font-weight: bold;">
El partit
</div>
<div style="width: 100%;">
<div style="float: left; font-size: 130%; font-weight: bold; width: 50%;">
<?php echo $csr_equipo_casa[0]["nombre"]; ?></div>
<div style="float: left; font-size: 130%; font-weight: bold; width: 50%;">
<?php echo $csr_equipo_fuera[0]["nombre"]; ?></div>
</div>
<div style="height: 5px; width: 100%;">
</div>
<div style="width: 100%;">
<?php echo $csr_partido[0]["competicion"]; ?></div>
<div style="text-align: center;">
<img alt="Rapitenca - Sants" border="0" src="http://www.apuestesuvida.com/sants/2017-2018/sants-rapitenca.jpg" id="imatge_principal" style="height: auto; max-width: 676px; width: 100%;" /></div>
<div style="height: 15px; width: 100%;">
</div>
<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(0, 0, 0); font-weight: bold;">
Lloc del partit</div>
<div style="width: 100%;">
<br />
<iframe frameborder="0" height="375" marginheight="0" marginwidth="0" scrolling="no" src="http://www.google.es/maps/ms?msid=207276383644634853213.00046e7e96c6081a2b0af&amp;msa=0&amp;ie=UTF8&amp;t=m&amp;vpsrc=6&amp;ll=<?php echo $csr_partido[0]["mapa"]; ?>&amp;spn=0.012079,0.021415&amp;z=15&amp;output=embed" width="500"></iframe><br />
<small>Mostra <a href="http://www.google.es/maps/ms?msid=207276383644634853213.00046e7e96c6081a2b0af&amp;msa=0&amp;ie=UTF8&amp;t=m&amp;vpsrc=6&amp;ll=<?php echo $csr_partido[0]["mapa"]; ?>&amp;spn=0.012079,0.021415&amp;z=15&amp;source=embed" style="color: blue; text-align: left;">Camps de futbol</a> en un mapa més gran</small><br /><br />
<?php echo $csr_partido[0]["campo"]; ?><br />
 <?php echo $csr_partido[0]["direccion"]; ?>
<br />
</div>
<br />
<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(0, 0, 0); font-weight: bold;">
Horari</div>
<div style="width: 100%;">
<?php echo $csr_partido[0]["horario"]; ?>
</div>
<div style="height: 5px; width: 100%;">
</div>
<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(0, 0, 0); font-weight: bold;">
Col.legiats</div>
<div style="width: 100%;">
Arbitre principal: <?php echo $csr_partido[0]["arbitro"]; ?><br />
Arbitre Assistent 1: <?php echo $csr_partido[0]["asistente1"]; ?><br />
Arbitre Assistent 2: <?php echo $csr_partido[0]["asistente2"]; ?><br />
<br /></div>
<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(0, 0, 0); font-weight: bold;">
Els de casa</div>
<div style="clear: both; width: 100%;">
</div>
<div style="width: 100%;">
<br />
<b>Dades:</b><br />
<div style="background-color: white;  clear: both; float: left; overflow: hidden; width: 352px;">
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: left; white-space: nowrap; width: 90px;">

</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
Conv.
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
Tit.
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
Supl.
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 50px;">
Min.
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
GF
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
GC
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
TG
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
TV
</div>
</div>
<?php

foreach ($arr_estadisticas as $clau => $arr_jugador){

?>
<div style="background-color: white;  clear: both; float: left; overflow: hidden; width: 352px;">
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: left; white-space: nowrap; width: 90px;">
<?php echo $arr_jugador["apodo"]; ?>
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
<?php echo $arr_jugador["convocado"]; ?>
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
<?php echo $arr_jugador["titular"]; ?>
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
<?php echo $arr_jugador["suplente"]; ?>
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 50px;">
<?php echo $arr_jugador["minutos"]; ?>
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
<?php echo $arr_jugador["gf"]; ?>
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
<?php echo $arr_jugador["gc"]; ?>
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
<?php echo $arr_jugador["ta"]; ?>
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 20px;">
<?php echo $arr_jugador["tr"]; ?>
</div>
</div>

<?php
}


?>
<div style="background-color: white;  clear: both; float: left; overflow: hidden; width: 352px;"></div>
<div style="clear: both; width: 100%;"></div>
 <br />
<br />
<b>Golejadors:</b><br />
<?php
$csr_goleadores=$ConnMySQL->db_selectData("select j.idjugador, j.apodo, count(*) as goles from jugadores j, equipos e, alineacion a, goles g where a.idequipo = e.idequipo and a.idjugador = j.idjugador and g.idpartido = a.idpartido and g.idjugador = a.idjugador and e.idequipo=1 and g.gfgc in ('gf','gp') and a.idpartido>=" . $idpartido_inicio_liga . " group by j.idjugador, j.apodo order by 3 desc");
$sizeof_csr_goleadores=sizeof($csr_goleadores);
$gol=0;
$txt_salida="";
for($i=0;$i<$sizeof_csr_goleadores;$i++){
	if($csr_goleadores[$i]["goles"]!=$gol){
		$txt_salida=substr($txt_salida,0,-2) . "<br />";
		$txt_gol="gols";
		if($csr_goleadores[$i]["goles"]==1) $txt_gol="gol";
		$txt_salida.=$csr_goleadores[$i]["goles"] . " " . $txt_gol . ": ";
		$gol=$csr_goleadores[$i]["goles"];
	}
	$txt_salida.=$csr_goleadores[$i]["apodo"] . ", ";
}
$txt_salida=substr($txt_salida,6,-2) . "<br />";
echo $txt_salida;
?>

<br />
<b>Targes:</b><br />
<?php
$csr_tarjetas=$ConnMySQL->db_selectData("select j.apodo, t.targeta, count(*) as num from jugadores j, equipos e, alineacion a, tarjetas t where a.idequipo = e.idequipo and a.idjugador = j.idjugador and t.idpartido = a.idpartido and t.idjugador = a.idjugador and e.idequipo=1 and a.idpartido>=" . $idpartido_inicio_liga . "  group by j.idjugador, t.targeta");
$sizeof_csr_tarjetas=sizeof($csr_tarjetas);
$pre_targetas=array();
for($i=0;$i<$sizeof_csr_tarjetas;$i++){
	$tipo='tr';
	if($csr_tarjetas[$i]["targeta"]=="ta1" || $csr_tarjetas[$i]["targeta"]=="ta2") $tipo='ta';
	if(!isset($pre_targetas[$tipo][$csr_tarjetas[$i]["apodo"]])) $pre_targetas[$tipo][$csr_tarjetas[$i]["apodo"]]=0;
	$factor=1;
	if($csr_tarjetas[$i]["targeta"]=="ta2") $factor=2;
	$pre_targetas[$tipo][$csr_tarjetas[$i]["apodo"]]+=($csr_tarjetas[$i]["num"]*$factor);

}
$arr_targes=$pre_targetas["ta"];
arsort($arr_targes);
$numero="";
$txt_salida="";
foreach ($arr_targes as $clau => $valor) {
    if($valor!=$numero){
    	$txt_salida=substr($txt_salida,0,-2) . "<br />";
    	$txt_tarja="grogues";
		if($valor==1) $txt_tarja="groga";
		$txt_salida.=$valor . " " . $txt_tarja . ": ";

    	$numero=$valor;
    }
    $txt_salida.=$clau . ", ";
}

$arr_targes=$pre_targetas["tr"];
arsort($arr_targes);
$numero="";
foreach ($arr_targes as $clau => $valor) {
    if($valor!=$numero){
    	$txt_salida=substr($txt_salida,0,-2) . "<br />";
    	$txt_tarja="vermelles";
		if($valor==1) $txt_tarja="vermella";
		$txt_salida.=$valor . " " . $txt_tarja . ": ";

    	$numero=$valor;
    }
    $txt_salida.=$clau . ", ";
}

echo substr($txt_salida,0,-2) . "<br />";
?>
<br />
<b>Sancions:</b>
Cura 1 partit (<a href="http://uesants.blogspot.com.es/2013/10/article-337.html" target="_blank">Art. 337</a>)<br />
<!--
Guille 1 partit (<a href="http://uesants.blogspot.com.es/2013/11/article-334.html" target="_blank">Art. 334</a>)<br />
El club (<a href="http://uesants.blogspot.com.es/2016/11/article-332.html)" target="_blank">Art. 332</a>)<br />
Faura 1 partit (<a href="http://uesants.blogspot.com.es/2014/05/338d.html)" target="_blank">Art. 338d</a>)<br />
cap.<br /><br />
Carlos 1 de 2 partits (<a href="http://uesants.blogspot.com.es/2013/09/article-338f.html" target="_blank">Art. 338f</a>)<br />
Alberto 1 partit (<a href="http://uesants.blogspot.com.es/2013/11/article-334.html" target="_blank">Art. 334</a>)<br />

Alberto 1 partit (<a href="http://uesants.blogspot.com.es/2013/12/article-336.html" target="_blank">Art. 336</a>)<br />

Peque 1 partit (<a href="http://uesants.blogspot.com.es/2013/11/article-334.html" target="_blank">Art. 334</a>)<br />

Guille 1 partit (<a href="http://uesants.blogspot.com.es/2013/11/article-334.html" target="_blank">Art. 334</a>)<br />
Fran 1 partit (<a href="http://uesants.blogspot.com.es/2013/11/article-334.html" target="_blank">Art. 334</a>)<br />

cap.


U.E. Sants Art. 355.- Incoar expedient d'aquest partit, considerant-lo pendent de resolucié. <br />
U.E. Sants Art. 352b).- Donar trasllat de la denéncia presentada al club UE Sants perqué en el termini de dos dies formuli alélegacions.


Gala 1 partit (<a href="http://uesants.blogspot.com.es/2013/12/article-336.html" target="_blank">Art. 336</a>)

Guille 1 partit (<a href="http://uesants.blogspot.com.es/2016/02/article-338f.html" target="_blank">Art. 338f</a>)<br />
 -->
 </div>
<br />
<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(0, 0, 0); font-weight: bold;">
El rival</div>
<div style="width: 100%;">
<?php
if($csr_partido[0]["idequipocasa"]==1){
	$nombre=$csr_equipo_fuera[0]["nombre"];
	$nombre_completo=$csr_equipo_fuera[0]["nombre_completo"];
	$escut=$csr_equipo_fuera[0]["escut"];
	$fundacio=$csr_equipo_fuera[0]["fundacio"];
	$web=$csr_equipo_fuera[0]["web"];
	$samarreta=$csr_equipo_fuera[0]["samarreta"];
	$pantalo=$csr_equipo_fuera[0]["pantalo"];
	$mitgetes=$csr_equipo_fuera[0]["mitgetes"];
}else{
	$nombre=$csr_equipo_casa[0]["nombre"];
	$nombre_completo=$csr_equipo_casa[0]["nombre_completo"];
	$escut=$csr_equipo_casa[0]["escut"];
	$fundacio=$csr_equipo_casa[0]["fundacio"];
	$web=$csr_equipo_casa[0]["web"];
	$samarreta=$csr_equipo_casa[0]["samarreta"];
	$pantalo=$csr_equipo_casa[0]["pantalo"];
	$mitgetes=$csr_equipo_casa[0]["mitgetes"];
}
?>
<span style="font-size: large; font-weight: bold;"><?php echo $nombre_completo; ?></span><br />
<img alt="<?php echo $nombre_completo; ?>" border="0" src="http://www.apuestesuvida.com/sants/escuts/<?php echo $escut; ?>" style="float: left; padding:8px;" title="<?php echo $nombre_completo; ?>" /><span style="font-weight: bold;">Any de fundacié:</span><?php echo $fundacio; ?><br />
Web:<a href="<?php echo $web; ?>" target="_blank"><?php echo $web; ?></a><br />
Samarreta: <?php echo $samarreta; ?><br />
Pantalé: <?php echo $pantalo; ?><br />
Mitgetes: <?php echo $mitgetes; ?></div>
<br />
<div style="clear: both; height: 5px; width: 100%;">
</div>

<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(0, 0, 0); font-weight: bold;">
Historial de partits</div>
<div style="float: left; width: 100%;">
<a href="http://uesants.blogspot.com.es/2012/05/ue-rapitenca-ue-sants.html">U.E. Rapitenca 3 - U.E. Sants 2 (26/05/2012)</a><br />
<a href="http://uesants.blogspot.com.es/2012/01/ue-sants-ue-rapitenca.html">U.E. Sants 1 - U.E. Rapitenca 3 (15/01/2012)</a><br />
<a href="http://uesants.blogspot.com/2010/10/ue-rapitenca-ue-sants.html">U.E. Sants 4 - U.E. Rapitenca 3 (20/03/2011)</a><br />
<a href="http://uesants.blogspot.com/2010/10/ue-rapitenca-ue-sants.html">U.E. Rapitenca 3 - U.E. Sants 0 (24/10/2010)</a><br />
	<!--
<a href="http://uesants.blogspot.com.es/2016/08/el-sants-supera-el-sant-cristobal-per.html">C.P. San Cristébal 0 - U.E. Sants 1  (14/08/2016)</a><br />
-->
<br />
<br />
</div>

<br />
<div style="-moz-background-clip: -moz-initial; -moz-background-inline-policy: -moz-initial; -moz-background-origin: -moz-initial; border-bottom: 1px solid rgb(0, 0, 0); font-weight: bold;">
Comparativa</div>
<!--posicio-->

<div style="background-color: white;  clear: both; float: left; overflow: hidden; width: 300px;">
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 22px;">
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
U.E. Sants
</div>
<div style="float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
U.E. Rapitenca
</div>
</div>
<div style="background-color: white;  clear: both; float: left; overflow: hidden; width: 300px;">
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 22px;">
PJ
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
9
</div>
<div style="float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
9
</div>
</div>
<div style="background-color: white;  clear: both; float: left; overflow: hidden; width: 300px;">
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 22px;">
G
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
5
</div>
<div style="float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
3
</div>
</div>
<div style="background-color: white;  clear: both; float: left; overflow: hidden; width: 300px;">
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 22px;">
E
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
3
</div>
<div style="float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
2
</div>
</div>
<div style="background-color: white;  clear: both; float: left; overflow: hidden; width: 300px;">
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 22px;">
P
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
2
</div>
<div style="float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
4
</div>
</div>
<div style="background-color: white;  clear: both; float: left; overflow: hidden; width: 300px;">
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 22px;">
GF
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
7</div>
<div style="float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
8
</div>
</div>
<div style="background-color: white;  clear: both; float: left; overflow: hidden; width: 300px;">
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 22px;">
GC
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
10
</div>
<div style="float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
11
</div>
</div>
<div style="background-color: white; clear: both; float: left; overflow: hidden; width: 300px;">
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 22px;">
</div>
<div style=" float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
<span style="background-color: #04b431; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Guanyat">G</span>&nbsp;
<span style="background-color: #04b431; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Guanyat">G</span>&nbsp;
<span style="background-color: #f78181; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Perdut">P</span>&nbsp;
<span style="background-color: #d7df01; color: white; font-size: 10px; font-weight: bolder; padding: 0 3px 0 3px;" title="Empatat">E</span>&nbsp;
<span style="background-color: #d7df01; color: white; font-size: 10px; font-weight: bolder; padding: 0 3px 0 3px;" title="Empatat">E</span>&nbsp;
<!--<span style="background-color: #d7df01; color: white; font-size: 10px; font-weight: bolder; padding: 0 3px 0 3px;" title="Empatat">E</span>&nbsp;
<span style="background-color: #04b431; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Guanyat">G</span>&nbsp;
<span style="background-color: #d7df01; color: white; font-size: 10px; font-weight: bolder; padding: 0 3px 0 3px;" title="Empatat">E</span>&nbsp;
<span style="background-color: #f78181; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Perdut">P</span>&nbsp;
<span style="background-color: #000000; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Perdut administrativament">P</span>&nbsp;
<span style="background-color: #f78181; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Perdut">P</span>&nbsp;
<span style="background-color: #d7df01; color: white; font-size: 10px; font-weight: bolder; padding: 0 3px 0 3px;" title="Empatat">E</span>&nbsp;
<span style="background-color: #04b431; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Guanyat">G</span>&nbsp;
-->
</div>
<div style="float: left; height: 25px; overflow: hidden; padding: 3px; text-align: right; white-space: nowrap; width: 128px;">
<span style="background-color: #f78181; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Perdut">P</span>&nbsp;
<span style="background-color: #04b431; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Guanyat">G</span>&nbsp;
<span style="background-color: #04b431; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Guanyat">G</span>&nbsp;
<span style="background-color: #04b431; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Guanyat">G</span>&nbsp;
<span style="background-color: #f78181; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Perdut">P</span>&nbsp;
<!--<span style="background-color: #04b431; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Guanyat">G</span>&nbsp;
<span style="background-color: #000000; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Ajornat">N</span>&nbsp;
<span style="background-color: #f78181; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Perdut">P</span>&nbsp;
<span style="background-color: #04b431; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Guanyat">G</span>&nbsp;
<span style="background-color: #d7df01; color: white; font-size: 10px; font-weight: bolder; padding: 0 3px 0 3px;" title="Empatat">E</span>&nbsp;
<span style="background-color: #04b431; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Guanyat">G</span>&nbsp;
<span style="background-color: #808080; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="No jugat">N</span>&nbsp;

<span style="background-color: #d7df01; color: white; font-size: 10px; font-weight: bolder; padding: 0 3px 0 3px;" title="Empatat">E</span>&nbsp;
<span style="background-color: #04b431; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Guanyat">G</span>&nbsp;
<span style="background-color: #f78181; color: white; font-size: 10px; font-weight: bolder; padding: 0 2px 0 2px;" title="Perdut">P</span>&nbsp;
-->
</div>
</div>
<!--posicio-->

<br />
