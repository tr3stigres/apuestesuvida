<?php

$Skinner=new Skinner();
$Skinner->setSkin($base_skins . "about");
$Skinner->registerVariable("social", $social);
$cuerpo=$Skinner->doSubstitution_FAST();

?>

