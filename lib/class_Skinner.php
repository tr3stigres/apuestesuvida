<?php
// Skinner. Version 2.0
/**
* La clase Skinner se encarga de separar control de visualizacion (en general)
* Sus responsabilidades son, sobre todo, la del control de skins, encontrar el skin adecuado
* De las sustituciones se encarga la clase Sustituidor
* Se ha añadido una funcion getXML() que está en fase de prueba. El resto parece completamente estable.
*
**/

class Skinner
	{
	var $skinsFolder;
	var $skinsFolderDefault;
	var $skinsExt;
	var $subExt;
	var $skin;
	var $skinString;
	var $elDirectorioGenerico;
	var $elNombreGenerico;
	var $elSustituidor;
	var $reIni;
	var $reFin;
	var $SustitucionHecha;
		
	function Skinner()
		{
		$this->reIni="\[";
		$this->reFin="\]";
		$this->elSustituidor=new Sustituidor($this->reIni,$this->reFin,1==0,"TOP_LEVEL");
		$this->elDirectorioGenerico="SKINS_DEFAULT/generico/";
		$this->skinsExt=".skin";
		$this->SustitucionHecha="";
		}	
	function setRegexpIni($regexp)
		{
		$this->reIni=$regexp;
		}
	function setRegexpFin($regexp)
		{
		$this->reFin=$regexp;
		}	
	
	function setDirGenerico($directorio)
		{
		$this->elDirectorioGenerico=$directorio;
		
		}
	function setNombreGenerico($nombre)
		{
		$this->elNombreGenerico=$nombre;
		}	
	function setSkinsFolder($folder)
		{
		$this->skinsFolder=$folder;
		}
	function setSubExtension($ext)
		{
		$this->subExt=$ext;
		}
	function setSkinsFolderDefault($folder)
		{
		$this->skinsFolderDefault=$folder;
		}
		
	function setSkinsExtension($Ext)
		{
		$this->skinsExt=$Ext;
		}
	function setSkin($skin)
		{

		if($this->subExt!="")
			{
			$subext=".".$this->subExt;
			}
		else
			{
			$subext="";
			}	
		
		$filename=$this->skinsFolder.$skin.$subext.$this->skinsExt;
		if (file_exists($filename))
			{
			$nombre_fichero=$filename;
			}
		else
			{
			$filename=$this->skinsFolderDefault.$skin.$subext.$this->skinsExt;
			if(file_exists($filename))
				{
				$nombre_fichero=$this->skinsFolderDefault.$skin.$subext.$this->skinsExt;
				}
			else
				{
				$nombre_fichero=$this->elDirectorioGenerico.$this->elNombreGenerico.$subext.$this->skinsExt;
				}		
			}
		if (file_exists($nombre_fichero))
			{	
			$fd=fopen($nombre_fichero,"r");
			$skin_f=fread($fd,filesize($nombre_fichero));
			fclose($fd);
			$this->skinString=$skin_f;
			}
		else
			{
			if ($subext=="")
				{
				$this->skinString="";
				}
			else
				{
				$this->subExt="";
				$this->setSkin($skin);
				}	
			}	
		}
		
	function setSkinString($skin_f)
		{
		$this->skinString=$skin_f;
		}
	function getSkinString()
		{
		return ($this->skinString);
		}
		
	function setREGs($reIni,$reFin)	
		{
		
		 $this->reIni=$reIni;
		 $this->reFin=$reFin;
		 $this->elSustituidor->setREGs($reIni,$reFin);
		}
		
	function registerVariables($dataRow)
		{
		$this->SustitucionHecha="";
		$els=$this->elSustituidor;
		$els->registerVariables($dataRow);
		$this->elSustituidor=$els;
		}
	function getVariables()
		{
		$els=$this->elSustituidor;
		return $els->VariablesArray;
		
		}	
	function createRepeticionRecordset($nombre)
		{
		$array_nombre=explode("/",$nombre);
		$this->elSustituidor->registerSubBloqueRs($array_nombre);
		}
	function createSplitter($nombre)
		{
		$array_nombre=explode("/",$nombre);
		$this->elSustituidor->registerSplitter($array_nombre);
		}
		
	function doSubstitution()
		{
		$this->elSustituidor->setSkinString($this->skinString);
		$this->SustitucionHecha=$this->elSustituidor->doSubstitution();
		return $this->SustitucionHecha;	
		}
	function doSubstitution_FAST()
		{
		$this->elSustituidor->setSkinString($this->skinString);
		$this->SustitucionHecha=$this->elSustituidor->doFastSubstitution();
		return $this->SustitucionHecha;	
		}

		
	function Sustituir()
		{
		$this->elSustituidor->setSkinString($this->skinString);
		$this->SustitucionHecha=$this->elSustituidor->doSubstitution();
		//$this->skinString=""; // parece ser que es demasiado pequeño en BD-->puede dar lugar a bugs???
		}
		
	function getSubstitution()
		{
		return $this->SustitucionHecha;
		}
		
	//----------------------------------------------------Funciones para mantener compatibilidad	
	function registerVariable($nombre,$valor)
		{
		$this->elSustituidor->registerVariable($nombre,$valor);
		}
	function registerVariableArray($nombre,$array_valor)
		{
		$array_nombre=explode("/",$nombre);
		$this->elSustituidor->registerVariableArray($array_nombre,$array_valor);
		}
		
	function createRepeticion($nombre)
		{
		$array_nombre=explode("/",$nombre);
		$this->elSustituidor->registerSubBloque($array_nombre);
		}
	function createRepeticionRs($nombre,$recordset)
		{
		// para el primer nivel; esta funcion solo existe para mantener la compatibilidad..
		$array_nombre=explode("/",$nombre);
		if (sizeof($array_nombre)>1)
			{
			echo "error...createRepeticionRs(nombre,recordset) no se puede usar esta funcion en un bloque de nivel superior a 1";
			}
		else
			{
			$this->elSustituidor->registerSubBloqueRs($array_nombre);
			$this->elSustituidor->Variables[$nombre]=$recordset;
			}	
		}	
	
	function setNumOfRepetitions($nombre,$num)
		{
		$array_nombre=explode("/",$nombre);
		$this->elSustituidor->setNumRepeticionesRec($array_nombre,$num);
		}
	function registerVariableRepeticion($nombreRep,$nombre,$valores)	
		{
		$array_nombre=explode("/",$nombreRep);
		//solo para el primer nivel
		if (sizeof($array_nombre)>1)
			{
			echo "error...registerVariableRepeticion(nombre,nombre,valores) no se puede usar esta funcion en un bloque de nivel superior a 1";
			}
		else
			{
			$this->elSustituidor->registerVariableRepeticion($nombreRep,$nombre,$valores);
			}	
		}
	//--------------------------------------------------------------------------------------
		
	function getXML()
		{
		return ($this->elSustituidor->getXML());
		}		
	
	}

	
	
