<!DOCTYPE html>
<html>
<head>
<title>Indexation</title>
<meta charset="UTF-8">
<link rel="stylesheet" href="css/style.css" type="text/css"	media="screen">
</head>
<body>


	
	<!-- En-tête -->
	
	
	
	</p><br /><br />
	

				
<?PHP
// Bibliotheque des fonctions
include 'fonction_utile.php';
include 'insertIntoDB.php';


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
								
						
							//appel au module d'indexation
							indexation($path_source);
								
						}
					}
				}
			}
			closedir($folder);
		}
		
	
// processus de traitement det d'indexation d'une source html
function indexation($source) {
	// ----------------1 : traitement du head------------//
	// Récupération le descriptif
	$description = get_description ( $source );
	
	// Récupération les mots-clés
	$keywords = get_keywords ( $source );
	
	// Récupération du title
	$html = file_get_contents ( $source );
	$html = utf8_encode($html);
	
	$title = get_title ( $html );
	
	
	$texte_head_description_keywords_title = $title . " " . $description . " " . $keywords;
	
	$title = preg_replace("/&nbsp;/", '', $title);
	// conversion du TITLE en ASCII
	$title_to_ascii = caractHTML2ASCII ( $title );
	
	// Mise en min
	$texte_head_description_keywords_title = strtolower_utf8 ( $title_to_ascii );
	$texte_head_description_keywords_title = utf8_decode($texte_head_description_keywords_title);
	//on calcule le nombre de mots du head 
	$sommeHead = str_word_count($texte_head_description_keywords_title);
	// Faire une indexation du head du HTML = description + keywords + titre
	
	// Découpage par liste de séparateurs
		
	$tab_mots = tokenisation ( " \n1234567890/[]|_@-$%&;§-<>,«.»#=:\'\"()!?", $texte_head_description_keywords_title );
	//print_r ( $tab_mots );
		
	// Affiche de la trace du découpage
	// print_tab($tab_mots);
	
	// Calculer le nombre d'occurrences de chaque mot
	$tab_mots_occurrences_head = array_count_values ( $tab_mots );
	
	// Affiche de la trace du découpage
	// print_tab($tab_mots_occurrences);
	
	// passer des occurrences aux poids
	$coefficient = 1.5;
	$tab_mots_poids_head = occurrences2poids ( $tab_mots_occurrences_head, $coefficient );
	
	// ----------------2 : traitement du body------------//
	// Récupération du body
	$html = file_get_contents ( $source );
	$html = utf8_encode($html);
	$body_html = get_body ( $html );
	
	
	
	// conversion du BODY en ASCII
	$body_html = preg_replace("/&nbsp;/", '', $body_html);
	$body_to_ascii = caractHTML2ASCII ( $body_html );
	/*$body_to_ascii = caractHTML2ASCII ( $html );*/
	// suppression du javascript avant le débalisage html
	//echo $body_to_ascii;
	$body_html = strip_javascript ( $body_to_ascii );
    
	// Suppression des balises HTML du body
	$body_texte = rip_tags ( $body_html );
	
	// Mise en min
	/*
	$body_texte = utf8_decode($body_texte);
	$body_texte = strtolower ( $body_texte );
	$body_texte = utf8_encode($body_texte);
	*/
	$body_texte = strtolower_utf8( $body_texte );
	
	$body_texte = utf8_decode($body_texte);
	//$body_texte = mb_strtolower($body_texte,'UTF-8');
	
	$sommeBody = str_word_count($body_texte);
	
	// Faire une indexation du head du HTML = description + keywords + titre
	
	// Découpage par liste de séparateurs
	//echo $body_texte;
	$tab_mots = tokenisation ( " \n\t\n1234567890/[]|_@&;-$%§-<>,«.»#=:\'\"()!?", $body_texte );
	
	//suppression des résidus s'attachants aux mots
/*	$string_mots = implode(" ", $tab_mots);
	
	$string_mots = preg_replace('/;/', '', $string_mots);
	
	$tab_mots = explode(" ", $string_mots);
*/	
	
	// Affiche de la trace du découpage
	//print_r ( $tab_mots );
	
	// Calculer le nombre d'occurrences de chaque mot
	$tab_mots_occurrences_body = array_count_values ( $tab_mots );
	// Coefficient = 1 alors pas de calcul
	$tab_mots_poids_body = $tab_mots_occurrences_body;
	
	// ----------------3 : fusion head et body------------//
	// Fusionner le tableau head avec body, obtenir
	// Un seul tableau mots=>poids pour le document
	$tab_mots_poids = fusionner_head_body ( $tab_mots_poids_head, $tab_mots_poids_body );

	//print_r($tab_mots_poids);
	//calculer le processus 
	
	$processus = (count($tab_mots_poids)/($sommeHead + $sommeBody))*100;

	$processus = number_format($processus, 0, '.', '');
		
	// --------partie 4 mise des resultats dans une base de données-------//
	
	insererDansDB ( $tab_mots_poids, $source, $title, $description );
	
	

	
}

?>
</body>
</html>