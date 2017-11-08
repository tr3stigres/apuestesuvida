<?php

//include_path=".:/zend/library";

require_once "include/var.php";
require_once "lib/class_Skinner.php";
$header="";
$cabecera="";
$cuerpo="";
$pie="";

require_once "include/cabecera.php";
require_once "include/pie.php";

$arr_fotos=array();

if(isset($_REQUEST["r"])){
	switch ($_REQUEST["r"]){
		case 'links':
			require_once "include/links.php";
			break;
		case 'concert':
			require_once "include/concert.php";
			break;
		case 'cajon':
			require_once "include/cajon.php";
			break;
		case 'bso':
			require_once "include/bso.php";
			break;
                case 'portfolio':
			require_once "include/portfolio.php";
			break;
                case 'fotos':
			require_once "include/fotos.php";
			break;
		default:
			require_once "include/home.php";
	}

}else{
	require_once "include/home.php";
}



$Skinner=new Skinner();
$Skinner->setSkin($base_skins .'index_responsive');
$Skinner->registerVariable("header", $header);
$Skinner->registerVariable("cabecera", $cabecera);
$Skinner->registerVariable("cuerpo", $cuerpo);
$Skinner->registerVariable("pie", $pie);
$Skinner->registerVariable("base_http", $base_http);
echo $Skinner->doSubstitution_FAST();

?>
