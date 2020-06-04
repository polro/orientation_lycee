<?php
session_start();
include 'connect.php';
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if (isset($_POST['valider_modif'])) {
  	$nom = test_input($_POST['nom']);
	if (preg_match("/^[a-zA-ZéÉèÈëËàÀïÏîÎöÖôÔ\- ]*$/",$nom)) {
		$sql = 'UPDATE voeux_eleves SET Nom="'.$_POST['nom'].'"  WHERE ID="'.$_GET['id'].'"';
  		$result = $conn->query($sql);
  		Header('Location: classe.php');exit;
	} else {
		echo 'Erreur ! Des caractères non autorisés ont été saisis dans le nom.<form action="classe.php"><input type="submit" value="Retour"></form>';
	} 
}

elseif (isset($_POST['valider_ajout'])) {
	$nom = test_input($_POST['nom']);
	if (!preg_match("/^[a-zA-ZéÉèÈëËàÀïÏîÎöÖôÔ\- ]*$/",$nom)) {
		echo 'Erreur ! Des caractères non autorisés ont été saisis dans le nom.<form action="classe.php"><input type="submit" value="Retour"></form>';
	} else {
		$sql = 'INSERT INTO `voeux_eleves` (`ID`, `Nom`, `Classe`, `g_choix_1`, `g_choix_2`, `g_choix_3`, `g_choix_4`, `g_choix_5`, `voeux_g`, `t_choix_1`, `t_choix_2`, `voeux_t`, `a_choix_1`, `voeux_a`, `term_choix_1`, `term_choix_2`, `term_choix_3`, `term_choix_4`, `term_choix_5`, `term_choix_6`) VALUES (NULL, "'.$nom.'", "'.$_SESSION['classe'].'", "", "", "", "", "", "0", "", "", "0", "", "0", "", "", "", "", "", "");';
  		$result = $conn->query($sql);
  		Header('Location:classe.php');exit;
  	}
}

elseif (isset($_POST['valider_multi_ajout'])) {
	$liste = explode(PHP_EOL,$_POST['noms']);
	for ($i = 0; $i < count($liste); $i++) {
		$nom = test_input($liste[$i]);
		if (preg_match("/^[a-zA-ZéÉèÈëËàÀïÏîÎöÖôÔ\- ]*$/",$nom) & $nom <> '') {
			$sql = 'INSERT INTO `voeux_eleves` (`ID`, `Nom`, `Classe`, `g_choix_1`, `g_choix_2`, `g_choix_3`, `g_choix_4`, `g_choix_5`, `voeux_g`, `t_choix_1`, `t_choix_2`, `voeux_t`, `a_choix_1`, `voeux_a`, `term_choix_1`, `term_choix_2`, `term_choix_3`, `term_choix_4`, `term_choix_5`, `term_choix_6`) VALUES (NULL, "'.$nom.'", "'.$_SESSION['classe'].'", "", "", "", "", "", "0", "", "", "0", "", "0", "", "", "", "", "", "");';
  			$result = $conn->query($sql);
  		}
	}
	Header('Location:classe.php');exit;
}

elseif (isset($_POST['valider_supprimer'])) {
	if ($_POST['valider_supprimer'] == 'Oui') {
		$sql = 'DELETE FROM voeux_eleves WHERE ID="'.$_GET['id'].'"';
  		$result = $conn->query($sql);
  	}
  	Header('Location:classe.php');exit;
}

