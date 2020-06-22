<?php
// Démarrage de la session
session_start();

//fonction pour réécrire correctement les champs de saisi.
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

//On affiche tous les comptes
if ($_SESSION['acces'] == '1'){
  include 'menu.php';

  /*if (($_GET['option'] <> 'modifier') & ($_GET['option'] <> 'ajouter') & !isset($_POST['valider_modif'])) {*/

$liste_classes = '("'.join('","',$_SESSION['liste_classes_seconde']).'","'.join('","',$_SESSION['liste_classes_premiere']).'")';

/* On affiche la liste des comptes spéciaux */
  $sql = 'SELECT Classe, Nom FROM voeux_pp WHERE Classe NOT IN '.$liste_classes.' ORDER BY Classe ASC';
  $req = $conn->query($sql);
  echo '
  <style>td{text-align: center;height: 30px;vertical-align: center;}</style>
	<div style="display: flex; justify-content: space-around;">
  		<div>
    		<table style="margin-bottom: 30px;">
    			<caption>Comptes spéciaux</caption>
			    <tr>
			      <th style="width: 250px;">Nom du compte</th>
			      <th style="width: 90px;">Envoyer mail</th>
			      <th style="width: 90px;">Modifier</th>
			    </tr>';
    if ($req->num_rows > 0) {
        while($row = $req->fetch_assoc()) {
          	echo '
			    <tr>
			      <td>'.$row['Nom'].'</td>
			      <td>';
    		if ($row['Classe'] <> 'AD') {
    			echo '<a href="gestion_prof.php?option=mail&classe='.$row['Classe'].'"><img src="../images/mail.png" alt="" title="Envoyer les identifiants par mail à '.$row['Nom'].'" width=25px height=25px></a>';}
    		echo '</td>
    		      <td><a href="gestion_prof.php?option=modifier&classe='.$row['Classe'].'"><img src="../images/modifier.jpeg" alt="" title="Modifier le compte '.(strpbrk("aàäeéèêëiïîoöôuù",strtolower(substr($row['Nom'],0,1))) <> '' ? 'd\'' : 'de ').$row['Nom'].'" width=25px height=25px></a></td>
  			
  		    	</tr>';
        }
    }
   echo '
    		</table>
    ';

/* On affiche la liste des comptes profs */
  $sql = 'SELECT Classe, Nom FROM voeux_pp WHERE Classe IN '.$liste_classes.' ORDER BY Classe ASC';
  $req = $conn->query($sql);
  echo '
  			<table>
  				<caption>Comptes des professeurs principaux</caption>
			    <tr>
			      <th style="width: 80px;">Classe</th>
			      <th style="width: 250px;">Professeur principal</th>
			      <th style="width: 90px;">Envoyer mail</th>
			      <th style="width: 90px;">Modifier</th>
			      <th style="width: 90px;">Supprimer</th>
			    </tr>';
    if ($req->num_rows > 0) {
        while($row = $req->fetch_assoc()) {
          	echo '
  				<tr>
				    <td>'.$row['Classe'].'</td>
				    <td>'.$row['Nom'].'</td>
				    <td><a href="gestion_prof.php?option=mail&classe='.$row['Classe'].'"><img src="../images/mail.png" alt="" title="Envoyer les identifiants par mail à '.$row['Nom'].'" width=25px height=25px></a></td>
				    <td><a href="gestion_prof.php?option=modifier&classe='.$row['Classe'].'"><img src="../images/modifier.jpeg" alt="" title="Modifier le compte '.(strpbrk("aàäeéèêëiïîoöôuù",strtolower(substr($row['Nom'],0,1))) <> '' ? 'd\'' : 'de ').$row['Nom'].'" width=25px height=25px></a></td>
				    <td>'.(in_array($row['Classe'], array('AD','DI')) ? '' : '<a href="gestion_prof.php?option=supprimer&classe='.$row['Classe'].'" title="Supprimer la classe de '.$row['Classe'].'"><img src="../images/supprimer.jpeg" alt="Supprime la classe de '.$row['Classe'].'" width=25px height=25px></a>').'</td>
  				</tr>';
        }
    }
    echo '
  			</table>
			<a href="gestion_prof.php?option=ajouter" title="Ajouter une classe"><img src="../images/ajouter.png" alt="Ajouter une classe" width=25px height=25px></a>
		</div>
		<div style="width: 500px;">';

/* Pour envoyer des mail de connexion */
	if ($_GET['option'] == 'mail') {
		if ($_GET['classe'] == 'CO') {
			echo '
			<form autocomplete="off" method="post" action="gestion_prof.php">
			Donnez le nom et le mail du professeur qui recevra les identifiants du compte visiteur.
			<br/>Nom : <input name="nom" value="">
	    	<br/>Email : <input name="email" value=""><br/>
	    	<input type="submit" name="mail_visiteur" value="Valider">
	    	</form>';
		}
		else {
			$sql = 'SELECT Nom, mail FROM voeux_pp WHERE Classe="'.$_GET['classe'].'"';
    		$req = $conn->query($sql);
    		$prof = mysqli_fetch_assoc($req);
			echo '
			<form method="post" action="gestion_prof.php?mail='.$_GET['classe'].'">
			Confirmez-vous l\'envoi du mail à '.$prof['Nom'].' sur l\'adresse '.$prof['mail'].' ?
			<br/>
			<input type="submit" name="confirm_mail" value="Oui">
			<input type="submit" name="no_confirm_mail" value="Non">
			</form>';
		}
	}
  	

    /* Pour supprimer une classe de la base de données */
	if ($_GET['option'] == 'supprimer') {
		echo '
			<form method="post" action="gestion_prof.php?classe='.$_GET['classe'].'">
			Confirmez-vous la suppression de la classe de '.$_GET['classe'].' ?
			<br/><input type="submit" name="confirm_suppr" value="Oui">
			<input type="submit" name="no_confirm_suppr" value="Non">
			</form>';
	}

	if (isset($_POST['confirm_suppr'])) {
		$sql = 'DELETE FROM voeux_pp WHERE Classe="'.$_GET['classe'].'"';
		if ($conn->query($sql)) {
			echo '
			<h3>La classe de '.$_GET['classe'].' a bien été supprimée. <a href="gestion_prof.php">Cliquez ici</a> pour voir le résultat.</h3>';
			// Mettre à jour la liste des classes
			if (in_array($_GET['classe'],$_SESSION['liste_classes_seconde'])) {
				unset($_SESSION['liste_classes_seconde'][array_search($_GET['classe'], $_SESSION['liste_classes_seconde'])]);
				$_SESSION['liste_classes_seconde'] = array_values($_SESSION['liste_classes_seconde']);
			} elseif (in_array($_GET['classe'],$_SESSION['liste_classes_premiere'])) {
				unset($_SESSION['liste_classes_premiere'][array_search($_GET['classe'], $_SESSION['liste_classes_premiere'])]);
				$_SESSION['liste_classes_premiere'] = array_values($_SESSION['liste_classes_premiere']);
			}
		$sql = 'DELETE FROM voeux_eleves WHERE Classe="'.$_GET['classe'].'"';
		if ($conn->query($sql)) {}

		} else {
			echo '
			<h3>Il y a eu un problème dans la suppresion de la classe de '.$_GET['classe'].'.</h3>';
		}
	}

//Envoie d'email
    if (isset($_POST['confirm_mail'])) {
    	//On retrouve l'admin pour le mail
    	$sql = 'SELECT Nom, mail FROM voeux_pp WHERE Classe="AD"';
    	$req = $conn->query($sql);
    	$admin = mysqli_fetch_assoc($req);

		if (!in_array($_GET['mail'],array('DI','CO'))) {
	    	$sql = 'SELECT Classe, Nom, login, password, mail FROM voeux_pp WHERE Classe="'.$_GET['mail'].'"';
	    	$req = $conn->query($sql);
	    	$prof = mysqli_fetch_assoc($req);
	    	ini_set( 'display_errors', 1 );
		    error_reporting( E_ALL );
		    $to = $prof['mail'];
		    $subject = "Vos identifiants pour le site de saisi des voeux";

		    $message = '<h1 style="font-size: 1.5em;">Bonjour '.$prof['Nom'].' !</h1>'."\r\n\r\n";
			$message .= '<p>Voici vos identifiants pour modifier les choix de vos élèves de la classe '.$prof['Classe'].'.'."\r\n";
			$message .= '<br/>login : '.$prof['login']."\r\n";
			$message .= '<br/>mot de passe : '.$prof['password']."\r\n";
			$message .= '<br/>lien de connexion : '.$nom_domaine.'voeux </p>'."\r\n\r\n";
			$message .= '<p>Utilisez ces identifiants avec précaution, rappelez-vous des sages paroles d\'Oncle Ben :'."\r\n";
			$message .= '<br/>« <em>Un grand pouvoir implique de grandes responsabilités.</em> »</p>'."\r\n";
			$message .= $admin['Nom']."\r\n";

		    $headers = 'From: '.$admin['Nom'].' <'.$admin['mail'].'>'."\r\n";
	    	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
			$headers .= "\r\n";

		    if (mail($to,$subject,$message, $headers)) {
		    	echo '<h3>L\'email a bien été envoyé à '.$prof['Nom'].'.</h3>';
		    } else {
		    	echo '<h3>Attention il y a un problème dans l\'envoi du mail !</h3>
		    	<p>Contactez l\'admin du site principal, et demandez lui de regarder dans le fichier des erreurs rencontrées.</p>';
		    }
		}

		elseif ($_GET['mail'] == 'DI') {
			$sql = 'SELECT Nom, login, password, mail FROM voeux_pp WHERE Classe="DI"';
	    	$req = $conn->query($sql);
	    	$direction = mysqli_fetch_assoc($req);
		    $to = $direction['mail'];
		    $subject = "Vos identifiants pour le site de saisi des voeux";
		    
		    $message = '<h1 style="font-size: 1.5em;">Bonjour '.$direction['Nom'].' !</h1>'."\r\n\r\n";
			$message .= '<p>Voici vos identifiants pour visualiser les choix des élèves du lycée et créer des groupes selon les spécialités.'."\r\n";
			$message .= '<br/>login : '.$direction['login']."\r\n";
			$message .= '<br/>mot de passe : '.$direction['password']."\r\n";
			$message .= '<br/>lien de connexion : '.$nom_domaine.'voeux </p>'."\r\n\r\n";
			$message .= '<p>Bien cordialement,</p>'."\r\n";
			$message .= '<p>'.$admin['Nom'].'</p>'."\r\n";

		    $headers = 'From: '.$admin['Nom'].' <'.$admin['mail'].'>'."\r\n";
	    	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
			$headers .= "\r\n";

		    if (mail($to,$subject,$message, $headers)) {
		    	echo '<h3>L\'email a bien été envoyé à la direction.</h3>';
		    } else {
		    	echo '<h3>Attention il y a un problème dans l\'envoi du mail !</h3>
		    	<p>Contactez l\'admin du site principal, et demandez lui de regarder dans le fichier des erreurs rencontrées.</p>';
		    }
		}
		
    }
  /*}*/

    if (isset($_POST['mail_visiteur'])) {
    	$sql = 'SELECT Nom, mail FROM voeux_pp WHERE Classe="AD"';
    	$req = $conn->query($sql);
    	$admin = mysqli_fetch_assoc($req);

		$sql = 'SELECT login, password FROM voeux_pp WHERE Classe="CO"';
    	$req = $conn->query($sql);
    	$visiteur = mysqli_fetch_assoc($req);

    	$email = test_input($_POST['email']);
		$nom = test_input($_POST['nom']);
		$error = 1;
		if (!preg_match("/^[a-zA-ZéÉèÈëËàÀïÏîÎöÖôÔ\- ]*$/",$nom)) {
			$error = $error*2;
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = $error*3;
		}


		if ($error == 1) {
		    $to = $_POST['email'];
		    $subject = "Identifiants pour le site de saisi des voeux";

		    $message = '<h1 style="font-size: 1.5em;">Bonjour '.$_POST['nom'].' !</h1>'."\r\n\r\n";
			$message .= '<p>Voici les identifiants pour visualiser le bilan des choix des élèves du lycée.'."\r\n";
			$message .= '<br/>login : '.$visiteur['login']."\r\n";
			$message .= '<br/>mot de passe : '.$visiteur['password']."\r\n";
			$message .= '<br/>lien de connexion : '.$nom_domaine.'voeux </p>'."\r\n\r\n";
			$message .= '<p>Bien cordialement,</p>'."\r\n";
			$message .= '<p>'.$admin['Nom'].'</p>'."\r\n";
		    
		    $headers = 'From: '.$admin['Nom'].' <'.$admin['mail'].'>'."\r\n";
	    	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
			$headers .= "\r\n";

		    if (mail($to,$subject,$message, $headers)) {
		    	echo '<h3>L\'email a bien été envoyé à '.$_POST['nom'].' ('.$email.').</h3>';
		    } else {
		    	echo '<h3>Attention il y a un problème dans l\'envoi du mail !</h3>
		    	<p>Contactez l\'admin du site principal, et demandez lui de regarder dans le fichier des erreurs rencontrées.</p>';
		    }
		}
		else {
			echo 'Un problème a été rencontré, vous avez utilisé des caractères non autorisés dans le nom ou utilisé un mauvais format de mail.';
		}
	}

/* Pour modifier un compte prof */
	if (isset($_GET['option'])) {
		if ($_GET['option'] == 'modifier') {
		  	$sql = 'SELECT Classe, Nom, login, password, mail FROM voeux_pp WHERE Classe="'.$_GET['classe'].'"';
		    $req = $conn->query($sql);
		    $prof = mysqli_fetch_assoc($req);
		    echo '
		    <h1>Modification d\'un compte</h1>
		    <form autocomplete="off" action="gestion_prof.php?classe='.$_GET['classe'].'" method="post">
		    '.(!in_array($_GET['classe'],array('AD','DI','CO')) ? 'Classe : '.$_GET['classe'].'<br/>' : '').'
		    Nom : <input name="nom" value="'.$prof['Nom'].'" required>
		    <br/>
		    Login : <input name="login" value="'.$prof['login'].'" required>
		    <br/>
		    Mot de passe : <input name="password" id="password" type="text" title="Seulement des lettres en minuscule ou majuscule ou des chiffres." value="'.$prof['password'].'" required>
	    	<input type="button" name="generer" value="Générer" onclick="javascript:generer_password(\'password\');" />
		    <br/>'.($_GET['classe'] <> 'CO' ? 'Mail : <input name="email" value="'.$prof['mail'].'" required>' : '').'<br/>
		    <input type="submit" name="valider_modif" value="Valider">
		    </form>';
		}

		elseif ($_GET['option'] == 'ajouter') {
			if (isset($_POST['new_pwd'])) {
		    	$new_pwd = create_pwd();
		    }
    		echo '
    		<h1>Création d\'un compte pour une nouvelle classe</h1>
    		<form autocomplete="off" method="post" action="gestion_prof.php">
	    	Classe : <input type="text" name="Classe" required>
	    	<br/>
	    	Nom : <input type="text" name="Nom" required>
	    	<br/>
	    	Login : <input type="text" name="login" required>
	    	<br/>
	    	Mot de passe : <input name="password" id="password" type="text" title="Seulement des lettres en minuscule ou majuscule ou des chiffres." required>
	    	<input type="button" name="generer" value="Générer" onclick="javascript:generer_password(\'password\');" />
	    	<br/>
	    	Mail : <input type="text" name="email" required>
	    	<br/>
	    	<input type="submit" name="valider_ajout" value="Valider">
	    	</form>
	    ';
		}
    }

/* Enregistrement des modification d'un compte prof dans la BD */
	if (isset($_POST['valider_modif'])) {
		// On vérifie d'abord si les informations contact sont au bon format
		$error = 1;

		$email = test_input($_POST['email']);
		$nom = test_input($_POST['nom']);
		$login = test_input($_POST['login']);
		$mdp = test_input($_POST['password']);

		if (!preg_match("/^[a-zA-ZéÉèÈëËàÀïÏîÎöÖôÔ\- ]*$/",$nom)) {
			$error = $error*2;
		}
		if (!preg_match("/^[a-zA-Z ]*$/",$login)) {
			$error = $error*3;
		}
		if (!preg_match("/^[a-zA-Z0-9 ]*$/",$mdp)) {
			$error = $error*5;
		}
		if ((!filter_var($email, FILTER_VALIDATE_EMAIL)) and ($_GET['classe'] <> 'CO')) {
			$error = $error*7;
		}


		if ($error == 1) { // On vérifie si l'email est au bon format
			$sql = 'UPDATE voeux_pp SET Nom="'.$nom.'", login="'.$login.'", password="'.$mdp.'", mail="'.$email.'" WHERE Classe="'.$_GET['classe'].'"';
	    	if ($conn->query($sql) === TRUE) {echo 'Modifications enregistrées pour '.$_POST['nom'];}
	    	else {echo 'Il y a un problème dans l\'enregistrement, contactez Romuald Pol';}
		} 
		else { 
			echo '<br/><strong>L\'enregistrement a échoué</strong> à cause du :<ul>';
			if ($error % 2 == 0) {
				echo '<li>nom qui comporte des caractère non acceptés</li>';
			}
			if ($error % 3 == 0) {
				echo '<li>login qui comporte des caractère non acceptés</li>';
			}
			if ($error % 5 == 0) {
				echo '<li>mot de passe qui comporte des caractère non acceptés</li>';
			}
			if ($error % 7 == 0) {
				echo '<li>mail qui n\'est pas au bon format</li>';
			}
			echo '</ul>';
			
		}
		echo '<form action="gestion_prof.php"><input type="submit" value="ok"></form>';

    }

/* Pour ajouter une classe dans la base de données*/
    if (isset($_POST['valider_ajout'])) {

    	$classe = test_input($_POST['Classe']);
    	$email = test_input($_POST['email']);
		$nom = test_input($_POST['Nom']);
		$mdp = test_input($_POST['password']);
		$login = test_input($_POST['login']);
		$error = 1;
		if (!preg_match("/^[a-zA-ZéÉèÈëËàÀïÏîÎöÖôÔ\- ]*$/",$nom)) {
			$error = $error*2;
		}
		if (!preg_match("/^[a-zA-Z ]*$/",$login)) {
			$error = $error*3;
		}
		if (!preg_match("/^[a-zA-Z0-9 ]*$/",$mdp)) {
			$error = $error*5;
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = $error*7;
		}
		if (!preg_match("/^[12][A-Z]$/",$classe)) {
			$error = $error*11;
		}

		if ($error == 1) {
	    	$sql = 'INSERT INTO voeux_pp VALUES ("'.$classe.'","'.$nom.'","'.$login.'","'.$mdp.'","'.$email.'","'.date('Y',time()).'-09-01 00:00:00","3")';
	    	if ($conn->query($sql)) {
	    		echo ' La classe de '.$_POST['Classe'].' a bien été ajoutée ! <a href="gestion_prof.php">Cliquez ici</a> pour voir le résultat.';
	    		// Mettre à jour la liste des classes
	    		if (strripos($_POST['Classe'], '2') !== False) {
					array_push($_SESSION['liste_classes_seconde'],$_POST['Classe']);
				} elseif (strripos($_POST['Classe'], '1') !== False) {
					array_push($_SESSION['liste_classes_premiere'],$_POST['Classe']);
				}

	    	} else {
	    		echo ' Il y a eu un problème dans l\'ajout de la classe de '.$_POST['Classe'].' dans la base de données.'.$sql;
	    	}
	    } else {echo 'Un des champs n\'est pas au bon format.';}
    }

  echo '
			</div>
		</div>
	</div>
    <footer><p>2019-'.date('Y',time()).' - <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="../images/cc.png" title="Ce site est mis à disposition selon les termes de la Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International."/></a> -  <a href="https://github.com/polro/orientation_lycee">Romuald Pol</a></p>
	</footer>
  </body>
</html> ';
  $conn->close();

}

//Si la personne n'est pas identifiée
else{
  Header('Location:../index.php');}
?>