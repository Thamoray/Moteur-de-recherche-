<?php function supprAccents($string){	return strtr($string,'���������������������������������������������������','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');}function supprSpeciaux($string){	$string = strtr($string,"' @\"\\/#,()","----------");	$string = str_replace("--","-",$string);	return $string;}?>