class Sustituidor
	{
	var $Variables;
	var $skinString;
	var $numRepeticiones;
	var $VariablesArray;
	var $losHijos;
	var $losHijosRs;
	var $losSplitters;
	var $soyBloque;
	var $reIni;
	var $reFin;
	var $nombre;
	
	
	function Sustituidor($reIni,$reFin,$soyBloque,$nombre)
		{
		$this->reIni=$reIni;
		$this->reFin=$reFin;
		$this->numRepeticiones=0;
		$this->Variables=array();
		$this->losHijos=array();
		$this->losSplitters=array();
		$this->losHijosRs=array();
		$this->skinString="";
		$this->VariablesArray=array();
		$this->soyBloque=$soyBloque;
		$this->nombre=$nombre;
		}
	
	function setREGs($reIni,$reFin)
		{
		$this->reIni=$reIni;
		$this->reFin=$reFin;
		while (list ($nombre ,$Sust)=each($this->losHijos))
			{
			$this->losHijos[$nombre]->setREGs($reIni,$reFin);
			
			}
		while (list ($nombre ,$Sust)=each($this->losHijosRs))
			{
			$this->losHijosRs[$nombre]->setREGs($reIni,$reFin);
			}
		
		}
	
	
	function setSkinString($skin)
		{
		
		$this->skinString=$skin;
		}
		
	function registerVariable($nombre,$valor)
		{
		$this->Variables[$nombre]=$valor;
		}
	
	
	function registerVariableRepeticion($nombreRep,$nombre,$valores)	
		{
		$tam=sizeof($valores);
		$scope=$this->Variables[$nombreRep];
		if (!(is_array($scope)))
			{
			$scope=array();
			}
		for ($it=0;$it<$tam;$it++)
			{
			$array_valores=$scope[$it];
			if (!(is_array($array_valores)))
				{
				$array_valores=array();
				}
			$scope[$it]["$nombre"]=$valores[$it];
			}
		$this->Variables[$nombreRep]=$scope;
		}
		
	function registerVariableArray($array_nombre,$array_valor)
		{
		if (sizeof($array_nombre)>0)
			{
			if (sizeof($array_nombre)==1)
				{
				$name=$array_nombre[0];
				$this->VariablesArray[$name]=$array_valor;
				}
			else
				{
				$name=array_shift($array_nombre);
				$this->losHijos[$name]->registerVariableArray($array_nombre,$array_valor);
				}	
			}
		}
		
		

	function registerSubBloque($array_nombre)
		{
		if (sizeof($array_nombre)>0)
			{
			if (sizeof($array_nombre)==1)
				{
				$name=$array_nombre[0];
				$this->losHijos[$name]=new Sustituidor($this->reIni,$this->reFin,1==1,$name);
				}
			else
				{
				$name=array_shift($array_nombre);
				$this->losHijos[$name]->registerSubBloque($array_nombre);
				}	
			}	
		}
	function registerSplitter($array_nombre)
		{
		if (sizeof($array_nombre)>0)
			{
			if (sizeof($array_nombre)==1)
				{
				$name=$array_nombre[0];
				$this->losSplitters[$name]=new Splitter($name);
				}
			else
				{
				$name=array_shift($array_nombre);
				$this->Splitters[$name]->registerSubSplitter($array_nombre);
				}	
			}	
		}
		
		
	function registerSubBloqueRs($array_nombre)
		{
		if (sizeof($array_nombre)>0)
			{
			if (sizeof($array_nombre)==1)
				{
				$name=$array_nombre[0];
				$this->losHijosRs[$name]=new SustituidorRs($name);
				}
			else
				{
				$name=array_shift($array_nombre);
				$this->losHijos[$name]->registerSubBloqueRs($array_nombre);
				}	
			}	
		}
		
	function setNumRepeticiones($num)	
		{
		$this->numRepeticiones=$num;
		}
			
			
	function setNumRepeticionesRec($array_nombre,$num)
		{
		if (sizeof($array_nombre)>0)
			{
			if (sizeof($array_nombre)==1)
				{
				$name=$array_nombre[0];
				$this->losHijos[$name]->setNumRepeticiones($num);
				}
			else
				{
				$name=array_shift($array_nombre);
				$this->losHijos[$name]->setNumRepeticionesRec($array_nombre,$num);
				}	
			}	
		}		
		
	function registerVariables($array_valores)
		{
		//$this->Variables=array_merge_recursive($this->Variables,$array_valores);
		$this->Variables=$array_valores;
		}
		
	function resetVariables()
		{
		$this->Variables=Array();
		}
	function trocearString($string,$nombre)
		{
        $tmp2="";
		
        
        
        $ini_bloque="<bloque nombre=[\"\']?".$nombre."[\"\']?>";
		$fin_bloque="</bloque nombre=[\"\']?".$nombre."[\"\']?>";
        //$ini_bloque='<bloque nombre="' . $nombre . '">';
    	//$fin_bloque='</bloque nombre="' . $nombre . '">';
		
		$patronIni="<bloque nombre=" . $nombre . ">";
		$patronFin="</bloque nombre=" . $nombre . ">";
		
                

		$string=preg_replace($ini_bloque,$patronIni,$string); //1
		$string=preg_replace($fin_bloque,$patronFin,$string);
        
        $string=str_replace("<<bloque","<bloque",$string);
        $string=str_replace($nombre . ">>",$nombre . ">",$string);
        $string=str_replace("</<","</",$string);
        
       
		
		$res=array();
		$tmp=stristr($string,$patronIni);
		if ($tmp===(1==0))
			{
			$res[0]=1==0;
			return($res);
			}
		$posIni=strlen($string)-strlen($tmp);
		$posFin=$posIni+strlen($patronIni);
		$res[1]=substr($string,0,$posIni);
		$tmp=stristr($string,$patronIni);
		if ($tmp2===(1==0))
			{
			$res[0]=1==0;
			return($res);
			}
		
		$tmp2=stristr($tmp,$patronFin);
		$posRelIni2=strlen($tmp)-strlen($tmp2);
		$res[2]=substr($tmp,strlen($patronIni),$posRelIni2-strlen($patronIni));
		//y el trozo del fin...
		$res[3]=substr($tmp2,strlen($patronFin));
		$res[0]=1==1;
		return $res;
		}

	function doSubstitution()
		{
		// empezaremos con nosotros mismos.. somos un bloque?
		if ($this->soyBloque)
			{
			$variables=$this->Variables;
			// s&iacute;, lo somos;
			if ($this->numRepeticiones==0)
				{
				$this->numRepeticiones=sizeof($variables);
				}
			}	
		else
			{
			$variables=array();
			$variables[0]=$this->Variables;
			$this->numRepeticiones=1;
			}
		$resultado="";
		$it=0;
		$salida=$this->skinString;
		while ($it<$this->numRepeticiones)	
			{
			$lasVariables=$variables[$it];
			$salida=$this->skinString;
			//primero mis propios bloques no recordset..
			reset($this->losHijos);

			while (list ($nombre ,$Sust)=each($this->losHijos))
				{
				$ini_bloque="<bloque nombre=[\"\']?".$nombre."[\"\']?>";
				$fin_bloque="</bloque nombre=[\"\']?".$nombre."[\"\']?>";
				$res=$this->trocearString($salida,$nombre);
				if ($res[0])
					{
					$Sust->setSkinString($res[2]);
					$antesdelBloque=$res[1];
					$despuesdelBloque=$res[3];
					// teoricamente $lasVariables[$nombre] es un array [0..N]
					$Sust->registerVariables($lasVariables[$nombre]);			
					$este_resultado=$Sust->doSubstitution();
					$salida=$antesdelBloque.$este_resultado.$despuesdelBloque;
					}
				unset($lasVariables[$nombre]);	
				}
			reset($this->losHijosRs);
			// luego mis bloques recordset;
			while (list ($nombre ,$SustRs)=each($this->losHijosRs))
				{
				// lasVariables[$nombre] debe ser un recordset..
				// y $SustRs un SustituidorRs
				$ini_bloque="<bloque nombre=[\"\']?".$nombre."[\"\']?>";
				$fin_bloque="</bloque nombre=[\"\']?".$nombre."[\"\']?>";
				if (ereg("([^\\]+)".$ini_bloque."([^\\]+)".$fin_bloque."([^\\]+)",$salida,$resultados))
					{
					$antesdelBloque=$resultados[1];
					$despuesdelBloque=$resultados[3];
					$SustRs->setSkinString($resultados[2]);
					$SustRs->setRecordset($lasVariables["$nombre"]);
					$SustRs->setREGs($this->reIni,$this->reFin);
					$este_resultado=$SustRs->doSubstitution();
					$salida=preg_replace($ini_bloque."[^\\]+".$fin_bloque,$este_resultado,$salida);
					//$salida=$antesdelBloque.$este_resultado.$despuesdelBloque;
					}
				$lasVariables[$nombre]->close();	
				unset($lasVariables[$nombre]);
				}		
			// los splitters
			reset($this->losSplitters);
			while (list($nombre,$Splitter)=each($this->losSplitters))
				{
				$Splitter->setSkinString($salida);
				$Splitter->setExpr($lasVariables[$nombre]);
				$salida=$Splitter->doSplit();
				}
			unset($lasVariables[$nombre]);
			//y ahora mis variables propiamente dichas;
			// las variables array,
			reset($this->VariablesArray);
			while (list ($nombre ,$array_valores_array)=each($this->VariablesArray))
				{
				$tamanyo=sizeof($array_valores_array);
				$it_arr=0;
				while ($it_arr<$tamanyo)
					{
					$salida=preg_replace($this->reIni.$nombre."\($it_arr\)".$this->reFin,$array_valores_array[$it_arr],$salida);
					$it_arr++;
					}
				}
			// y por ultimo las variables escalares;
			$lasVariables["iterador"]=$it;
			$lasVariables["iterador+"]=$it+1;
			$Skinner_simple=new simpleSkinner($lasVariables,$salida,$this->reIni,$this->reFin);
			$salida=$Skinner_simple->doSubstitution();
			$resultado.=$salida;
			$it++;
			}
//		echo "<!--".microtime().$this->nombre." Sustituidor: sustitucion hecha! -->\n";	
		return ($resultado);	
		}		
		
		
//////////////////////////////////////////////////////////////////////////////////////		
	function doFastSubstitution()
		{
		// empezaremos con nosotros mismos.. somos un bloque?
		$numRepeticiones=$this->numRepeticiones;
		if ($this->soyBloque)
			{
			$variables=$this->Variables;
			// s&iacute;, lo somos;
			if ($numRepeticiones==0)
				{
				$numRepeticiones=sizeof($variables);
				$this->numRepeticiones=$numRepeticiones;
				}
			}	
		else
			{
			$variables=array();
			$variables[0]=$this->Variables;
			$this->numRepeticiones=1;
			$numRepeticiones=1;
			}
		$resultado="";
		$it=0;
		$salida=$this->skinString;
		while ($it<$numRepeticiones)	
			{
			$lasVariables=$variables[$it];
			$salida=$this->skinString;
			//primero mis propios bloques no recordset..
			reset($this->losHijos);

			while (list ($nombre ,$Sust)=each($this->losHijos))
				{
				$ini_bloque="<bloque nombre=[\"\']?".$nombre."[\"\']?>";
				$fin_bloque="</bloque nombre=[\"\']?".$nombre."[\"\']?>";
				$res=$this->trocearString($salida,$nombre);
				if ($res[0])
					{
					$Sust->setSkinString($res[2]);
					$antesdelBloque=$res[1];
					$despuesdelBloque=$res[3];
					// teoricamente $lasVariables[$nombre] es un array [0..N]
					$Sust->registerVariables($lasVariables[$nombre]);			
					$este_resultado=$Sust->doFastSubstitution();
					$salida=$antesdelBloque.$este_resultado.$despuesdelBloque;
					}
				unset($lasVariables[$nombre]);	
				}
			reset($this->losHijosRs);
			// luego mis bloques recordset;
			while (list ($nombre ,$SustRs)=each($this->losHijosRs))
				{
				// lasVariables[$nombre] debe ser un recordset..
				// y $SustRs un SustituidorRs
				$ini_bloque="<bloque nombre=[\"\']?".$nombre."[\"\']?>";
				$fin_bloque="</bloque nombre=[\"\']?".$nombre."[\"\']?>";
				$res=$this->trocearString($salida,$nombre);
				if ($res[0])
				
				//if (ereg("([^\\]+)".$ini_bloque."([^\\]+)".$fin_bloque."([^\\]+)",$salida,$resultados))
					{
					$antesdelBloque=$res[1];
					$despuesdelBloque=$res[3];
					$SustRs->setSkinString($res[2]);
					$SustRs->setRecordset($lasVariables["$nombre"]);
					$SustRs->setREGs($this->reIni,$this->reFin);
					$este_resultado=$SustRs->doFastSubstitution();
					//$salida=preg_replace($ini_bloque."[^\\]+".$fin_bloque,$este_resultado,$salida);
					$salida=$antesdelBloque.$este_resultado.$despuesdelBloque;
					}
				$lasVariables[$nombre]->close();	
				unset($lasVariables[$nombre]);
				}		
			// los splitters
			reset($this->losSplitters);
			while (list($nombre,$Splitter)=each($this->losSplitters))
				{
				$Splitter->setSkinString($salida);
				$Splitter->setExpr($lasVariables[$nombre]);
				$salida=$Splitter->doSplit();
				unset($lasVariables[$nombre]);
				}
			
			//y ahora mis variables propiamente dichas;
			// las variables array,
			reset($this->VariablesArray);
			while (list ($nombre ,$array_valores_array)=each($this->VariablesArray))
				{
				$tamanyo=sizeof($array_valores_array);
				$it_arr=0;
				while ($it_arr<$tamanyo)
					{
					$salida=preg_replace($this->reIni.$nombre."\($it_arr\)".$this->reFin,$array_valores_array[$it_arr],$salida);
					$it_arr++;
					}
				}
			// y por ultimo las variables escalares;
			$lasVariables["iterador"]=$it;
			$lasVariables["iterador+"]=$it+1;
			$Skinner_simple=new simpleSkinner_FAST($lasVariables,$salida,$this->reIni,$this->reFin);
			$salida=$Skinner_simple->doSubstitution();
			$resultado.=$salida;
			$it++;
			}
		return ($resultado);	
		}		



		
	function getXML()
		{
		// empezaremos con nosotros mismos.. somos un bloque?
		if ($this->soyBloque)
			{
			$variables=$this->Variables;
			// s&iacute;, lo somos;
			if ($this->numRepeticiones==0)
				{
				$this->numRepeticiones=sizeof($variables);
				}
			}	
		else
			{
			$variables=array();
			$variables[0]=$this->Variables;
			$this->numRepeticiones=1;
			}
		$salida="";
		$resultado="";
		$it=0;
		while ($it<$this->numRepeticiones)	
			{
			$salida="";
			$lasVariables=$variables[$it];
			//primero mis propios bloques no recordset..
			reset($this->losHijos);
			while (list ($nombre ,$Sust)=each($this->losHijos))
				{
				$Sust->registerVariables($lasVariables[$nombre]);			
				$este_resultado=$Sust->getXML();
				//echo $este_resultado;
				$salida.=$este_resultado;
				unset($lasVariables[$nombre]);
				}
			reset($this->losHijosRs);
			// luego mis bloques recordset;
			while (list ($nombre ,$SustRs)=each($this->losHijosRs))
				{
				// lasVariables[$nombre] debe ser un recordset..
				// y $SustRs un SustituidorRs
				$SustRs->setRecordset($lasVariables["$nombre"]);
				$este_resultado=$SustRs->getXML();
				$salida.=$este_resultado;
				$lasVariables[$nombre]->close();	
				$lasVariables[$nombre]="";
				}		
				
			// y por ultimo las variables escalares;
			$Skinner_simple=new simpleSkinner($lasVariables,$salida,$this->reIni,$this->reFin);
			$salida.=$Skinner_simple->getXML();
			$nombre=$this->nombre;
			$resultado.="<$nombre>$salida</$nombre>\n";
			$it++;
			}
		return ($resultado);	
		}
		
		
	}	
	
