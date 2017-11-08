<?php
    
function link_titulo($titulo){
	$origen=array(' ','á','é','í','ó','ú','à','è','ì','ò','ù','ñ','ç',',','.','ö','(',')');
	$destino=array('_','a','e','i','o','u','a','e','i','o','u','n','c','_','_','o','_','_');
	$titulo=strtolower($titulo);
	return str_replace($origen, $destino, $titulo);
}

$titulo="";
$html="";
$tags="";
$arr_salida=array();
$csr_tags=array();
require_once "lib/MySQL.lib.php";

$content_bbdd_host ="hostingmysql299.nominalia.com";
$content_bbdd_user ="JG26981260_1";
$content_bbdd_pass ="Apu3st3_";
$content_bbdd_bbdd ="apuestesuvida_com_1";

$ConnMySQL=new MySQL();
$ConnMySQL->db_connect($content_bbdd_host,$content_bbdd_user,$content_bbdd_pass,$content_bbdd_bbdd);

if(isset($_REQUEST["t"]) && trim($_REQUEST["t"])!=""){
	if(isset($_REQUEST["id"]) && trim($_REQUEST["id"])!="") $idcajon=trim($_REQUEST["id"]);
	$csr_menu=$ConnMySQL->db_selectData("select titulo, html, date_format(fecha_creacion,'%d/%m/%Y %H:%i') as fecha from cajon_de_sastre where idcajon=" . $idcajon);
	//$html=ereg_replace('class="video-container"',"",$csr_menu[0]["html"]);
        $html=$csr_menu[0]["html"];
	$titulo=$csr_menu[0]["titulo"] . " (" . $csr_menu[0]["fecha"] . ")";
	$path_skin="cajon_post";
}else{
	$csr_tags=$ConnMySQL->db_selectData("select idtag, tag from apuestesuvida_com_1.tags order by tag");
	$i=0;
	$sizeof_csr_tags=sizeof($csr_tags);
	$tags="";
	while($i<$sizeof_csr_tags){
		$csr_menu=$ConnMySQL->db_selectData("select c.idcajon, c.titulo, date_format(c.fecha_creacion,'%d/%m/%Y %H:%i') as fecha from cajon_de_sastre c, cajon_tags t where c.idcajon = t.idcajon and t.idtag=" . $csr_tags[$i]["idtag"] . " order by c.fecha_creacion desc");
		$j=0;
		$sizeof_csr_menu=sizeof($csr_menu);
		$menu="";
		while($j<$sizeof_csr_menu){
			$arr_salida[$j]["tag"]=$csr_tags[$i]["tag"];
			$arr_salida[$j]["link"]=$base_http . "/cajon/" . link_titulo($csr_menu[$j]["titulo"]) . "/" . $csr_menu[$j]["idcajon"];
			$arr_salida[$j]["titulo"]=$csr_menu[$j]["titulo"] . " (" . $csr_menu[$j]["fecha"] . ")";
			$j++;
		}
		$tags.=$csr_tags[$i]["tag"] . " ";
		$i++;
	}
	$tags=substr($tags,0,-1);
	$path_skin="cajon";
}

$ConnMySQL->close();

$Skinner=new Skinner();
$Skinner->setSkin($base_skins . $path_skin);
$Skinner->createRepeticion("menu");
$Skinner->registerVariable("menu", $arr_salida);
$Skinner->createRepeticion("arr_tags");
$Skinner->registerVariable("arr_tags", $csr_tags);
$Skinner->registerVariable("tags", $tags);
$Skinner->registerVariable("html", $html);
$Skinner->registerVariable("titulo", $titulo);
$cuerpo=$Skinner->doSubstitution_FAST();

?>