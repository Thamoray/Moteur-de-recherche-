


<?php

include 'fonction_utile.php';
include 'insertIntoDB.php';


$dossier = 'files/';
$fichier = basename($_FILES['avatar']['name']);
$taille_maxi = 1000000;
$taille = filesize($_FILES['avatar']['tmp_name']);
$extensions = array('.htm', '.html');
$extension = strrchr($_FILES['avatar']['name'], '.'); 
//Début des vérifications de sécurité...
if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
{
     $erreur = 'Vous devez uploader un fichier de type html';
}
if($taille>$taille_maxi)
{
     $erreur = 'Le fichier est trop gros...';
}
if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
{
     //On formate le nom du fichier ici...
     $fichier = strtr($fichier, 
          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
     $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
     $path=$dossier.$fichier;
     if(move_uploaded_file($_FILES['avatar']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
          indexation($path);
          echo '<script>alert("Upload effectue avec succes !");</script>';
     }
     else //Sinon (la fonction renvoie FALSE).
     {
          echo '<script>alert("Echec de l\'upload !");</script>';
     }
}
else
{
     echo $erreur;
}

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
     // à faire
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
     // à faire
     // Découpage par liste de séparateurs
     //echo $body_texte;
     $tab_mots = tokenisation ( " \n\t\n1234567890/[]|_@&;-$%§-<>,«.»#=:\'\"()!?", $body_texte );
     
     //suppression des résidus s'attachants aux mots
/*   $string_mots = implode(" ", $tab_mots);
     
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
