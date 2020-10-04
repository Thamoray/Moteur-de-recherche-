<!DOCTYPE html>
<html>
	<head>
		<title > Nuage des mots</title>
		<link rel="stylesheet" href="css/style.css" type="text/css"	media="screen">
		<script src="js/tagcloud.jquery.min.js"></script>
		<script type="text/javascript" src="js/tagcloud.jquery.js"></script>
		<style type="text/css">
		background-image: url('bg.jpg');
		</style>
	</head>
	<body style="background-color: #000;">
		
		<?php
		include 'distance_lev.php';
		?>
		<script type="text/javascript">
			var settings = {
			//hauteur de la sphere du nuage
			height: 200,
			//largeure de la sphere
			width: 650,
			//rayon du nuage
			radius: 150,
			//vitesse de rotation
			speed: 1,
			//vitesse de ralentissement
			slower: 0.9,
			timer: 15,
			//dependance de la taille de police en l'axe des Z
			fontMultiplier: 15,
			hoverStyle: {
			border: 'none',
			color: '#0b2e6f'
			},
			mouseOutStyle: {
			border: '',
			color: ''
			}
			};
			
			$(document).ready(function(){
			$('#_tag').tagoSphere(settings);
			});
		</script>
		<?php
			function selectForTag(){
			//connexion et selection du nuage de mots
			error_reporting(E_ALL ^ E_DEPRECATED);
			mysql_connect('127.0.0.1', 'root', '') or die ( "Impossible de se connecter : " . mysql_error () );
				mysql_select_db('pp');
			
			$tab = array();
			$sql ="SELECT mot.mot, doc_mot.poids
					FROM mot
						INNER JOIN doc_mot
						ON mot.id = doc_mot.mot_id
						WHERE doc_mot.poids > 1 ORDER BY RAND()
						DESC LIMIT 100";
			
			$resultat = mysql_query($sql) or die("Impossible de se connecter : " . mysql_error());
			
			
			while ( $row = mysql_fetch_array ( $resultat, MYSQL_ASSOC ) ) {
				$tab [] = $row ['mot'];
			}
			
			$tab = array_flip ( $tab );
			
			return $tab;
			}
			$tab_tag = selectForTag();
			
		?>
		
		<h1 align="center" style="color:#fff;font-family:Castellar;font-size: 300%;">Nuage de mots</h1>
		<p align="center">
			
		</p><br /><br />
		
		<div id ="_tag" style="left: 433.043px;top: 75.5967px;  "  >
			<ul >
				
				<?php //$tab = genererNuage($tab_tag);
				$colors = array("#F0F8FF", "#F0FFFF", "#FFFAFA", "#40E0D0", "#98FB98");
				
					$tab_tag = array_flip($tab_tag);
					foreach ($tab_tag as $tag){
						$tab_colors = array ("#3087F8", "#7F814E", "#EC1E85", "#14E414", "#9EA0AB", "#9EA414");
						$color = rand ( 0, count ( $tab_colors ) - 1 );
						echo '<a  style=" color:' . $tab_colors [$color] . '; " title="Rechercher le tag ' . $tag . '" href="index.php ">'.$tag.'</a>';
						}

				
				?>
			</ul>
		</div><br /><br />
		
		<form action="tags.php" method="post">
			
		</form>
		
	</body>
</html>