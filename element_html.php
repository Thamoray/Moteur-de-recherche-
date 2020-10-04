<?php

include("insertIntoDB.php");


$chaine_html="files/doc1.html";

$description =html_entity_decode(get_meta_description($chaine_html));

$keywords= get_meta_Keywords($chaine_html);

$chaine = file_get_contents($chaine_html);

$titre= get_title (utf8_decode($chaine));

$body= get_body (utf8_decode($chaine));

$titre = str_replace("?", "e", (utf8_decode(strtolower($titre))));
$description= str_replace("?", "e",$description); 


//****************indexation head*************


$chaine_html_head = strtolower("$titre.$description.$keywords");


$chaine_html_head = html_entity_decode($chaine_html_head);


//****************indexation Body*************


//Extraction du body

$chaine_html_body = strip_scripts($body);

// Traitement de découpage et filtrage des mots : body 

$chaine_html_body = utf8_decode(strtolower(html_entity_decode(strip_tags($chaine_html_body))));





//************les mots_vides*********

$motVide = file_get_contents("mots_vides.txt");
$motVide = strtolower($motVide);
$motVide = utf8_decode($motVide);


//**************Séparateurs***********

$separateur =" .;,:!'?“<>«»()[\"]/\|-{}°0123456789&#=+-*$";

$tab_elements_head = explodebis($separateur ,$chaine_html_head,$motVide);


$tab_elements_body = explodebis($separateur ,$chaine_html_body,$motVide);

$element_count_head = array_count_values($tab_elements_head);

$element_count_body =array_count_values($tab_elements_body);

//*********trier en conservant les associations clé-valeur, utilisez arsort()********************* 

arsort($element_count_head,SORT_NATURAL); 

arsort($element_count_body,SORT_NATURAL); 

echo "********************** Les elements de  Head ***************** <br/> "; 


//print_tab($element_count_head);


echo "********************** Les elements de Body ***************** <br/>"; 


//print_tab($element_count_body);


//*******************Fusion des tables ********************


$fusion = array_merge($element_count_head,$element_count_body);
arsort($fusion,SORT_NATURAL); 
$nuage = array_slice($fusion, 0, 60);
echo '<table width="100%" border ="1" cellspacing="1" cellpadding="1"><tr><td>'.genererNuage($nuage).'<br></td><tr></table>' ;


print_tab($fusion);

//***************************mot clé***********************

//$mm = mots_cles($element_count_body);


//*************************interaction BD *****************

//$bdd= new PDO ("mysql:host=127.0.0.1;dbname=support;charset=utf8","root","");
//$req = $bdd->exec("INSERT INTO formations(id,f_titre,f_motscles) VALUES (1,'".$chaine_html."','".$titre."','".$mm."')");

insererDansDB($fusion, $chaine_html, $titre, $description);




function explodebis( $separateur, $texte,$ban )
  {
      $token = strtok($texte,$separateur);
       if(strlen($token)> 2 && (strpos($ban, $token)===false))
        $tab_elements[] = $token;

      while( $token = strtok($separateur) )
      {
           if(strlen($token)> 2 && (strpos($ban, $token)===false) )
            $tab_elements[] = $token;
      }
      return $tab_elements;
  }

  function print_tab($tab)
  {
      foreach($tab as $key => $value)
        echo "$key = $value <br>";
  }


function get_title($chaine_html){

	$modele = '/<title[^>]*>(.*)<\/title>/is';//i(majuscule ou minscules), s:gére la difference d'espace

	if(preg_match($modele, $chaine_html,$tab_titre)) return utf8_decode($tab_titre[1]) ;//rechercher le modéle dans chaine_html 

	else

		return  print_r("vide");

}
function get_body($chaine_html){

	$modele = '/<body[^>]*>(.*)<\/body>/is';//i(majuscule ou minscules), s:gére la difference d'espace

	if(preg_match($modele, $chaine_html,$tab_titre)) return $tab_titre[1] ;//rechercher le modéle dans chaine_html 

	else

		return  print_r("vide");

}


function get_meta_description($fichier_html)
{

	$tab_meta = get_meta_tags($fichier_html);

	return utf8_decode($tab_meta["description"]);
}

function get_meta_Keywords($fichier_html)
{

	$tab_meta = get_meta_tags($fichier_html);

	return utf8_decode($tab_meta["keywords"]);
}

//***************** recuperer contenu de body **********************

//supprimer les scripts 

function strip_scripts ($fichier_html){




$modele_balises_script ="/<script[^>]*?>.*?<\/script>/is";

$html_sans_script =  preg_replace($modele_balises_script, '', $fichier_html);

return $html_sans_script;

}

//*****************************Nuage de mots**********************

function genererNuage( $data = array() , $minFontSize = 10, $maxFontSize = 36 )
{
$tab_colors=array("#3087F8", "#7F814E", "#EC1E85","#14E414","#9EA0AB",
"#9EA414");

$minimumCount = min( array_values( $data ) );
$maximumCount = max( array_values( $data ) );
$spread = $maximumCount - $minimumCount;
$cloudHTML = '';
$cloudTags = array();
$spread == 0 && $spread = 1;
//Mélanger un tableau de manière aléatoire
srand((float)microtime()*1000000);
$mots = array_keys($data);
shuffle($mots);

foreach( $mots as $tag )
{
$count = $data[$tag];

//La couleur aléatoire
$color=rand(0,count($tab_colors)-1);

$size = $minFontSize + ( $count - $minimumCount )
* ( $maxFontSize - $minFontSize ) / $spread;

$cloudTags[] ='<a style="font-size: '.
floor( $size ) .
'px' .
'; color:' .
$tab_colors[$color].
'; " title="Rechercher le tag ' .
$tag .
'" href="rechercher.php?q=' .
urlencode($tag) .
'">' .
$tag .
'</a>';

}
return join( "\n", $cloudTags ) . "\n";

}



?>