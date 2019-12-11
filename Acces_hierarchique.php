
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
	//$tab = getTabSuperCategorie("boisson", $mysqli)
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
		//NE PAS SUPPRIMER
		//echo '$tab = getTabSuperCategorie("boisson", $mysqli)' ;
    /*foreach($Hierarchie as $cle1 => $cat){  //clef -> valeure
        foreach($cat['super-categorie'] as $cle2 => $sous_cat){
            
            if(!is_array($sous_cat)){
					echo 'tab.push("'.$sous_cat.'");';
				
            }
            else{
                foreach($sous_cat as $clef3 => $clef4){

						echo 'tab.push("'.$clef4.'");';
					
                }
            }
        }
    }*/
   ?>;
   
    str="<select name='aliments' onChange='choixCateg()'>" ;
    for(var i = 0 ; i < tab.length ; i++){
            str += "<option value='"+tab[i]+"'>"+tab[i]+" </option>" ;
    }
    str +="</select>" ;
    document.write(str) ;
        
    // Creation de la liste 2 : categorie
    function choixCateg(){
       // var idCateg = document.formulaire.aliments.value ; //recup l'aliment selectionne
        //window.location.href=”index.php?idCateg = document.formulaire.aliments.value";
        var tmp ="";
        var tabCateg = new Array() ;
        
        <?php 
            //$categ = $_GET["idCateg"]; //puts the uid varialbe into $somevar
            $tabTmp = array() ;
            foreach($Hierarchie as $cle1 => $cat){  //clef -> valeure
                    echo 'tabCateg.push("'.$cle1.'");';
                    console.log("Dans le for") ;
			}
        ?>
        
        tmp="<select name='categ' onChange='choixSousCateg()'>" ;
        for(var i = 0 ; i < tabCateg.length ; i++){
            tmp += "<option>"+tabCateg[i]+"</option>" ;
        }
        tmp += "</select>" ;
        document.getElementById('div_categ').innerHTML = tmp ;
    }
    
    
    // Creation de la liste 3 : sous_categorie
    function choixSousCateg(){
    console.log("Dans la sous categorie") ;

    // var idCateg = document.formulaire.aliments.value ; //recup l'aliment selectionne
    //window.location.href=”index.php?idCateg = document.formulaire.aliments.value";
    var tmp ="";
    var tabCateg = new Array() ;
    
    <?php 
        //$categ = $_GET["idCateg"]; //puts the uid varialbe into $somevar
        $tabTmp = array() ;
        foreach($Hierarchie as $cle1 => $cat){  //clef -> valeure
                echo 'tabCateg.push("'.$cle1.'");';
                console.log("Dans le for") ;
        }
    ?>
    
    tmp="<select name='sousCateg' onChange='recetteAssociee(this.value)'>" ;
    for(var i = 0 ; i < tabCateg.length ; i++){
        tmp += "<option>"+tabCateg[i]+"</option>" ;
    }
    tmp += "</select>" ;
    document.getElementById('div_sousCateg').innerHTML = tmp ;
    }
    

    function recetteAssociee(nom){
    console.log("Dans les recettes associée avec nom = "+nom) ;
    var tmp ="";
    var tabRecette = new Array() ;
    <?php
        $tabTmp = array() ;
            foreach($Recettes as $cle1 => $cat){  //clef -> valeure
                foreach($cat['index'] as $clef2 => $clef3){
                    echo 'tabRecette.push("'.$cat['titre'].'");';
                }
        }
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
