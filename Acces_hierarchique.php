
<?php
    include 'Donnees.inc.php' ;

    //tableau de recette
    
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

        echo "<td>".$numero."</td>" ;

        foreach($titre as $v1 => $v2){ 
            if(!is_array($v2)){
                echo "<td>".$v2."</td>" ;
            }
            else{
                $tmp=implode(",",$v2) ;
                echo "<td>".$tmp."</td>" ;
            }
        }
        echo "</tr>" ;
    }
    echo "</table>" ;


    //tableau de hierarchie
    echo "<table border=2>" ;
    echo "<tr>" ;
    echo "<td>categorie</td>" ;
    echo "<td>sous_cat</td>" ;
    echo "<td>super_cat</td>" ;
    echo "</tr>" ;
    
    foreach($Hierarchie as $cle1 => $cat){  //clef -> valeure
        echo "<tr>" ;

        echo "<td>".$cle1."</td>" ;
        foreach($cat as $cle2 => $sous_cat){
            if(!is_array($sous_cat)){
                echo "<td>".$sous_cat."</td>" ;
            }
            else{
                $tmp=implode(",",$sous_cat) ;
                echo "<td>".$tmp."</td>" ;
            }
          
        }
        echo "</tr>" ;
    }
    echo "</table>" ;
?>

<html>
<head>
	<title>Listes déroulantes des aliments</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script type="text/javascript">
        var tab = new Array(Array(),Array("Meuse", "Meurthe-et-Moselle", "Moselle", "Vosges"), Array("Bas-Rhin", "Haut-Rhin")) ;
        function choixRegion(index){
            var str="";
            if (index.localeCompare("0")==0){
                document.getElementById("div_aliment").innerHTML = str;
                return 0;
            }
            
            str="<select name='departement'>"
            for(var i=0; i <tab[index].length; i++){
                str += "<option value='"+i+"'>"+tab[index][i]+" </option>" ;
            }
            str +="</select>" ;
            document.getElementById("div_aliment").innerHTML = str;
        }
        
	</script>
</head>

<body>
<form name="formulaire">
<select name="regions" onChange="choixRegion(this.value);">
	<option value="0"> </option>
	<option value="1">Lorraine</option>
	<option value="2">Alsace</option>
</select>




<div id="div_aliment">
    
</div>
</form>

</body>
</html>