class SustituidorRs
	{
	var $elRecordset;
	var $skinString;
	var $reIni;
	var $reFin;
	var $nombre;
	function SustituidorRs($nombre)
		{
		$this->reIni="\[";
		$this->reFin="\]";
		$this->nombre=$nombre;
		}
	function setREGs($reIni,$reFin)	
		{
		$this->reIni=$reIni;
		$this->reFin=$reFin;
		}
	function setRecordset($rs)
		{
		$this->elRecordset=$rs;
		}
	function setSkinString($skinString)
		{
		$this->skinString=$skinString;
		}
	function doSubstitution()
		{
		$salida="";
		$rs=$this->elRecordset;
		if (get_class($rs)!="recordset")
			{
			echo "SustituidorRs: ERROR...no es un recordset";
			return ("");
			}
		$it=0;
		while ($rs->hasMoreElements())
			{
			$dataRow=$rs->getRow();
			$dataRow["iterador"]=$it;
			$dataRow["iterador+"]=$it+1;
			$Skinner_simple=new simpleSkinner($dataRow,$this->skinString,$this->reIni,$this->reFin);
			$salida.=$Skinner_simple->doSubstitution();
			$rs->moveNext();
			$it++;
			}
		return ($salida);
		}
	function doFastSubstitution()
		{
		$salida="";
		$rs=$this->elRecordset;
		if (get_class($rs)!="recordset")
			{
			echo "SustituidorRs: ERROR...no es un recordset";
			return ("");
			}
		$it=0;
		while ($rs->hasMoreElements())
			{
			$dataRow=$rs->getRow();
			$dataRow["iterador"]=$it;
			$dataRow["iterador+"]=$it+1;
			$Skinner_simple=new simpleSkinner_FAST($dataRow,$this->skinString,$this->reIni,$this->reFin);
			$salida.=$Skinner_simple->doSubstitution();
			$rs->moveNext();
			$it++;
			}
		return ($salida);
		}	
	function getXML()
		{
		$salida="";
		$rs=$this->elRecordset;
		if (get_class($rs)!="recordset")
			{
			echo "SustituidorRs: ERROR...no es un recordset";
			return ("");
			}
		$it=0;
		while ($rs->hasMoreElements())
			{
			$dataRow=$rs->getRow();
			$dataRow["iterador"]=$it;
			$dataRow["iterador+"]=$it+1;
			$Skinner_simple=new simpleSkinner($dataRow,$this->skinString,$this->reIni,$this->reFin);
			$nombre=$this->nombre;
			$salida.="<$nombre>".$Skinner_simple->getXML()."</$nombre>\n";
			$rs->moveNext();
			$it++;
			}
		return ($salida);
		
		}
	
		
	}
	
	
