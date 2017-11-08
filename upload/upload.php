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
<script src="http://www.apuestesuvida.com/js/jquery.min.js"></script>
<script type="text/javascript">
	var v_upload =  '';
	var image_upload_script =  '/MiniUploadForm/ImgUpload.php';
</script>
<!-- MiniUploadForm -->
<script src="/MiniUploadForm/assets/js/jquery.ui.widget.js"></script>
<script src="/MiniUploadForm/assets/js/jquery.iframe-transport.js"></script>
<script src="/MiniUploadForm/assets/js/jquery.fileupload.js"></script>
<style>

.colaFicheros2 ul{
	list-style-type: none;
	margin: 0;
  	padding: 0;
  	postion: relative; 
  	left: 25px;
  	width: 100%;
  	min-width: 140px;  	
}
.colaFicheros2 li{
	list-style-type: none;
	margin: 0;
  	padding: 0;
  	postion: relative; 
  	overflow : hidden;
  	width: 100%;
  	min-width: 150px;
  	min-height: 170px;
}

.titulo_seccion{
	color:#FFFFFF;
	background:#000000;
	font-weight:normal;
	font-size: 12px;
	width:100%;
	height: 14px;
}
/******* Nous butons *******/

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
/******* Nous butons *******/

</style>
<!-- MiniUploadForm -->
	<div style="clear: both; height: 10px;"></div>
	<div style="clear: both; height: 20px;" class="titulo_seccion">Upload</div>
	<div style="clear: both; height: 40px;"></div>
	<div style="text-align: left;">
	    <input type="button" value="Añadir ficheros" class="button_20" style="background: #f3f3f3 url(/images/new-icon.png) 5px 3px no-repeat;" id="afegir" />
	    <input class="upl" style="position: absolute; top: 0; right: 0; margin: 0; padding: 0; font-size: 20px; cursor: pointer; opacity: 0; filter: alpha(opacity=0);" type="file" name="Filedata" multiple />
	    <input type="button" value="Marcar todas" id="check_all" class="button_20" style="background: #f3f3f3 url(/images/checked.png) 5px 3px no-repeat;" />
	    <input type="button" value="Desmarcar todas" class="button_20" style="background: #f3f3f3 url(/images/unchecked.png) 5px 3px no-repeat;"  id="uncheck_all" />
	    <div style="clear: both; height: 10px;"></div>
	    <div id="fileQueue" class="colaFicheros2"><ul></ul></div>
	</div>    
	<div style="clear: both; height: 30px;"></div>
	<div style="clear: both; height: 20px;" class="titulo_seccion">Errores</div>
	<div style="clear: both; height: 10px;"></div>
	<div id="zona_err" style="float: left;text-align: left;"></div>
	
	
	

<script>

var arr_fitxers=Array();

var url_basica='[base_https]/index.php?resource=GCON700100';
var arr_id=Array();

function sizeFormat(p_size){	
	//$('.upl').fileupload on fileuploadfail
	//$('.upl').fileupload add
			
	//Traductor de la mida del fitxer en bytes a una nomenclatura mes fàcil
	var v_size=parseFloat(p_size);		
    if(v_size<1024){
        return v_size+" bytes";
    }else{
    	if(v_size<(1024*1024)){
    		v_size=Math.round(v_size/1024).toFixed(1);
        	return v_size+" KB";
    	}else{
    		if(v_size<(1024*1024*1024)){
    			v_size=Math.round(v_size/(1024*1024)).toFixed(1);	
    			return v_size+" MB";
    		}else{
    			if(v_size<(1024*1024*1024*1024)){
    				v_size=Math.round(v_size/(1024*1024*1024)).toFixed(1);	
        			return v_size+" GB";
    			}else{
    				v_size=Math.round(v_size/(1024*1024*1024*1024)).toFixed(1);
        			return v_size+" TB";
    			}
    		}
    	}
    }

}





function nametoid(p_name){
	var v_lon=p_name.length;
	var v_nou_nom='';
	p_name=p_name.toLowerCase(); 
	
	for(var i=0; i<v_lon; i++){
		if((p_name[i]>='a' && p_name[i]<='z') || (p_name[i]>='0' && p_name[i]<='9')){
			v_nou_nom+=p_name[i];
		}else{
			v_nou_nom+='_';
		}
	}
	
	return v_nou_nom;
}

