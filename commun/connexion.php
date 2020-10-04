<?php 
/*
	Section de configuration
*/

	$info = $_SERVER['REQUEST_URI'];
	$info = substr($info,strripos($info,"/")+1);
	$info = str_replace(".php","",str_replace("-"," ",$info));
	$info = strtoupper(substr($info,0,1)).substr($info,1);
	
	$liaison = mysqli_connect("127.0.0.1","root");
	mysqli_select_db($liaison,"pp");	
?>