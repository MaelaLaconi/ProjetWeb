
<?php

    function query($link,$requete){
 		$resultat=mysqli_query($link,$requete) or die("$requete :".mysqli_error($link));
 	    return($resultat);

 	}


 	function insertIngredient($base,$link,$categorie,$souscategorie,$supercategorie){
 		$requete = "USE $base;INSERT INTO Ingredient VALUES( $categorie ,$souscategorie,$supercategorie)";
 		
		foreach(explode(';',$requete) as $ingredient) {
			query($link,$ingredient);
		}
 	}

 	function insertRecette($base,$link,$id,$titre,$ingredient,$preparation,$index){
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

    function creerBase(){
    
    
    
    
    }

 	$mysqli=mysqli_connect('127.0.0.1', 'root', '') or die("Erreur de connexion");
 	$base="Boisson";
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


	//Test pour insert ingredient
	 $categorie = "'test2'";
	 $souscategorie = "'test'";
	 $supercategorie = "'test'";

	insertIngredient($base,$mysqli,$categorie,$souscategorie,$supercategorie);


	//Test pour insert recette
	 $id = "2";
	 $titre = "'Alerte à Malibu (Boisson de la couleurs des fameux maillots de bains... ou presque)'";
	 $ingredient = "'50 cl de malibu coco|50 cl de gloss cerise|1 l de jus de goyave blanche|1 poignée de griottes'";
	 $preparation = "'Mélanger tous les ingrédients ensemble dans un grand pichet. Placer au frais au moins 3 heures avant de déguster. Tchin tchin !!'";
	 $index ="'Malibu,Cerise,Jus de goyave,Cerise griotte'";
	insertRecette($base,$mysqli,$id,$titre,$ingredient,$preparation,$index);
	

	/*Test affichage d'une recette*/
	$result = afficheRecette($base,$mysqli,$id);
	//echo $result;
	
	while($row = mysqli_fetch_array($result)){
	    echo $row['id'] . "</br>";
	    echo $row['titre'] . "</br>";
	    echo $row['ingredients'] . "</br>";
	    echo $row['preparation'] . "</br>";
	    echo $row['listeCategorie'] . "</br>";
	}

	mysqli_close($mysqli); 

?>
