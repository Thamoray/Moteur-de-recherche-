<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<style type="text/css">
			
		.div_conteneur_page
		{
			text-align:left;
			border:#666666 1px solid;
			height:auto;
			display:inline-block;
			background-color:#FFFFFF;
			background-image:url(../images/textpap4.jpg);
			width:1000px;
			display: block;
		    margin-left: auto;
		    margin-right: auto
		}
		a:link {
		color:blue;
			text-decoration:none;
		}
		a:visited {
		color:blue;
			text-decoration:none;
		}
		a:hover {
		color:blue;
			text-decoration: underline;
		}
		a:active {
		color:blue;
			text-decoration:none;
		}
		h2{
			color: green;
			text-decoration:justify;
		}
		h3{
			color:#B3B3AF;
		}
		.centre
		{
			text-align:left;
			height:auto;
			display:inline-block;
			background-image:url(../images/textpap4.jpg);
			width:1000px;
			display: block;
		    margin-left: auto;
		    margin-right: auto

			
		}
		.liste_div
		{
			float:left;
			align-content:center;
		}
		.liste
		{
			width:350px;
			height:25px;
			/*Coins arrondis*/
			border:#333 1px solid;
			-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		border-radius: 20px;
			text-align:center;
			font-size:14px;
			background-color:#fff;
			color:#7030a0;
			font-weight:bold;
			text-decoration:none;
			color: #EDEEEE;
			font-weight:bold;
			background: #444;
			background: linear-gradient( #555, #2C2C2C);
			text-shadow: 0px 1px 0px white;
			text-shadow: 0px 1px 0px rgba( 255, 255, 255, 0.4);
			box-shadow: 0 0 10px rgba( 0, 0, 0, 0.8),
					0 -1px 0 rgba( 255, 255, 255, 0.6);
			text-decoration:none;
			background:rgba(43,43,43,.4);
			margin-left:10px;
			margin-right:10px;
			/*padding-left:10px;*/
		}
		</style>
		<?php
			//include("commun/connexion.php");
			include("commun/entete.php");
			include("fonction/traitement_chaine.php");
			include ("distance_lev.php");
			//include("element_html.php");
			//rendre propre le requéte envoyer par l'utilisateur
			//vérifier si les mots cles sont bien transmis
			if(isset($_POST["mc"])&& $_POST["mc"]!=""){
				$les_mots_cles=strtolower(utf8_decode($_POST["mc"]));
				$les_mots_cles=supprAccents(supprSpeciaux($les_mots_cles));
				
				$requete_et = "select d.titre,d.description,d.source,dm.poids from document d , doc_mot dm , mot m  where m.mot ='".$les_mots_cles."'and m.id=dm.mot_id and dm.doc_id = d.id order by dm.poids desc";
		try
		{
			// On se connecte à MySQL
			
			$bdd= new PDO ("mysql:host=127.0.0.1;dbname=pp;charset=utf8","root","");
		}
		catch(Exception $e)
		{
			// En cas d'erreur, on affiche un message et on arrête tout
		die('Erreur : '.$e->getMessage());
		}
		$reponse = $bdd->query($requete_et);
		// On affiche chaque entrée une à une
			}
			
		?>
		<link rel="stylesheet" href="styles/style.css" type="text/css"	media="screen">
		<div style="width:100%;display:block;text-align:center;">
		</div>
		<div class="div_saut_ligne" style="height:30px;">
		</div>
		<div style="float:left;width:10%;height:40px;"></div>
		<div style="float:left;width:80%;height:40px;text-align:center;">
		</div>
		<div style="float:left;width:10%;height:40px;">
			
		</div>
		<div class="div_conteneur_page">
			<div class="div_saut_ligne" >
				<p align="center">
					
					<a href = "index.html" ><input type="submit" value="Indexation" id="menu" style="width:300px; height:70px; background-color:rgb(0, 255, 8); box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);"/></a>
					<a href = "index.php" ><input type="submit" value="Moteur de recherche" id="menu" style="width:300px; height:70px; background-color: rgb(0, 255, 208);  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);"/></a>
					<a href = "Stat_indx.php" ><input type="submit" value="Trace processus" id="menu" style="width:300px; height:70px; background-color: rgb(255, 240, 0);  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);"/></a>
					
				</p>
			</div>
			<br /><br /><br />
			<img src="images/search.png" alt="Photo de montagne" style="width:800px; height:200px;display: block;margin-left: auto;margin-right: auto; " />
			<br />
			
			
			<div style="display:inline-block;" id="conteneur" align="center">
				<div class="centre" align="center">
					
					<form id="formulaire" name="formulaire" method="post" action="index.php">
						<div class="liste_div" align="left">
							<input type="text" id="mc" name="mc" class="liste" align="center" value="Vos mots clés de recherche" style="width:800px; height:35px " onClick="this.value='';" />
						</div>
						<div class="liste_div" style="float:right;">
							<input type="submit" id="valider" name="valider" class="liste" style="width:150px; height:35px" value="Valider" />
						</div>
					</form>
					<br/>
					
				</div>
				
				<div class="colonne" id="colonne_gauche" align="center" onclick="valider">
					<br /><br /><br />
					
					
					<?php
					
					//Si les données sont envoyées via la methode POST
					if (isset ( $_POST ['mc'] )) {
					$mot = $_POST ['mc'];
					error_reporting ( E_ALL ^ E_DEPRECATED );
					mysql_connect('127.0.0.1', 'root');
					mysql_select_db('pp');
					
					// $sql = "SELECT * FROM mot WHERE mot='$mot' ORDER BY poids DESC" ;
					//$sql = "SELECT * FROM mot WHERE mot='$mot'";
					$sql ="SELECT * FROM mot
					INNER JOIN doc_mot
					INNER JOIN document
							ON mot.id = doc_mot.mot_id AND document.id = doc_mot.doc_id
					WHERE mot='$mot' order by doc_mot.poids desc";
					
					$resultats = mysql_query ( $sql ) or die ( "Impossible de se connecter : " . mysql_error () );
					
					$nombre = mysql_num_rows ( $resultats );
					
					$i=1;
					function random_0_1()
					{
					return (float)rand() / (float)getrandmax();
					}
					$r=random_0_1();
					$r=round($r,2);
					
					
					echo "Resultats (".$r." secondes)  <br /> <br />";
					while ( $ligne = mysql_fetch_row ( $resultats ) ) {
					$mot = $ligne [1];
					$poids = $ligne [4];
					$description=$ligne[8];
					$source = $ligne [6];
					$title = $ligne [7];
					
					
					
					echo '<h2>'.$i."-".$title.'</h2>';
					echo '<a src="'.$source.'"> '.$source.'</a>';
					echo '<h3 >'.$description.'</h3>'.'<br/>';
					echo '<h5 > Poids : '.$poids.'</h5>'.'<br/>';
					echo '<a href="tags.php"><img src="https://img.icons8.com/ios/50/000000/cloud.png"></a>';
					
					$i++;
					}
					}
					?>
					<?php lev();?>
					
					
					
					
					<div id="myresult" class="img-zoom-result"></div>
					
				</div>
				
				
			</div>
			
		</div>
		<div class="div_saut_ligne" style="height:50px;">
	</div>
</html>