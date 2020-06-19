<?php
// Démarrage de la session
session_start();

if (isset($_SESSION['nom'])){

  	include 'connect.php';
  	include 'decrypt_db.php';

  	// On affiche l'entête de la page 
  	include 'menu.php';

  	$liste_classes = '("'.join('","',$_SESSION['liste_classes_premiere']).'");';
	$sql = 'SELECT MAX(modif) as modif FROM voeux_pp WHERE Classe IN '.$liste_classes;
	$req = $conn->query($sql);
	$result = $req->fetch_assoc();
	echo '<div class="noimprim">
    <p>La dernière mise à jour du lycée en première a été faite le '.date_format(date_create($result['modif']),'d/m/Y à H:i').'.</p>';

    // On regarde les vœux manquant et multiple
	$voeux_manquant = array_pad(array(), count($_SESSION['liste_classes_premiere']), 0);
	foreach ($_SESSION['liste_classes_premiere'] as $classe) {
		$sql_voeux = 'SELECT term_choix_1, term_choix_2 FROM voeux_eleves WHERE Classe="'.$classe.'";';
  		$req_voeux = $conn->query($sql_voeux);
  		while($eleve = $req_voeux->fetch_assoc()) {
  			if (($eleve['term_choix_1'] == '') | ($eleve['term_choix_2'] == '')) {
  				$voeux_manquant[array_search($classe, $_SESSION['liste_classes_premiere'])]++;
  			}
  		}
	}

	if (array_sum($voeux_manquant) > 0) {
		echo '<strong>Il manque encore '.array_sum($voeux_manquant).' élève'.(array_sum($voeux_manquant) == 1 ? '' : 's').'</strong> dont les vœux n\'ont pas été saisis. Voici la répartition :</p>
<table>
  <tr>
    <th class="bilan">Classe</th>';
		foreach ($_SESSION['liste_classes_premiere'] as $classe) {
			echo '<th class="bilan">'.$classe.'</th>';
		}

		echo '
  </tr>
  <tr>
    <td class="bilan">Nbr d\'élèves<br/>manquants</td>';

	  	foreach ($_SESSION['liste_classes_premiere'] as $classe) {
			echo '<th class="bilan">'.$voeux_manquant[array_search($classe, $_SESSION['liste_classes_premiere'])].'</th>';
		}

	    echo '
  </tr>
</table>
</div>';
	}

	echo '<form method="post" action="bilan-premiere.php">
  Quelle taille voulez-vous pour les groupes de spécialités : 
  <input type="number" name="groupe_spe" min="10" max="35" step="1" value="'.(isset($_POST['groupe_spe']) ? $_POST['groupe_spe'] : '35').'">
  <br/>
  Quelle taille voulez-vous pour les groupes d\'options :
  <input type="number" name="groupe_opt" min="10" max="35" step="1" value="'.(isset($_POST['groupe_opt']) ? $_POST['groupe_opt'] : '20').'">
  <br/>
  <input type="submit" value="Valider">
</form>';


	//On prépare le tableau
	$nbr_choix = array('HGGSP'=>0, 'HLP'=>0, 'LLCE'=>0, 'Maths'=>0, 'SPC'=>0, 'SVT'=>0, 'SES'=>0, 'Latin-o'=>0, 'HDA'=>0, 'Alleuro'=>0, 'Mathscomp'=>0, 'Mathsexp'=>0, 'DGEMC'=>0, 'STMG'=>0);

	foreach ($conversion_vers_db as $decrypt => $crypt){
	  if (in_array($decrypt,array('HGGSP', 'HLP', 'LLCE', 'Maths', 'SPC', 'SES', 'SVT'))){ //on compte les vœux de spécialités
	      $sql1 = 'SELECT COUNT(term_choix_1) AS choix FROM `voeux_eleves` WHERE term_choix_1="'.$crypt.'"';
	      $req1 = $conn->query($sql1);
	      $result1 = $req1->fetch_assoc();
	      $sql2 = 'SELECT COUNT(term_choix_2) AS choix FROM `voeux_eleves` WHERE term_choix_2="'.$crypt.'"';
	      $req2 = $conn->query($sql2);
	      $result2 = $req2->fetch_assoc();
	      $nbr_choix[$decrypt] = $result1['choix']+$result2['choix'];
	  }//fin if
	  elseif (in_array($decrypt,array('Latin-o', 'HDA', 'Alleuro'))){ //on compte les vœux des options du pack 1
	      $sql1 = 'SELECT COUNT(term_choix_3) AS choix FROM `voeux_eleves` WHERE term_choix_3="'.$crypt.'"';
	      $req1 = $conn->query($sql1);
	      $result1 = $req1->fetch_assoc();
	      $sql2 = 'SELECT COUNT(term_choix_4) AS choix FROM `voeux_eleves` WHERE term_choix_4="'.$crypt.'"';
	      $req2 = $conn->query($sql2);
	      $result2 = $req2->fetch_assoc();
	      $nbr_choix[$decrypt] = $result1['choix']+$result2['choix'];
	  }//fin elseif
	  elseif (in_array($decrypt,array('Mathscomp', 'Mathsexp', 'DGEMC'))){ //on compte les vœux des options du pack 2
	      $sql1 = 'SELECT COUNT(term_choix_5) AS choix FROM `voeux_eleves` WHERE term_choix_5="'.$crypt.'"';
	      $req1 = $conn->query($sql1);
	      $result1 = $req1->fetch_assoc();
	      $nbr_choix[$decrypt] = $result1['choix'];
	  }//fin elseif
	  elseif ($decrypt == 'STMG'){ // on compte le nombre de réorientation
	      $sql1 = 'SELECT COUNT(term_choix_6) AS choix FROM `voeux_eleves` WHERE term_choix_6="'.$crypt.'"';
	      $req1 = $conn->query($sql1);
	      $result1 = $req1->fetch_assoc();
	      $nbr_choix[$decrypt] = $result1['choix'];
	  }//fin elseif
	}//fin foreach


	// Définition de la taille des groupes
	if (isset($_POST['groupe_spe'])){
	  $_SESSION['groupe_spe'] = $_POST['groupe_spe'];
	} //fin if
	else{
	  $_SESSION['groupe_spe'] = 35;
	} //fin else

	if (isset($_POST['groupe_opt'])){
	  $_SESSION['groupe_opt'] = $_POST['groupe_opt'];
	} //fin if
	else{
	  $_SESSION['groupe_opt'] = 20;
	} //fin else

// Création du nombre de groupes
	$nbr_grp = array('HGGSP'=>0, 'HLP'=>0, 'LLCE'=>0, 'Maths'=>0, 'SPC'=>0, 'SES'=>0, 'SVT'=>0,'Latin-o'=>0, 'HDA'=>0, 'Alleuro'=>0,'Mathscomp'=>0, 'Mathsexp'=>0, 'DGEMC'=>0);
	foreach (array('HGGSP', 'HLP', 'LLCE', 'Maths', 'SPC', 'SES', 'SVT') as $spe){
	    if ($nbr_choix[$spe]%$_SESSION['groupe_spe']==0){$nbr_grp[$spe] = (int)($nbr_choix[$spe]/$_SESSION['groupe_spe']);}
	    else{$nbr_grp[$spe] = (int)($nbr_choix[$spe]/$_SESSION['groupe_spe']) + 1;}
	} //fin foreach

	foreach (array('Latin-o', 'HDA', 'Alleuro','Mathscomp', 'Mathsexp','DGEMC') as $opt){
	    if ($nbr_choix[$opt]%$_SESSION['groupe_opt']==0){$nbr_grp[$opt] = (int)($nbr_choix[$opt]/$_SESSION['groupe_opt']);}
	    else{$nbr_grp[$opt] = (int)($nbr_choix[$opt]/$_SESSION['groupe_opt']) + 1;}
	} //fin foreach



// On affiche les tableaux
	echo '
<table class="bilan">
  <caption class="generale">Choix des spécialités</caption>
  <tr>
    <th class="bilan" width=100px>Spécialité</th>
    <th class="bilan" width=100px>Hist.-Géo GSP</th>
    <th class="bilan" width=100px>Humanités LP</th>
    <th class="bilan" width=100px>LLCE</th>
    <th class="bilan" width=100px>Maths</th>
    <th class="bilan" width=100px>Phys.-Ch.</th>
    <th class="bilan" width=100px>S.V.T.</th>
    <th class="bilan" width=100px>S.E.S.</th>
  <tr>
  <tr>
    <td class="bilan">Nombre d\'élèves</td>
    <td class="bilan">'.$nbr_choix['HGGSP'].'</td>
    <td class="bilan">'.$nbr_choix['HLP'].'</td>
    <td class="bilan">'.$nbr_choix['LLCE'].'</td>
    <td class="bilan">'.$nbr_choix['Maths'].'</td>
    <td class="bilan">'.$nbr_choix['SPC'].'</td>
    <td class="bilan">'.$nbr_choix['SVT'].'</td>
    <td class="bilan">'.$nbr_choix['SES'].'</td>
  </tr>
  <tr>
    <td class="bilan">Nombre de groupes<br/>de '.$_SESSION['groupe_spe'].' élèves</td>
    <td class="bilan">'.$nbr_grp['HGGSP'].'</td>
    <td class="bilan">'.$nbr_grp['HLP'].'</td>
    <td class="bilan">'.$nbr_grp['LLCE'].'</td>
    <td class="bilan">'.$nbr_grp['Maths'].'</td>
    <td class="bilan">'.$nbr_grp['SPC'].'</td>
    <td class="bilan">'.$nbr_grp['SVT'].'</td>
    <td class="bilan">'.$nbr_grp['SES'].'</td>
  </tr>
</table>
<table class="bilan">
  <caption>Choix des options</caption>
  <tr>
    <th class="bilan">Option</th>
    <th class="bilan option1" width=100px>Latin</th>
    <th class="bilan option1" width=100px>HDA</th>
    <th class="bilan option1" width=100px>Allemand euro</th>
    <th class="bilan option2" width=100px>Maths comp.</th>
    <th class="bilan option2" width=100px>Maths expertes</th>
    <th class="bilan option2" width=100px>DGEMC</th>
  </tr>
  <tr>
    <td class="bilan">Nombre de vœux</td>
    <td class="bilan">'.$nbr_choix['Latin-o'].'</td>
    <td class="bilan">'.$nbr_choix['HDA'].'</td>
    <td class="bilan">'.$nbr_choix['Alleuro'].'</td>
    <td class="bilan">'.$nbr_choix['Mathscomp'].'</td>
    <td class="bilan">'.$nbr_choix['Mathsexp'].'</td>
    <td class="bilan">'.$nbr_choix['DGEMC'].'</td>
  </tr>
  <tr>
    <td class="bilan">Nombre de groupes<br/>de '.$_SESSION['groupe_opt'].' élèves</td>
    <td class="bilan">'.$nbr_grp['Latin-o'].'</td>
    <td class="bilan">'.$nbr_grp['HDA'].'</td>
    <td class="bilan">'.$nbr_grp['Alleuro'].'</td>
    <td class="bilan">'.$nbr_grp['Mathscomp'].'</td>
    <td class="bilan">'.$nbr_grp['Mathsexp'].'</td>
    <td class="bilan">'.$nbr_grp['DGEMC'].'</td>
  </tr>
</table>
<table class="bilan">
  <caption class="techno">Réorientation</caption>
  <tr>
    <th class="bilan"></th>
    <th class="bilan">STMG</th>
  </tr>
  <tr>
    <td class="bilan">Nombre d\'élèves</td>
    <td class="bilan">'.$nbr_choix['STMG'].'</td>
  </tr>
</table>
<footer class="noimprim"><p>2019-'.date('Y',time()).' - <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="https://licensebuttons.net/l/by-nc-sa/4.0/80x15.png" title="Ce site est mis à disposition selon les termes de la Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International."/></a> -  <a href="https://github.com/polro/orientation_lycee">Romuald Pol</a></p>
</footer>
</body>
</html>';
$conn->close();

}


else
{Header('Location:../index.php');}
?>