
<?php

	// Fait les requetes
    function query($link,$requete){
 		$resultat=mysqli_query($link,$requete) or die("$requete :".mysqli_error($link));
 	    return($resultat);

 	}

	// Ajoute un ingredient
 	function insertIngredient($base,$link,$categorie,$souscategorie,$supercategorie){
 		$requete = "USE $base;INSERT INTO Ingredient VALUES( '$categorie', '$souscategorie', '$supercategorie')";
 		
		foreach(explode(';',$requete) as $ingredient) {
			query($link,$ingredient);
		}
 	}

	// Ajoute une recette
 	function insertRecette($base,$link,$id,$titre,$ingredient,$preparation,$index){
 		$requete = "USE $base;INSERT INTO Recette VALUES($id,'$titre','$ingredient','$preparation','$index')";
 		$resultat; //
		foreach(explode(';',$requete) as $recette) {
			$resultat = query($link,$recette);
		}
		
 	}

 	function afficheRecette($base,$link,$id){
 		$requete = "USE $base;SELECT * FROM Recette WHERE id=$id";
 		$resultat;
		foreach(explode(';',$requete) as $recette) {
			$resultat = query($link,$recette);
		}

		return($resultat);
 	}

	//revoie un tableau de recette qui utilise une certaine categorie
	function getTabRecetteCateg($base,$link,$categorie){
 		$res = array();

		$query = "SELECT titre FROM Recette WHERE categorie LIKE $categorie";   
		$result = mysqli_query($link, $query);

		while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
			array_push($res, $row[0]);
		}
		return $res ;
	}
	
	//revoie un tableau de recette qui utilise une certaine sous categorie
	function getTabRecetteSousCateg($base,$link,$sousCategorie){
 		$res = array();

		$query = "SELECT titre FROM Recette WHERE sousCategorie LIKE $sousCategorie";   
		$result = mysqli_query($link, $query);

		while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
			array_push($res, $row[0]);
		}
		return $res ;
	}
	
	
	//revoie un tableau de recette qui utilise une certaine super categorie
	function getTabRecetteSuperCateg($base,$link,$superCategorie){
 		$res = array();

		$query = "SELECT titre FROM Recette WHERE superCategorie LIKE $superCategorie";   
		$result = mysqli_query($link, $query);

		while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
			array_push($res, $row[0]);
		}
		return $res ;
	}
	
	// retoune un tableau composé de toute les super categorie
	function getTabSuperCategorie($base, $link){
		$res = array();

		$query = "SELECT superCategorie FROM Ingredient";
		$result = mysqli_query($link, $query);

		/* Tableau numérique */
		while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
			if(!in_array($row[0], $res)){  //teste si la valeur n'est pas deja contenue dans le tableau
				array_push($res, $row[0]);
			}
		}
		return $res ;
	}
	
	// retoune un tableau composé de toute les  categories
	function getTabCategorie($base, $link, $aliment){
		$res = array();
		//echo "l'aliment passé est :".$aliment."fin" ;
		$query = "SELECT categorie FROM Ingredient WHERE superCategorie LIKE '$aliment'";
		$result = mysqli_query($link, $query);

		/* Tableau numérique */
		while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
			array_push($res, $row[0]);
		}
		return $res ;
	}
	
	// retoune un tableau composé de toute les sous categories
	function getTabSousCategorie($base, $link, $aliment){
		$res = array();

		$query = "SELECT sousCategorie FROM Ingredient WHERE categorie LIKE $aliment";
		$result = mysqli_query($link, $query);

		/* Tableau numérique */
		while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
			array_push($res, $row[0]);
		}
		return $res ;
	}
	
	
 	function afficheTableRecette($base,$link){
 		$requete = "USE $base;SELECT * FROM Recette";
 		$resultat;
		foreach(explode(';',$requete) as $recette) {
			$resultat = query($link,$recette);
		}

		return($resultat);
 	}

	function destruction(){
		mysqli_close($mysqli); 
	}
    function creerBase(){
    $mysqli=mysqli_connect('127.0.0.1', 'root', '') or die("Erreur de connexion");
 	$base="boisson";
 	$Sql="
	 DROP DATABASE IF EXISTS $base;
	 CREATE DATABASE $base;
	 USE $base;
	 CREATE TABLE Recette (id INT PRIMARY KEY, titre VARCHAR(500),ingredients VARCHAR(500), preparation VARCHAR(500),listeCategorie VARCHAR(500));
	 CREATE TABLE Ingredient (categorie VARCHAR(50) PRIMARY KEY,sousCategorie VARCHAR(50),superCategorie VARCHAR(50) NOT NULL)";



	/*Charge la BDD et creer les tables*/
	foreach(explode(';',$Sql) as $Requete) {
		query($mysqli,$Requete);
	}
	return $mysqli ;
	echo "table creer" ;
    
    }

 	


?>
