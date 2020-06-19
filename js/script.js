function generer_password(champ_cible) {
        var ok = "azertyupqsdfghjkmwxcvbn23456789AZERTYUPQSDFGHJKMWXCVBN";
        var pass = "";
        longueur = 8;
        for(i=0;i<longueur;i++){
            var wpos = Math.round(Math.random()*ok.length);
            pass+=ok.substring(wpos,wpos+1);
        }
        document.getElementById(champ_cible).value = pass;
    }

function masquer_afficher() {
  if (document.getElementById("recap").style.display != "none"){
    document.getElementById("recap").style.display = "none";
    document.getElementById("bouton").innerHTML = '<input type="button" value="Afficher récap" onClick="masquer_afficher()">';
  }
  else {
    document.getElementById("recap").style.display = "block";
    document.getElementById("bouton").innerHTML = '<input type="button" value="Marquer récap" onClick="masquer_afficher()">';
  }
}
