<?php


//var_dump($_REQUEST);

$js_about=array();
$js_links=array();
$js_concert=array();
$js_lista_concert=array();
$js_cancion_concert=array();
$js_fotos=array();
$js_cajon=array();
$js_post_cajon=array();
$js_bso=array();
$js_home=array();
$css_about=array();
$css_links=array();
$css_concert=array();
$css_lista_concert=array();
$css_cancion_concert=array();
$css_fotos=array();
$css_cajon=array();
$css_post_cajon=array();
$css_bso=array();
$css_home=array();

if(isset($_REQUEST["r"])){
	switch ($_REQUEST["r"]){
		case 'about': 
			$js_about[0]["ok"]="S";
			$css_about[0]["ok"]="S";
			break;
        case 'links': 
        	$js_links[0]["ok"]="S";
        	$css_links[0]["ok"]="S";
			break;
		case 'concert': 
		    if(isset($_REQUEST["id"])){
		    	$js_lista_concert[0]["ok"]="S";
		    	$css_lista_concert[0]["ok"]="S";
		    }else{
				if(isset($_REQUEST["s"])){
			    	$js_cancion_concert[0]["ok"]="S";
			    	$css_cancion_concert[0]["ok"]="S";
			    }else{
					$js_concert[0]["ok"]="S";
					$css_concert[0]["ok"]="S";
			    }
		    }
			break;
        case 'fotos': 
        	$js_fotos[0]["ok"]="S";
        	$css_fotos[0]["ok"]="S";
			break;
        case 'bso': 
            $js_bso[0]["ok"]="S";
        	$css_bso[0]["ok"]="S";
			break;
        case 'cajon': 
            if(isset($_REQUEST["id"])){
                 $js_post_cajon[0]["ok"]="S";
        	     $css_post_cajon[0]["ok"]="S";
            }else{
        	     $js_cajon[0]["ok"]="S";
        	     $css_cajon[0]["ok"]="S";
            }
			break;
        default: 
        	$js_home[0]["ok"]="S";
        	$css_home[0]["ok"]="S";
	}
}else{
	$js_home[0]["ok"]="S";
        $css_home[0]["ok"]="S";
}

$Skinner=new Skinner();
$Skinner->setSkin($base_skins ."/header");
$Skinner->createRepeticion( "js_about" );
$Skinner->registerVariable( "js_about",$js_about);
$Skinner->createRepeticion( "js_links" );
$Skinner->registerVariable( "js_links",$js_links);
$Skinner->createRepeticion( "js_concert" );
$Skinner->registerVariable( "js_concert",$js_concert);
$Skinner->createRepeticion( "js_cancion_concert" );
$Skinner->registerVariable( "js_cancion_concert",$js_cancion_concert);
$Skinner->createRepeticion( "js_lista_concert" );
$Skinner->registerVariable( "js_lista_concert",$js_lista_concert);
$Skinner->createRepeticion( "js_fotos" );
$Skinner->registerVariable( "js_fotos",$js_fotos);
$Skinner->createRepeticion( "js_bso" );
$Skinner->registerVariable( "js_bso",$js_bso);
$Skinner->createRepeticion( "js_cajon" );
$Skinner->registerVariable( "js_cajon",$js_cajon);
$Skinner->createRepeticion( "js_post_cajon" );
$Skinner->registerVariable( "js_post_cajon",$js_post_cajon);
$Skinner->createRepeticion( "js_home" );
$Skinner->registerVariable( "js_home",$js_home);
$Skinner->createRepeticion( "css_about" );
$Skinner->registerVariable( "css_about",$css_about);
$Skinner->createRepeticion( "css_links" );
$Skinner->registerVariable( "css_links",$css_links);
$Skinner->createRepeticion( "css_concert" );
$Skinner->registerVariable( "css_concert",$css_concert);
$Skinner->createRepeticion( "css_cancion_concert" );
$Skinner->registerVariable( "css_cancion_concert",$css_cancion_concert);
$Skinner->createRepeticion( "css_lista_concert" );
$Skinner->registerVariable( "css_lista_concert",$css_lista_concert);
$Skinner->createRepeticion( "css_fotos" );
$Skinner->registerVariable( "css_fotos",$css_fotos);
$Skinner->createRepeticion( "css_bso" );
$Skinner->registerVariable( "css_bso",$css_bso);
$Skinner->createRepeticion( "css_cajon" );
$Skinner->registerVariable( "css_cajon",$css_cajon);
$Skinner->createRepeticion( "css_post_cajon" );
$Skinner->registerVariable( "css_post_cajon",$css_post_cajon);
$Skinner->createRepeticion( "css_home" );
$Skinner->registerVariable( "css_home",$css_home);
$header=$Skinner->doSubstitution_FAST();
?>