function borrar(p_name){
	$.post('/upload/upload.php', {
		a         : 'delete',
		file      : p_name
	}, function( xml ) {
		$("#" + nametoid(p_name)).remove();
	});
}

$("#check_all").click(function(){		
	$('.check_box').each(function() {
	    this.checked = true;
	});
});

$("#uncheck_all").click(function(){		
	$('.check_box').each(function() {
	    this.checked = false;
	});
});

$("#afegir").click(function(){		
	$('.upl').click();
});


$(function(){
	// Initialize the jQuery File Upload plugin
    $('.upl').fileupload({
		url: image_upload_script,
        maxFileSize: 10240000, // 10 MB
    	limitConcurrentUploads: 50,
    	add: function (e, data) {
        	
            var v_nom=data.files[0].name;
            var v_lon=v_nom.length;
            var v_id=nametoid(v_nom);
            
            
            var tpl = $('<li id="' + v_id + '"><img src="/images/ajax_loader.gif" width="31" height="31" border="0" id="img_' + v_id + '"><span id="text"></span> - <span id="percentatge"></span><input type="checkbox" class="check_box" id="chk_' + v_id + '"><input type="button" class="button_20" style="background: #f3f3f3 url(/images/trash.png) 5px 3px no-repeat;"  value="Eliminar" id="delete" onclick="borrar(\''+ v_nom + '\')" /></li>');
            
            $("#fileQueue").show();
            
            tpl.find('#text').text(v_nom).append(' ' + sizeFormat(data.files[0].size));
            
            data.context = tpl.appendTo($('#fileQueue ul'));
			// Automatically upload the file once it is added to the queue
			
			
			
            var jqXHR = data.submit();
            
        },
		progress: function(e, data){
        	var progress = parseInt(data.loaded / data.total * 100, 10);
			data.context.find('#percentatge').html('(' + progress + '%)').change();
			data.context.find('#progress .progress-bar').css('width',progress + '%');
		},
		fail:function(e, data){
			data.context.remove();
        },
        done: function(e, data) { 
        	var v_id=nametoid(data.files[0].name);
        	arr_id[v_id]=data.files[0].name;
        	
        	if(data.files[0].type=='image/png' || data.files[0].type=='image/jpeg' || data.files[0].type=='image/gif'){
				$('#img_' + v_id).attr({
				  src: 'http://www.apuestesuvida.com/upload/calaix/' + data.files[0].name
				});
        	}else{
        		$('#img_' + v_id).attr({
				  src: 'http://www.apuestesuvida.com/images/file.png'
				});
        	}
        	
			$('#img_' + v_id).css({
				'width':  '150px',
				'height': 'auto'
			});	
        	
		},
        send: function(e, data) { 
			//console.log('send');
			if(document.addEventListener){
				var files = data.files;
				if(data.maxFileSize && files[0].size>data.maxFileSize){ 
					return false;
				}
				
				if(data.acceptFileTypes && !(data.acceptFileTypes.test(files[0].type))){
					return false;
				}
			}
			//sense control ni de tipus ni tamany si es ie8			
		}
    }).on('fileuploadfail', function (e, data) {
    	//console.log('fileuploadfail');
    	var v_msg_error="Se ha producido un error inesperado.";
    	var d_num_act_img_pro=$("#num_act_img_pro");
    	var v_num_act_img_pro=parseInt(d_num_act_img_pro.val());
    	var d_error_upload=$('#zona_err');
    	
    	if(document.addEventListener){
			if(data.maxFileSize){
		    	if(data.files[0].size>data.maxFileSize){
		    		v_msg_error="El archivo excede el límite de tamaño (" + sizeFormat(data.maxFileSize) + ")";
		    		v_num_act_img_pro++;
		    		d_num_act_img_pro.val(v_num_act_img_pro);
				}
		    	
			}
			if(data.acceptFileTypes){
		    	if(!data.acceptFileTypes.test(data.files[0].type)){
					v_msg_error="Tipo de archivo no aceptado";
					v_num_act_img_pro--;
					d_num_act_img_pro.val(v_num_act_img_pro);
				}
				
			}
		}
		//sense control ni de tipus ni tamany per ie8
		
		d_error_upload.html(d_error_upload.html() + "<b>" + data.files[0].name + "</b> " + v_msg_error + "<br>");
	});	    
});
</script>