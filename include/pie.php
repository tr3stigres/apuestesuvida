<?php


$frase="Si pot somiar-ho pots fer-ho (Walt Disney).";

if(isset($_REQUEST["r"])){
     switch ($_REQUEST["r"]){
          case 'links': 
               $frase="Sigues tu i prova de ser feliç, pero sobretot sigues tu. (Charles Chaplin).";
               break;
          case 'concert': 
               $frase="Es mes com una gran bola de wibbly-wobbly...timey-wimey...cosas (El Doctor)";
               break;
         case 'bso': 
               $frase="Si vols resultats diferents, no facis sempre el mateix (Albert Einstein).";
               break;
          case 'cajon': 
               $frase="Aquests son els meus principis, i si no li agradan, en tinc d'altres (Groucho Marx).";
               break;
          default: 
               $frase="Si pot somiar-ho pots fer-ho (Walt Disney).";
     }
}

$Skinner=new Skinner();
$Skinner->setSkin($base_skins ."/pie");
$Skinner->registerVariable( "frase",$frase);
$pie=$Skinner->doSubstitution_FAST();
?>