class simpleSkinner
	{
	var $dataRow;
	var $skinString;
	var $reIni;
	var $reFin;
	function simpleSkinner($dataRow,$skinString,$reIni,$reFin)
		{
		$this->dataRow=$dataRow;
		$this->skinString=$skinString;
		$this->reIni=$reIni;
		$this->reFin=$reFin;
		}
	function doSubstitution()
		{
//		echo "<!--".microtime()." simpleSkinner:sustitucion no hecha -->\n";		
		$salida=$this->skinString;
		if ($salida=="")
			{
			return "";
			}
		else
			{
			while (list ($nombre,$valor)=each ($this->dataRow))
				{
				if (is_array($valor))
					{
					}
				else
					{	
					$salida=ereg_replace($this->reIni.$nombre.$this->reFin,"$valor",$salida);
					}
				}
		//	echo "<!--".microtime()." simpleSkinner:sustitucion hecha -->\n";			
			return $salida;
			}	
		}	
		
	function getXML()
		{
		$salida="";
		while (list ($nombre,$valor)=each ($this->dataRow))
			{
			$salida.="<$nombre>".strip_tags($valor)."</$nombre>\n";
			}
				
		return $salida;
		}	
		
		
	}
	
class simpleSkinner_FAST extends simpleSkinner
	{
	function simpleSkinner_FAST($dataRow,$skinString,$reIni,$reFin)
		{
		$this->simpleSkinner($dataRow,$skinString,$reIni,$reFin);
		
		if ($this->reIni=="\[")
			{
			$this->reIni="[";
			}
		if ($this->reFin=="\]")
			{
			$this->reFin="]";
			}
		}

	function doSubstitution()
		{
		$salida=$this->skinString;
		$ini=$this->reIni;
		$fin=$this->reFin;
		// optimizacion 1.. si no encontramos el caracter de sustitucion no hacemos ninguna sustitucion..		
		if (!strstr($salida,$fin))
			{
			return($salida);
			}
		if ($salida=="")
			{
			return "";
			}
		else
			{
			if (is_array($this->dataRow))
				{
				while (list ($nombre,$valor)=each ($this->dataRow))
					{
					if (is_array($valor))
						{
						}
					else
						{	
						$salida=str_replace($ini.$nombre.$fin,"$valor",$salida);
						}
					}
				}
			return $salida;
			}
		}
	
	
	}