elseif (isset($_SESSION['nom'])) {
  include 'menu.php';

  if (($_GET['option'] == 'modif')) {
  	$sql = 'SELECT Nom FROM voeux_eleves WHERE ID="'.$_GET['id'].'"';
  	$result = $conn->query($sql);
  	$eleve = mysqli_fetch_assoc($result);
  	
  	$classe_selected = array('','','','','','','','','','','','','');
  	switch ($_SESSION['classe']) {
  		case '2A':
  			$classe_selected[0] = ' selected';
  			break;
  		
  		case '2B':
  			$classe_selected[1] = ' selected';
  			break;

  		case '2C':
  			$classe_selected[2] = ' selected';
  			break;

  		case '2D':
  			$classe_selected[3] = ' selected';
  			break;

  		case '2E':
  			$classe_selected[4] = ' selected';
  			break;

  		case '2F':
  			$classe_selected[5] = ' selected';
  			break;

  		case '2G':
  			$classe_selected[6] = ' selected';
  			break;

  		case '2H':
  			$classe_selected[7] = ' selected';
  			break;

  		case '1A':
  			$classe_selected[8] = ' selected';
  			break;

  		case '1B':
  			$classe_selected[9] = ' selected';
  			break;

  		case '1C':
  			$classe_selected[10] = ' selected';
  			break;

  		case '1D':
  			$classe_selected[11] = ' selected';
  			break;

  		case '1E':
  			$classe_selected[12] = ' selected';
  			break;
  	}
	echo '<form autocomplete="off" action="eleves.php?id='.$_GET['id'].'" method="post">
	<table>
	  <tr>
	    <th>NOM Prénom</th>
	    <th>Classe</th>
	    <th></th>
	  </tr>
	  <tr>
	    <td><input type="text" name="nom" value="'.$eleve['Nom'].'" required></td>
	    <td><select name="classe">
	    <option value="2A"'.$classe_selected[0].'>2A
	    <option value="2B"'.$classe_selected[1].'>2B
	    <option value="2C"'.$classe_selected[2].'>2C
	    <option value="2D"'.$classe_selected[3].'>2D
	    <option value="2E"'.$classe_selected[4].'>2E
	    <option value="2F"'.$classe_selected[5].'>2F
	    <option value="2G"'.$classe_selected[6].'>2G
	    <option value="2H"'.$classe_selected[7].'>2H
	    <option value="1A"'.$classe_selected[8].'>1A
	    <option value="1B"'.$classe_selected[9].'>1B
	    <option value="1C"'.$classe_selected[10].'>1C
	    <option value="1D"'.$classe_selected[11].'>1D
	    <option value="1E"'.$classe_selected[12].'>1E</td>
	    <td><input type="submit" name="valider_modif" value="Valider"></td>
	  </tr>
	</table></form>';
  }

  elseif (($_GET['option'] == 'supprimer')) {
	$sql = 'SELECT Nom FROM voeux_eleves WHERE ID="'.$_GET['id'].'"';
  	$result = $conn->query($sql);
  	$eleve = mysqli_fetch_assoc($result);
  	echo '<form action="eleves.php?id='.$_GET['id'].'" method="post"><table><tr><td>Voulez vous vraiment supprimer <strong>'.$eleve['Nom'].'</strong> du lycée ?</td></tr>
  	<tr><td style="text-align: center;"><input type="submit" name="valider_supprimer" value="Oui"> <input type="submit" name="valider_supprimer" value="Non"></td></tr></table></form>';
  }

  elseif (($_GET['option'] == 'ajout')) {
	echo '<form autocomplete="off" action="eleves.php" method="post">
    	<table>
    	  <tr>
    	    <th>NOM Prénom</th>
    	    <th>Classe</th>
    	    <th></th>
    	  </tr>
    	  <tr>
    	    <td><input type="text" name="nom" value="" required></td>
    	    <td>'.$_SESSION['classe'].'</td>
    	    <td><input type="submit" name="valider_ajout" value="Valider"></td>
    	  </tr>
    	</table></form>';
  }

  elseif (($_GET['option'] == 'multi_ajout')) {
	echo '<form autocomplete="off" action="eleves.php" method="post">
			Écrire le NOM Prénom d\'un élève par ligne.
    		<br/><textarea name="noms" value="" rows=30 cols=55 required wrap=hard>PREMIER Élève'.PHP_EOL.'DEUXIÈME Élève</textarea>
    	    <br/><input type="submit" name="valider_multi_ajout" value="Valider"></td>
    	  </form>';
  }

      $conn->close();
    echo '
  </body>
  </html>';
}


else{header('Location:../index.html');}
?>