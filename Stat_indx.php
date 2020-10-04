<!DOCTYPE html>
<html>
<head>
	<title>Trace d'indexation</title>
	<link rel="stylesheet" href="styles/style.css" type="text/css"	media="screen">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF8">
</head>
<body  style="background-image: url('images/back.jpg');background-position: center;
  background-repeat: no-repeat;background-size: cover;">

	<h2 align="center" style="color:#000;font-family:Castellar;font-size: 300%;">Trace indexation</h2><br /><br /><br /><br />
	<p align="center">
	
		<a href = "index.html" ><input type="submit" value="Indexation" id="menu" style="width:300px; height:70px; background-color:rgb(0, 255, 8); box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);"/></a>
		<a href = "index.php" ><input type="submit" value="Moteur de recherche" id="menu" style="width:300px; height:70px; background-color: rgb(0, 255, 208);  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);"/></a>
		<a href = "Stat_indx.php" ><input type="submit" value="Trace processus" id="menu" style="width:300px; height:70px; background-color: rgb(255, 240, 0);  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);"/></a>
	
	</p>
	
	<P align="center" style="width:300px; height:30px; ">	
		<?php 
		echo '<table align="center">';
		echo '<tr><td id="tab_"><center>DEBUT DU PROCESSUS</center></td><td id="tab_"><center>PATH</center></td></tr>'; 
		?>
	</P>


<?php 

function indexation($source) {
	// ----------------1 : traitement du head------------//
	// Récupération le descriptif
	$description = get_description ( $source );

	// Récupération les mots-clés
	$keywords = get_keywords ( $source );

	// Récupération du title
	$html = file_get_contents ( $source );
	$title = get_title ( $html );

	$texte_head_description_keywords_title = $title . " " . $description . " " . $keywords;

	// conversion du TITLE en ASCII
	$title_to_ascii = caractHTML2ASCII ( $title );

	// Mise en min
	$texte_head_description_keywords_title = strtolower ( $title_to_ascii );

	$tab_mots = tokenisation ( " \n1234567890/[]+\{}/*\|_@-$%§'-<>,«.»=:;\/'\"()!?", $texte_head_description_keywords_title );
	//print_r ( $tab_mots );

	// Calculer le nombre d'occurrences de chaque mot
	$tab_mots_occurrences_head = array_count_values ( $tab_mots );

	// Affiche de la trace du découpage
	// print_tab($tab_mots_occurrences);

	// passer des occurrences aux poids
	$coefficient = 2;
	$tab_mots_poids_head = occurrences2poids ( $tab_mots_occurrences_head, $coefficient );

	// ----------------2 : traitement du body------------//
	// Récupération du body
	$html = file_get_contents ( $source );
	$body_html = get_body ( $html );

	// conversion du BODY en ASCII
	$body_to_ascii = caractHTML2ASCII ( $body_html );
	// suppression du javascript avant le débalisage html

	$body_html = strip_javascript ( $body_to_ascii );

	// Suppression des balises HTML du body
	$body_texte = strip_tags ( $body_html );

	// Mise en min
	$body_texte = strtolower ( $body_texte );
	
	
	// echo $body_texte;
	
	$tab_mots = tokenisation ( " \n1234567890/[]|_@-$%§'-<>,«.»=:\'\"()!?", $body_texte );

	// Calculer le nombre d'occurrences de chaque mot
	$tab_mots_occurrences_body = array_count_values ( $tab_mots );
	// Coefficient = 1 alors pas de calcul
	$tab_mots_poids_body = $tab_mots_occurrences_body;

	// ----------------3 : fusion head et body------------//
	// Fusionner le tableau head avec body, obtenir
	// Un seul tableau mots=>poids pour le document
	
	
	$tab_mots_poids = fusionner_head_body ( $tab_mots_poids_head, $tab_mots_poids_body );

}

?>

<?php

include 'fonction_utile.php';


//Augmentation du temps
//d'exécution de ce script
set_time_limit (500);
$path= "files";
$i = 1;
explorerDir($path);

function explorerDir($path)
{
	$folder = opendir($path);
	while($entree = readdir($folder))
	{
		//On ignore les entrées . ..
		if($entree != "." && $entree != "..")
		{
			// On vérifie si il s'agit d'un répertoire
			if(is_dir($path."/".$entree))
			{
				$sav_path = $path;
				// Construction du path jusqu'au nouveau répertoire
				$path .= "/".$entree;
				//echo "DOSSIER = ", $path, "<BR>";
				// On parcours le nouveau répertoire
				explorerDir($path);
				$path = $sav_path;
			}
			else // C'est un fichier
			{
				//C'est un fichier html ou pas
				$path_source = $path."/".$entree;
				
				if( preg_match("/.htm/i", $path_source) )
				{
					//echo "--DEBUT indexation : $path_source ", "<br>";
					
					echo '<tr>';
					echo '<td><center>'.date ("H:i:s").'</center></td>';
					echo '<td><center>'.$path_source.'</center></td>';
					echo '</tr>';
					//appel au module d'indexation
					indexation($path_source);
					
				}
			}
		}
	}
	closedir($folder);
}
?>

<?php 

echo '<tr><td id="tab_"><center>FIN DU PROCESSUS</center></td><td id="tab_"><center>'.date ("H:i:s").'</center></td></tr>'; 
echo '</table>';
?>

</body>
</html>