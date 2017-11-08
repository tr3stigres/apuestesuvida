<?php

	if(isset($_REQUEST["a"])) $accio=$_REQUEST['a']; else $accio="";

	if(trim($accio)!=""){
		session_write_close(); //truc per guanyar velocitat. tanquem la sesio en les crides de jquery. JGX(15/05/2015)
		if($accio=='delete'){
	    	if(isset($_REQUEST["file"])) $fitxer=$_REQUEST['file']; else $fitxer="";
	    	if(file_exists("../upload/calaix/" . $fitxer)) unlink("../upload/calaix/" . $fitxer);
			die();
	    }
		die();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Pagina personal de Jordi Gil">
		<meta name="keywords" content="Apueste su vida, Aposti a seva vida, You bet your life, Jordi Gil, Jordi Gil Ximénez">
		<link href='http://blog.apuestesuvida.com/favicon.ico' rel='icon' type='image/x-icon'/>
		<title>Apueste su vida | Jordi Gil's website</title>


		<!-- Bootstrap core CSS -->
		<link href="/css/bootstrap.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="/css/bootstrap-theme.css" rel="stylesheet">
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<link href="/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="/css/theme.css" rel="stylesheet">


		<script src="/js/jquery.min.js"></script>
		<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
		<!--[if lt IE 9]><script src="/js/ie8-responsive-file-warning.js"></script><![endif]-->
		<script src="/js/ie-emulation-modes-warning.js"></script>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<link href="/css/font-awesome.css" rel="stylesheet">

		<script>
			jQuery(document).ready(function() {
			    jQuery('.toggle-nav').click(function(e) {
			        jQuery(this).toggleClass('active');
			        jQuery('.menu ul').toggleClass('active');

			        e.preventDefault();
			    });
			});


			function borrar(p_name, p_id){
				$.post('/upload/recupera.php', {
					a         : 'delete',
					file      : p_name
				}, function( xml ) {
					$("#" + p_id).parent().parent().remove();
				});
			}

		</script>

		<style>
			.button_20 {
				padding: 3px 10px 3px 25px;
				border: solid 1px #b7b7b7;
				position: relative;
				cursor: pointer;
				display: inline-block;
				font-family: Arial, Helvetica, sans-serif;
				font-size: 11px;
				font-weight:bold;
				height: 24px;
				text-decoration: none;
				color: #4f4f4f;
				-moz-border-radius-bottomleft: 5px;
				-moz-border-radius-bottomright: 5px;
				-moz-border-radius-topleft: 5px;
				-moz-border-radius-topright: 5px;


			}

			.button_20 img {
				position: absolute;
				top: 2px;
				left: 5px;
				border: none;
			}
			.button_20:hover {
				color: #40740d;
			}
		</style>
	</head>
	<body>
		<!-- Fixed navbar -->
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="http://www.apuestesuvida.com">Apueste su vida</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li ><a href="http://www.apuestesuvida.com">Home</a></li>
						<li ><a href="http://www.apuestesuvida.com/links">Links ràpids</a></li>
						<li ><a href="http://www.apuestesuvida.com/video/concert">Videos</a></li>
						<li ><a href="http://www.apuestesuvida.com/banda_sonora">Banda sonora</a></li>
						<li ><a href="http://www.apuestesuvida.com/cajon">Calaix de sastre</a></li>


					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>
		<div class="container theme-showcase" role="main">
			<div class="row">


				<?php

					$dir = "../upload/calaix/";
					if (is_dir($dir)) {
						if ($dh = opendir($dir)) {
							while (($file = readdir($dh)) !== false) {

								if ($file!='..' && $file!='.'){
									$ext=substr(strrchr($file, "."), 1);

									$id=strtolower($file);
									$lon=strlen($id);
									$i=0;
									while($i<$lon){
										if(($id[$i]>='a' && $id[$i]<='z') || ($id[$i]>='0' && $id[$i]<='9')){

										}else{
											$id[$i]="_";
										}
										$i++;
									}


				?>
				<div class="col-sm-4" style="width: 250px; height: 250px;">
					<?php
						$path="http://www.apuestesuvida.com/upload/calaix/" . $file;
						if($ext=="jpg" || $ext=="gif" || $ext=="png"){
					?>
					<img src="<?php echo $path ?>" class="img-thumbnail" style="max-height: 150px;" id="<?php echo $id ?>" />
					<?php
						}else{
					?>
					<img src="http://www.apuestesuvida.com/images/file.png" class="img-thumbnail" style="max-height: 150px;" id="<?php echo $id ?>" />
					<?php
						}

					?>

					<div class="titleBox" style="position: absolute; background: #dbd8d8; width:70%;">
						<?php echo $file ?><br />
						<input type="button" value="Descargar" class="button_20" style="background: #f3f3f3 url(/images/subir.png) 5px 3px no-repeat;"  id="upload" onclick="window.open('<?php echo $path ?>', '_blank')" /><br />
						<input type="button" class="button_20" style="background: #f3f3f3 url(/images/trash.png) 5px 3px no-repeat;"  value="Eliminar" id="delete" onclick="borrar('<?php echo $file ?>', '<?php echo $id ?>')" /><br />

					</div>

				</div>
				<?php
									}
								}
								closedir($dh);
							}
						}

				?>




				</div>
		</div>

		<div class="well">
			<p style="text-align: center;">Aquests son els meus principis, i si no li agradan, en tinc d'altres (Groucho Marx).</p>
		</div>

		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->

		<script>window.jQuery || document.write('<script src="/js/jquery.min.js"><\/script>')</script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/docs.min.js"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="/js/ie10-viewport-bug-workaround.js"></script>
	</body>
</html>
