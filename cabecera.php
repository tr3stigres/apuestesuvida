<?php
$activa_1='';
$activa_2='';
$activa_3='';
$activa_4='';
$activa_5='';

if(isset($_REQUEST["r"])){
	if($_REQUEST["r"]=='links'){
		$activa_2='class="active"';
	}elseif($_REQUEST["r"]=='concert'){
		$activa_3='class="active"';
	}elseif($_REQUEST["r"]=='bso'){
		$activa_4='class="active"';
	}elseif($_REQUEST["r"]=='cajon'){
		$activa_5='class="active"';
	}else{
		$activa_1='class="active"';
	}
}else{
	$activa_1='class="active"';
}


$Skinner=new Skinner();
$Skinner->setSkin($base_skins . "cabecera");
$Skinner->registerVariable("activa_1",$activa_1);
$Skinner->registerVariable("activa_2",$activa_2);
$Skinner->registerVariable("activa_3",$activa_3);
$Skinner->registerVariable("activa_4",$activa_4);
$Skinner->registerVariable("activa_5",$activa_5);
$cabecera=$Skinner->doSubstitution_FAST();

?>

