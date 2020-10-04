<!DOCTYPE html>
<html>
<head>

<title>Insertion Database</title>
</head>
<body>
<?php
//PARTIE INDEXATION SEPAREE AVEC LE MODULE DE RECHERCHE 



function insererDansDB($tableau_mots_poids, $source, $title, $description){
	
	$source		 = addslashes(utf8_decode(html_entity_decode($source)));
	$title 		 = addslashes(utf8_decode(html_entity_decode($title)));
	$description = addslashes(utf8_decode(html_entity_decode($description)));
	

//ecrire les resultats de l'indexation dans la base de donn�es 
error_reporting(E_ALL ^ E_DEPRECATED);

mysql_connect('127.0.0.1', 'root');
mysql_select_db('pp');

$sql1 = "INSERT INTO document (id, source, titre, description) VALUES (null,'$source', '$title' , '$description')";
mysql_query($sql1)  or die("Impossible de se connecter : " . mysql_error());
//r�cup�rqation de l'id document automatiquement 
$id_document = mysql_insert_id();

//on parcours la liste des mots et on fait le lien avec l'id document 

foreach($tableau_mots_poids as $mot => $poids){	
	
	//Au lieu de mettre mot(UNIQUE) , la creation de la table 
	//verifier si le mot existe ou pas dans la table mot et on recupere son id 
	$sql2bis = "SELECT * FROM mot WHERE mot ='$mot'";
	$resultat = mysql_query($sql2bis) or die("Impossible de se connecter : " . mysql_error());
	
	if(mysql_num_rows($resultat) == 1){
		$ligne = mysql_fetch_row($resultat);
		$id_mot = $ligne[0];
	}else{
	
		$sql2 = "INSERT INTO mot (id, mot) VALUES (null, '$mot')";
		mysql_query($sql2) or die("Impossible de se connecter : " . mysql_error());
		//r�cup�ration de l'id mot automatiquement 
	//	$id_document = mysql_insert_id();
		$id_mot = mysql_insert_id();
		
	}

		//lien entre document et mot et son poids
		$sql3 = "INSERT INTO doc_mot (doc_id, mot_id, poids) VALUES ($id_document, $id_mot, $poids)";
		mysql_query($sql3) or die("Impossible de se connecter : " . mysql_error());
		
	}

/*
3==> fin de l'ecriture des r�sultats de l'ind�xation en cours 

*/
//je l'ai ajout� 
mysql_close();

}
?>
</body>
</html>
