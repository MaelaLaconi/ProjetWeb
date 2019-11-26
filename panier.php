

<LINK rel=STYLESHEET href="styles.css" type="text/css">


<button class="bouttonAjouter"
        type="button" onclick="incrementePanier();">
        Ajouter aux recettes préférées
</button>

<button class="bouttonSupprimer"
        type="button" onclick="decrementerPanier();">
        Supprimer des recettes préférées
</button>


 <img src="Photos/panier.png" alt="panier" class="imgPanier" />
 <span id="badge" class="badge">0</span>
 
 
 <script>
 
    function incrementePanier(){
        document.getElementById("badge").innerHTML ++;
    }
    
    function decrementerPanier(){
        if(document.getElementById("badge").innerHTML > 0){ 
            document.getElementById("badge").innerHTML --;
        }
    }
        
</script>
