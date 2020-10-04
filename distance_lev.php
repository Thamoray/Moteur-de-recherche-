<!DOCTYPE html>
<html>
<head>

<title>Distance levenshtein</title>
<link rel="stylesheet" href="css/style.css" type="text/css"	media="screen">
</head>
<body>
<?php
// mot mal orthographié

function lev(){
	
	if(isset($_POST['mc'])){
		$input = $_POST['mc'];
		
		error_reporting ( E_ALL ^ E_DEPRECATED );
		mysql_connect('127.0.0.1', 'root');
        mysql_select_db('mrdb');
		//mysql_connect ( 'localhost', 'root', '' );
		//mysql_select_db ( 'bdd' );
		
		$sql ="SELECT mot FROM mot";
		
		// aucune distance de trouvée pour le moment
		$shortest = -1;
		
		$resultats = mysql_query ( $sql ) or die ( "Impossible de se connecter : " . mysql_error () );
		
	//	$nombre = mysql_num_rows ( $resultats );
		
		$lignes = array();
		while ( $ligne = mysql_fetch_array ( $resultats ) ) {			
			$mot = $ligne [0];
			
			$lev = levenshtein($input, $mot);
			// cherche une correspondance exacte
			if ($lev == 0) {
			
				// le mot le plus près est celui-ci (correspondance exacte)
				$closest = $mot;
				$shortest = 0;
			
				// on sort de la boucle ; nous avons trouvé une correspondance exacte
				break;
			}

			// Si la distance est plus petite que la prochaine distance trouvée
			// OU, si le prochain mot le plus près n'a pas encore été trouvé
			if ($lev <= $shortest || $shortest < 0) {
				// définition du mot le plus près ainsi que la distance
				$closest  = $mot;
				$shortest = $lev;
				
				}
			}
			
			echo '<div align = "center">';
			
			if ($shortest == 0) {
				echo '';
			} else {
				//echo '<p id="distance">Mot saisi :' .$input.'</p>';
				//echo '<p id="distance">Essayez avec cette orthographe  :' .$closest.' ?</p>';
				echo '<p align="left" id="distance">Essayez avec cette orthographe :<a href= "index.php?mc='.$closest.'" >'.$closest. ' ?</a></p>';
			}
			
			echo "</div>";
		}
	}

?>
</body>
</html>