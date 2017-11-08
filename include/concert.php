<?php

$menu=array();
$concert=array();
$videos=array();

require_once "lib/MySQL.lib.php";

$content_bbdd_host="hostingmysql299.nominalia.com";
$content_bbdd_user="JG26981260_1";
$content_bbdd_pass="Apu3st3_";
$content_bbdd_bbdd="apuestesuvida_com_1";

$ConnMySQL=new MySQL();
$ConnMySQL->db_connect($content_bbdd_host,$content_bbdd_user,$content_bbdd_pass,$content_bbdd_bbdd);

$song=-1;
$path="";
$titulo="";
if(isset($_REQUEST["s"])) $song=$_REQUEST["s"];
$id='';
if(isset($_REQUEST["id"])){
	$id=trim($_REQUEST["id"]);
	$concert=$ConnMySQL->db_selectData("select idconcert, grup, descripcio, dir, img from concert where id='" . $id . "'");
	$videos=$ConnMySQL->db_selectData("select idsong, idconcert, titulo, path from concert_videos where idconcert='" . $concert[0]["idconcert"] . "'  order by idsong");
	$i=0;
	$sizeof_videos=sizeof($videos);
	while($i<$sizeof_videos){
		$videos[$i]["titulo"]=$videos[$i]["titulo"];
		if($song>=0 && $song==$videos[$i]["idsong"]){
			$path=$videos[$i]["path"];
			$titulo=$videos[$i]["titulo"];
		}
		$i++;
	}

}
$skin_concert="concert";
if($id==''){
	$skin_concert="concert_menu_principal";
	$menu=$ConnMySQL->db_selectData("select id, grup, ico from concert order by fecha_creacion desc");
	$i=0;
	$sizeof_menu=sizeof($menu);
	while($i<$sizeof_menu){
		$menu[$i]["grup"]=$menu[$i]["grup"];
		$i++;
	}
}else{
	if($song>=0){
		$skin_concert="concert";
	}else{
		$skin_concert="concert_menu_grup";
	}
}

if(isset($concert[0]["grup"])) $grup=$concert[0]["grup"]; else $grup="";
if(isset($concert[0]["descripcio"])) $desc=$concert[0]["descripcio"]; else $desc="";
if(isset($concert[0]["dir"])) $dir=$concert[0]["dir"]; else $dir="";
if(isset($concert[0]["img"])) $img=$concert[0]["img"]; else $img="";

$Skinner=new Skinner();
$Skinner->setSkin($base_skins . $skin_concert);
$Skinner->registerVariable("id", $id);
$Skinner->registerVariable("grup", $grup);
$Skinner->registerVariable("desc", $desc);
$Skinner->registerVariable("dir", $dir);
$Skinner->registerVariable("img", $img);
$Skinner->registerVariable("path", $path);
$Skinner->registerVariable("titulo", $titulo);
$Skinner->createRepeticion("videos");
$Skinner->registerVariable("videos", $videos);
$Skinner->createRepeticion("menu");
$Skinner->registerVariable("menu", $menu);
$cuerpo=$Skinner->doSubstitution_FAST();
$ConnMySQL->close();
?>