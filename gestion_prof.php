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
if ($_SESSION['login'] == 'admin'){
  include 'connect.php';
  include 'menu.php';

  if (($_GET['option'] <> 'modifier') & !isset($_POST['valider_modif'])) {
  	//On affiche la liste des comptes profs
    $sql = 'SELECT Classe, Nom FROM voeux_pp ORDER BY `Classe` ASC';
    $req = $conn->query($sql);
    echo '<style>td{text-align: center;height: 30px;vertical-align: center;}</style>
<table>
  <tr>
    <th style="width: 80px;">Classe</th>
    <th style="width: 250px;">Professeur principal</th>
    <th style="width: 90px;">Envoyer mail</th>
    <th style="width: 90px;">Modifier<th>
  </tr>';
    if ($req->num_rows > 0) {
        while($row = $req->fetch_assoc()) {
          	echo '
  <tr>
    <td>'.$row['Classe'].'</td>
    <td>'.$row['Nom'].'</td>
    <td>';
    		if ($row['Classe'] <> 'AD') {
    			echo '<a href="gestion_prof.php?option=mail&classe='.$row['Classe'].'"><img src="images/mail.png" alt="" title="Envoyer les identifiant par mail à '.$row['Nom'].'" width=25px height=25px></a>';}
    		echo '</td>
    <td><a href="gestion_prof.php?option=modifier&classe='.$row['Classe'].'"><img src="images/modifier.jpeg" alt="" title="Modifier le compte de '.$row['Nom'].'" width=25px height=25px></a></td>
  </tr>';
        }
    }
    echo '
</table>';

	if ($_GET['option'] == 'mail') {
		if ($_GET['classe'] == 'MD') {
			echo '<form autocomplete="off" method="post" action="gestion_prof.php">
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
			echo 'Confirmez-vous l\'envoi du mail à '.$prof['Nom'].' sur l\'adresse '.$prof['mail'].' ?
		<br><form method="post" action="gestion_prof.php?mail='.$_GET['classe'].'">
		<input type="submit" name="confirm_mail" value="Oui">
		<input type="submit" name="no_confirm_mail" value="Non">
		</form>';
		}
	}

//Envoie d'email
    if (isset($_POST['confirm_mail'])) {
    	//On retrouve l'admin pour le mail
    	$sql = 'SELECT Nom, mail FROM voeux_pp WHERE Classe="AD"';
    	$req = $conn->query($sql);
    	$admin = mysqli_fetch_assoc($req);

		if (!in_array($_GET['mail'],array('DI','MD'))) {
	    	$sql = 'SELECT Classe, Nom, login, password, mail FROM voeux_pp WHERE Classe="'.$_GET['mail'].'"';
	    	$req = $conn->query($sql);
	    	$prof = mysqli_fetch_assoc($req);
	    	ini_set( 'display_errors', 1 );
		    error_reporting( E_ALL );
		    $to = $prof['mail'];
		    $subject = "Vos identifiants pour le site de saisi des voeux";
		    $message = '
Bonjour '.$prof['Nom'].' !

Voici vos identifiants pour modifier les choix de vos élèves de la classe '.$prof['Classe'].'.
login : '.$prof['login'].'
mot de passe : '.$prof['password'].'
lien de connexion : https://www.lycee-marxdormoy-creteil.fr/voeux

Utilisez ces identifiants judicieusement, rappelez-vous des sages paroles d\'Oncle Ben :
« Un grand pouvoir implique de grandes responsabilités. »

'.$admin['Nom'];
		    $headers = "From:" . $admin['mail'];
		    if (mail($to,$subject,$message, $headers)) {
		    	echo '<h3>L\'email a bien été envoyé à '.$prof['Nom'].'.</h3>';
		    } else {
		    	echo '<h3>Attention il y a un problème dans l\'envoi du mail !</h3>
		    	<p>Contactez l\'admin du site principal, et demandez lui de regarder dans le fichier des erreurs rencontrées.</p>';
		    }
		}

		elseif ($_GET['mail'] == 'DI') {
			$sql = 'SELECT login, password, mail FROM voeux_pp WHERE Classe="DI"';
	    	$req = $conn->query($sql);
	    	$direction = mysqli_fetch_assoc($req);
		    $to = $direction['mail'];
		    $subject = "Vos identifiants pour le site de saisi des voeux";
		    $message = '
Bonjour,

Voici vos identifiants pour visualiser les choix des élèves du lycée et créer des groupes selon les spécialités.
login : '.$direction['login'].'
mot de passe : '.$direction['password'].'
lien de connexion : https://www.lycee-marxdormoy-creteil.fr/voeux

Bien cordialement,

'.$admin['Nom'];
		    $headers = "From:" . $admin['mail'];
		    if (mail($to,$subject,$message, $headers)) {
		    	echo '<h3>L\'email a bien été envoyé à la direction.</h3>';
		    } else {
		    	echo '<h3>Attention il y a un problème dans l\'envoi du mail !</h3>
		    	<p>Contactez l\'admin du site principal, et demandez lui de regarder dans le fichier des erreurs rencontrées.</p>';
		    }
		}
		
    }
  }
    if (isset($_POST['mail_visiteur'])) {
    	$sql = 'SELECT Nom, mail FROM voeux_pp WHERE Classe="AD"';
    	$req = $conn->query($sql);
    	$admin = mysqli_fetch_assoc($req);

		$sql = 'SELECT login, password FROM voeux_pp WHERE Classe="MD"';
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
		    $message = '
Bonjour '.$_POST['nom'].',

Voici les identifiants pour visualiser le bilan des choix des élèves du lycée.
login : '.$visiteur['login'].'
mot de passe : '.$visiteur['password'].'
lien de connexion : https://www.lycee-marxdormoy-creteil.fr/voeux

Bien cordialement,

'.$admin['Nom'];
		    $headers = "From:" . $admin['mail'];
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

// Pour modifier un compte prof
	if ($_GET['option'] == 'modifier') {
	  	$sql = 'SELECT Classe, Nom, login, password, mail FROM voeux_pp WHERE Classe="'.$_GET['classe'].'"';
	    $req = $conn->query($sql);
	    $prof = mysqli_fetch_assoc($req);
	    echo '
	    <h1>Modification d\'un compte professeur</h1>
	    <form autocomplete="off" action="gestion_prof.php?classe='.$_GET['classe'].'" method="post">
	    Classe : '.$_GET['classe'].'
	    <br/>
	    Nom : <input name="nom" value="'.$prof['Nom'].'">
	    <br/>
	    Login : <input name="login" value="'.$prof['login'].'">
	    <br/>
	    Mot de passe : <input name="mdp" value="'.$prof['password'].'">
	    <br/>';
	    if ($_GET['classe'] <> 'MD') {
	    	echo 'Email : <input name="email" value="'.$prof['mail'].'">';
	    }
	    echo '<br/>
	    <input type="submit" name="valider_modif" value="Valider">
	    </form>';
	}

  //Enregistrement des modification d'un compte prof dans la BD
	if (isset($_POST['valider_modif'])) {
		// On vérifie d'abord si les informations contact sont au bon format
		$error = 1;

		$email = test_input($_POST['email']);
		$nom = test_input($_POST['nom']);
		$login = test_input($_POST['login']);
		$mdp = test_input($_POST['mdp']);

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


		if ($error == 1) { // On vérifie si l'email est au bon format
			$set = 'Nom="'.$_POST['nom'].'", login="'.$_POST['login'].'", password="'.$_POST['mdp'].'", mail="'.$email.'"';
			$sql = 'UPDATE voeux_pp SET '.$set.' WHERE Classe="'.$_GET['classe'].'"';
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

  echo '
  </body>
</html> ';

}

//Si la personne n'est pas identifiée
else{
  Header('Location:index.html');}
?>