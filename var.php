<?php
    error_reporting(E_ALL);

    $base_skins="skins/";
	$skin="index";
	
	if($_SERVER['SERVER_NAME']=='localhost'){
		$base_http="http://localhost/www.apuestesuvida.com";
	}else{
		$base_http="http://www.apuestesuvida.com";
	}
	
    /*
	$content_bbdd_host		="localhost";
	$content_bbdd_user		="root";
	$content_bbdd_pass		="";
	$content_bbdd_bbdd		="cm135799";
	$base_http_web=$base_http;


	$base_http="http://www.haboob.net";
	$base_http_web="http://www.haboob.net";
	$content_bbdd_host		="mysql4-vh.amenworld.com";
	$content_bbdd_user		="cm135799";
	$content_bbdd_pass		="r0av8xjo";
	$content_bbdd_bbdd		="cm135799";
	*/
	
?>