/** El Splitador.
   sustituye texto1 {$nombre:contenidoSiValorCierto|SiFalso:$nombre} texto2
   por texto1 contenidoSiValorCierto texto2 ssi valor es cierto
   o por texto1 contenidoSiValorFalso texto2 ssi valor es falso
*/	
class Splitter
	{
	var $valor;
	var $SkinString;
	var $nombre;
	function Splitter($nombre)
		{
		$this->nombre=$nombre;
		}
	function setExpr($bool)	
		{
		$this->valor=$bool;
		}
	function setSkinString($SkinString)	
		{
		$this->SkinString=$SkinString;
		}
	function doSplit()
		{
		/**
		   sustituye texto1 {$nombre:contenidoSiValorCierto|contenidoSiValorFalso:$nombre} texto2
		   por texto1 contenidoSiValorCierto texto2 ssi valor es cierto
		   o por texto1 contenidoSiValorFalso texto2 ssi valor es falso
		   en todas las apariciones de {$nombre:contenidoSiValorCiertoi|contenidoSiValorFalsoi:$nombre}
		   del SkinString
		**/
		$stringSalida=$this->SkinString;
		if ($stringSalida=="")
			{
			
			return("");
			}
		$res=$this->trocearString($stringSalida);
		while ($res[0])
			{
			
			if($this->valor)
				{
				$stringSalida=$res[1].$res[2].$res[4];
				}
			else
				{
				$stringSalida=$res[1].$res[3].$res[4];
				}
			$res=$this->trocearString($stringSalida);
			}
		return ($stringSalida);
		}
		
	function trocearString($string)
		{
		/**
		devuelve $res
		$res[0]=falso si no ha encontrado ninguna ocurrencia de {$nombre:contenidoSiValorCierto|contenidoSiValorFalso:$nombre}
		$res[0]=cierto si ha encontrado alguna ocurrencia
		$res[1]=$texto1
		$res[2]=contenidoSiValorCierto
		$res[3]=contenidoSiValorFalso
		$res[4]=$texto2
		*/
		$marcaInicio="{".$this->nombre.":";
		$marcaEnmedio="|";
		$marcaFin=":".$this->nombre."}";
		$res=Array();
		$temp1=stristr($string,$marcaInicio);
		if ($temp1===(1==0))
			{
			$res[0]=1==0;
			return ($res);
			}
		$pos=strlen($string)-strlen($temp1);
		$res[1]=substr($string,0,$pos);
		// ahora eliminamos el trozo {$nombre:
		$temp1=substr($temp1,strlen($marcaInicio));
		$temp2=stristr($temp1,$marcaEnmedio);
		if ($temp2===(1==0))
			{
			$res[0]=1==0;
			return($res);
			}
		$pos=strlen($temp1)-strlen($temp2);
		$res[2]=substr($temp1,0,$pos);
		$temp2=substr($temp2,strlen($marcaEnmedio));
		$temp3=stristr($temp2,$marcaFin);
		if ($temp3===(1==0))
			{
			$res[0]=1==0;
			return ($res);
			}
		$pos=strlen($temp2)-strlen($temp3);	
		$res[3]=substr($temp2,0,$pos);
		$res[4]=substr($temp3,strlen($marcaFin));
		$res[0]=1==1;
		return $res;
		}
	}
?>
