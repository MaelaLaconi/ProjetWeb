
<?php
    include 'Donnees.inc.php' ;
    include 'InstallBddProjet.php' ;

	$mysqli = creerBase();
  
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
	
?>



<html>
<head>
	<title>Listes déroulantes des aliments</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	
</head>


<body>
<form name="formulaire"> 

<script>
    // Creation de la liste 1 : super_categorie
    var str ="";
    var tab = new Array() ;
    console.log("Dans la fonction javascript") ;
    <?php 
		
		foreach($tabSuper as $cle1 => $clef2){  
				
			if($clef2){   //teste si la chaine n'est pas null
				echo 'tab.push("'.$clef2.'");';
			}
		}
   ?>;
   
    str="<select name='aliments' onChange='choixCateg(this.value); recetteSuperCategorie(this.value)'>" ;
    for(var i = 0 ; i < tab.length ; i++){
            str += "<option value='"+tab[i]+"'>"+tab[i]+" </option>" ;
    }
    str +="</select>" ;
    document.write(str) ;
        
	//utiliser ajax pour passer l'aliment donner dans choixCateg au php	
	/*$.ajax({
			type: 'POST',
			url: 'insert.php',                
			data: {idCateg:idCateg},
			success: function(data) {

			}  
		});*/
		
    // Creation de la liste 2 : categorie
    function choixCateg(aliment){ //aliment est la super categorie
        var tmp ="";
        var tabCateg = new Array() ;
        var idCateg = document.formulaire.aliments.value ; //recup l'aliment selectionne
		console.log(idCateg)  ;
		
        <?php 
			if (isset($_GET['idCateg'])){
				$categ = $_GET['idCateg']; 
			}
			$tabCat = getTabCategorie("boisson", $mysqli, $categ)  ;  //$categ doit etre recup du js
			
            $tabTmp = array() ;
            foreach($tabCat as $cle1 => $clef2){  //clef -> valeure
                    echo 'tabCateg.push("'.$clef2.'");';
			}
        ?>
        
        tmp="<select name='categ' onChange='choixSousCateg(this.value); recetteCategorie(this.value)'>" ;
        for(var i = 0 ; i < tabCateg.length ; i++){
            tmp += "<option>"+tabCateg[i]+"</option>" ;
        }
        tmp += "</select>" ;
        document.getElementById('div_categ').innerHTML = tmp ;
    }
    
    
    
    // Creation de la liste 3 : sous_categorie
    function choixSousCateg(aliment){  //aliment est categorie
    console.log("Dans la sous categorie") ;

    var tmp ="";
    var tabCateg = new Array() ;
    
    <?php 
        /*$categ = $_GET["aliment"];
		$tabCat = getTabSousCategorie("boisson", $mysqli, $categ)  ;

        $tabTmp = array() ;
        foreach($tabCat as $cle1 => $clef2){  //clef -> valeure
                echo 'tabCateg.push("'.$clef2.'");';
        }*/
    ?>
    
    tmp="<select name='sousCateg' onChange='recetteSousCategorie(this.value)'>" ;
    for(var i = 0 ; i < tabCateg.length ; i++){
        tmp += "<option>"+tabCateg[i]+"</option>" ;
    }
    tmp += "</select>" ;
    document.getElementById('div_sousCateg').innerHTML = tmp ;
    }
	
	function recetteSousCategorie(sousCat){
		 var tmp ="";
		var tabRecette = new Array() ;
		<?php
			//$tab = getTabRecetteSousCateg("boisson", $mysqli, sousCat) ;
			$tabTmp = array() ;
				/*foreach($tab as $cle1 => $cat){  //clef -> valeure
					echo 'tabRecette.push("'.$cat.'");';
					
				}*/
		?>
		
		tmp="<select name='recette'>" ;
		for(var i = 0 ; i < tabRecette.length ; i++){
			if (tabRecette[i] == nom){
				tmp += "<option>"+tabRecette[i]+"</option>" ;
			}
		}
		tmp += "</select>" ;
		document.getElementById('div_recette').innerHTML = tmp ;
	}
	
	function recetteSuperCategorie(superCat){
		 var tmp ="";
		var tabRecette = new Array() ;
		<?php
			//$tab = getTabRecetteSuperCateg("boisson", $mysqli, superCat) ;
			$tabTmp = array() ;
				/*foreach($tab as $cle1 => $cat){  //clef -> valeure
					echo 'tabRecette.push("'.$cat.'");';
				}*/
		?>
		
		tmp="<select name='recette'>" ;
		for(var i = 0 ; i < tabRecette.length ; i++){
			if (tabRecette[i] == nom){
				tmp += "<option>"+tabRecette[i]+"</option>" ;
			}
		}
		tmp += "</select>" ;
		document.getElementById('div_recette').innerHTML = tmp ;
	}
	
	function recetteCategorie(categ){
		 var tmp ="";
		var tabRecette = new Array() ;
		<?php
			//$tab = getTabRecetteCateg("boisson", $mysqli, categ) ;
			$tabTmp = array() ;
				echo 'tabRecette.push("'.$cat.'");';
		?>
		
		tmp="<select name='recette'>" ;
		for(var i = 0 ; i < tabRecette.length ; i++){
			if (tabRecette[i] == nom){
				tmp += "<option>"+tabRecette[i]+"</option>" ;
			}
		}
		tmp += "</select>" ;
		document.getElementById('div_recette').innerHTML = tmp ;
	}
    
</script>

<div id="div_categ">
</div>

<div id="div_sousCateg">
</div>

<div id="div_recette">
</div>

</form>
</body>
</html>
