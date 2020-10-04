<!DOCTYPE html>
<html>
<head>
<title>Framework</title>

</head>

<body>

<?php


// fonction pour charger le disctionnaire des mots vides
function chargerDicoMotsVides($fichier) {
	// $tableau_mots_vides = file($fichier) ;
	$lien = fopen ( $fichier, "r" );
	while ( ! feof ( $lien ) ) {
		$tableau_mots_vides [] = trim ( fgets ( $lien, 4096 ) );
	}
	fclose ( $lien );
	
	return $tableau_mots_vides;
}

// Fonction découpage chaine de caratères
function tokenisation($separateurs, $texte) {
	$fichier = "mots-vides.txt";
	
	$chargerDicoMotsVides;
	
	$tableau_mots_vides = chargerDicoMotsVides ( $fichier );
	
	
	$arrayElements = array ();
	// ajouter le tableau des mots vides / charger la liste des mots vides $ array_mot_vide
	$tok = strtok ( $texte, $separateurs );
	
	
	while ( $tok !== false ) {
		
		$tok = strtok ( $separateurs );
		
		// rajouter le test apres le 2 && siMotNonVide($tok)
		if ((strlen ( $tok ) > 2) && ! (in_array ( $tok, $tableau_mots_vides ))) { // apres le 2 inclure le tableau des mots vides /ajouter un && utiliser(key_exist)
			
			$arrayElements [] = $tok;
			
		}
	}
	

	return $arrayElements;
}

// Afficher un tableau
function print_tab($tab_mots) {
	foreach ( $tab_mots as $position => $valeur ) {
		echo $position, " => ", $valeur, "<br>";
	}
}

// Récupération du title
function get_title($html) {
	$modele = '/<title>(.*)<\/title>/i';
	
	preg_match ( $modele, $html, $tab_resultat );
	
	return $tab_resultat [1]; 
}

// Recupération du meta description
function get_description($file_html) {
	$tab_metas = get_meta_tags ( $file_html );
	if (isset ( $tab_metas ['description'] )) {
		return $tab_metas ['description'];
	} else {
		return "";
	}
}

// Recupération du meta keywords
function get_keywords($file_html) {
	$tab_metas = get_meta_tags ( $file_html );
	if (isset ( $tab_metas['keywords'] )) {
		return $tab_metas['keywords'];
		} else {
			return "";
		}
}

// Récupération du title
function get_body($html) {
	$modele = '/<body[^>]*>(.*)<\/body>/is';
	//$modele = '/<body>(.*)<\/body>/is';
	preg_match ( $modele, $html, $tab_resultat );
	
	return $tab_resultat[1]; 
}

function rip_tags($string) {

	// ----- remove HTML TAGs -----
	$string = preg_replace ('/<[^>]*>/', ' ', $string);

	// ----- remove control characters -----
	$string = str_replace("\r", '', $string);    // --- replace with empty space
	$string = str_replace("\n", ' ', $string);   // --- replace with space
	$string = str_replace("\t", ' ', $string);   // --- replace with space

	// ----- remove multiple spaces -----
	$string = trim(preg_replace('/ {2,}/', ' ', $string));

	return $string;

}

// passer des occurrences aux poids des mots
function occurrences2poids($tab_mots_occurrences, $coefficient) {
	$tab_mots_poids = array ();
	foreach ( $tab_mots_occurrences as $mot => $valeur ) {
		$tab_mots_poids [$mot] = $valeur * $coefficient;
	}
	// retourne les mots et les poids
	return $tab_mots_poids;
}

// fusionner les résultats des traitements head et body)
function fusionner_head_body($tab_head, $tab_body) {
	if (count ( $tab_head ) > count ( $tab_body )) {
		// head plus grand que body
		$tab_grand = $tab_head;
		$tab_petit = $tab_body;
	} else {
		// body plus grand que head
		$tab_petit = $tab_head;
		$tab_grand = $tab_body;
	}
	
	foreach ( $tab_petit as $mot => $valeur ) {
		if (array_key_exists ( $mot, $tab_grand )) {
			// si le mot est dans le 2ème
			// additionne les valeurs
			$tab_grand [$mot] += $valeur;
		} else {
			// rajouter l'élément au 2ème tableau
			$tab_grand [$mot] = $valeur;
		}
	}
	return $tab_grand;
}

// conversion des caractere HTML de la chaine $html en ascii
function caractHTML2ASCII($html) {
	 
	// retourner le texte apres conversiondes caracteres html2Ascii
	//return $texteAscii;
	$table_caracts_html = get_html_translation_table( HTML_ENTITIES);
	$tableau_html_caracts = array_flip ( $table_caracts_html );
	$string  = strtr ($html, $tableau_html_caracts );
	
	//$string = utf8_encode($string);
	return $string;
}

// suppression du javascript dans l'html
function strip_javascript($chaine_html) {
	$modele_balises_scripts = '/<[^>]*?script[^>]*?>.*?<\/script>/is';
	
	$html_sans_script = preg_replace ( $modele_balises_scripts, '', $chaine_html );
	
	return $html_sans_script;
}


//strtolower
function strtolower_utf8($chaine) {

	$resultat = utf8_decode($chaine);
	$resultat = strtolower($resultat);
	$resultat = utf8_encode($resultat);
	return $resultat;

}

?>
	

	
	</body>
</html>