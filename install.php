<?php
	include 'InstallBddProjet.php' ;
	include 'Donnees.inc.php' ;
	

	// retire les caracteres speciaux d'un chaine de caracteres
  function enleverCaracteresSpeciaux($text) {
	$utf8 = array(
	'/[áàâãªä]/u' => 'a',
	'/[ÁÀÂÃÄ]/u' => 'A',
	'/[ÍÌÎÏ]/u' => 'I',
	'/[íìîï]/u' => 'i',
	'/[éèêë]/u' => 'e',
	'/[ÉÈÊË]/u' => 'E',
	'/[óòôõºö]/u' => 'o',
	'/[ÓÒÔÕÖ]/u' => 'O',
	'/[úùûü]/u' => 'u',
	'/[ÚÙÛÜ]/u' => 'U',
	'/ç/' => 'c',
	'/Ç/' => 'C',
	'/ñ/' => 'n',
	'/;/u' => ' ',
	'/Ñ/' => 'N'
	);
	return preg_replace(array_keys($utf8), array_values($utf8), $text);
	}
	
	function insertionDansTables(){
		$mysqli = creerBase();
		var_dump($Recettes) ;
	   foreach($Recettes as $clef1 => $clef2){  //clef2 = tab( titre, ing, prep, index[])
			$tmp=implode(",",$clef2['index']) ;  // on mets tous les elements du tableau dans une string separée par des ,
			$tmpBon = str_replace("'", " ", $tmp);
			
			// on enleve les ' presents
			$titre = str_replace("'", " ", $clef2['titre']);
			$ingredients = str_replace("'", " ", $clef2['ingredients']);
			$prep = str_replace("'", " ", $clef2['preparation']);
			
			// on enleve les accents
			$titreBon = enleverCaracteresSpeciaux($titre) ;
			$index = enleverCaracteresSpeciaux($tmpBon) ;
			$ingredientsBon = enleverCaracteresSpeciaux($ingredients) ;
			$prepBon = enleverCaracteresSpeciaux($prep) ;
			insertRecette("boisson",$mysqli,$clef1,$titreBon,$ingredientsBon,$prepBon,$index);
	   }
	   
		foreach($Hierarchie as $clef1 => $clef2){  //clef1 = categ clef2 = tab( sousCat[], superCat[])
			if(count($clef2)==2){
				$souCat = implode(",",$clef2['sous-categorie']) ;
				$sousCat2 = str_replace("'", " ", $souCat);
				$sousCatBon = enleverCaracteresSpeciaux($sousCat2) ;
				$superCat = implode(",",$clef2['super-categorie']) ;
			}
			else{
				if(count($clef2['super-categorie'])>1){			// tester si categ = aliment -> afficher juste sous-cat
					$superCat = implode(",",$clef2['super-categorie']) ;
				}
				else{
					$superCat = $clef2['super-categorie'][0] ;
				}
				$sousCatBon = "NULL" ;
			}
			$superCat2 = str_replace("'", " ", $superCat);
			$superCatBon = enleverCaracteresSpeciaux($superCat2) ;
			$categorie = enleverCaracteresSpeciaux($clef1) ; 
			$categorieBon = str_replace("'", " ", $categorie);
			insertIngredient("boisson",$mysqli,$categorieBon,$sousCatBon,$superCatBon);
		}
		$tabSuper = getTabSuperCategorie("boisson", $mysqli) ;
	}
?>
