
<?php

	// Fait les requetes
    function query($link,$requete){
 		$resultat=mysqli_query($link,$requete) or die("$requete :".mysqli_error($link));
 	    return($resultat);

 	}

	// Ajoute un ingredient
 	function insertIngredient($base,$link,$categorie,$souscategorie,$supercategorie){
 		$requete = "USE $base;INSERT INTO Ingredient VALUES( $categorie ,$souscategorie,$supercategorie)";
 		
		foreach(explode(';',$requete) as $ingredient) {
			query($link,$ingredient);
		}
 	}

	// Ajoute une recette
 	function insertRecette($base,$link,$id,$titre,$ingredient,$preparation,$index){
		echo "dans l'insert" ;
 		$requete = "USE $base;INSERT INTO Recette VALUES( $id,$titre,$ingredient,$preparation,$index)";
 		$resultat;
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
