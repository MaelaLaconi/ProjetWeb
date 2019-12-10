
<?php
    include 'Donnees.inc.php' ;
    include 'InstallBddProjet.php' ;
	    //require_once('InstallBddProjet.php');

	$mysqli = creerBase();
    //tableau de recette
   /* echo "tableau recette : " ;
    echo "<table border=2>" ;
    echo "<tr>" ;
    echo "<td>id</td>" ;
    echo "<td>titre</td>" ;
    echo "<td>ingredient</td>" ;
    echo "<td>preparation</td>" ;
    echo "<td>index</td>" ;
    echo "</tr>" ;

    foreach($Recettes as $numero => $titre){  //clef -> valeure
        echo "<tr>" ;

        echo "<td> premier".$numero."</td>" ;

        foreach($titre as $v1 => $v2){ 
            if(!is_array($v2)){
				//insertRecette($base,$mysqli,$numero,$titre,$ingredient,$preparation,$index);

                echo "<td> deuxieme".$v2."</td>" ;
            }
            else{
                $tmp=implode(",",$v2) ;
                echo "<td> troisieme".$tmp."</td>" ;
            }
        }
        echo "</tr>" ;
    }
    echo "</table>" ;*/

	//***********************************
	/* 0 => 
  array (
    'titre' => 'Alerte à Malibu (Boisson de la couleurs des fameux maillots de bains... ou presque)',
    'ingredients' => '50 cl de malibu coco|50 cl de gloss cerise|1 l de jus de goyave blanche|1 poignée de griottes',
    'preparation' => 'Mélanger tous les ingrédients ensemble dans un grand pichet. Placer au frais au moins 3 heures avant de déguster. Tchin tchin !!',
    'index' => 
    array (
      0 => 'Malibu',
      1 => 'Cerise',
      2 => 'Jus de goyave',
      3 => 'Cerise griotte',
    ),
  ),*/
	   foreach($Recettes as $clef1 => $clef2){  //clef2 = tab( titre, ing, prep, index[])
			foreach($clef2['index'] as $clef3 => $clef4){
				 insertRecette("boisson",$mysqli,$clef1,$clef2['titre'],$clef2['ingredients'],$clef2['preparation'],$clef4);
			}
	   }
	
	$result = afficheRecette($base,$mysqli,$id);
	//echo $result;
	
	while($row = mysqli_fetch_array($result)){
	    echo $row['id'] . "</br>";
	    echo $row['titre'] . "</br>";
	    echo $row['ingredients'] . "</br>";
	    echo $row['preparation'] . "</br>";
	    echo $row['listeCategorie'] . "</br>";
	}
	//**************************************

    //tableau de hierarchie
    /*echo "<table border=2>" ;
    echo "<tr>" ;
    echo "<td>categorie</td>" ;
    echo "<td>sous_cat</td>" ;
    echo "<td>super_cat</td>" ;
    echo "</tr>" ;
    foreach($Hierarchie as $cle1 => $cat){  //clef -> valeure
        echo "<tr>" ;

        echo "<td> premier :".$cle1."</td>" ;
        foreach($cat as $cle2 => $sous_cat){
            if(!is_array($sous_cat)){
                echo "<td>deuxieme :".$sous_cat."</td>" ;
            }
            else{
                $tmp=implode(",",$sous_cat) ;
                echo "<td> troisieme :".$tmp."</td>" ;
            }
          
        }
        echo "</tr>" ;
    }
    echo "</table>" ;*/
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
