<?php

include 'php/connect.php';

echo '<!DOCTYPE html>
<html lang="fr" >
  <head>
    <title>Lycée Marx Dormoy</title>
    <meta name="author" content="Romuald Pol" />
    <link href="../styles/style.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../styles/impression.css" rel="stylesheet" type="text/css" media="print" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
  </head>
  <body>';

if (!isset($_POST['valider'])) {

	$sql = 'SELECT Nom, login, password, mail FROM voeux_pp WHERE Classe="AD"';
	if ($req = $conn->query($sql)) {
		$admin = mysqli_fetch_assoc($req);
		echo '<h2>Le site est déjà paramétré.</h2> 
		<p>Si vous avez oublié le mot de passe administrateur et que vous voulez recevoir vos identifiants sur l\'adresse email du compte admin</p> <form method="post"><input type="submit" name="connexion" value="cliquez ici"></form>' ;

		/* Envoi du mail de connexion admin */
		if (isset($_POST['connexion'])) {
			$to = $admin['mail'];
		    $subject = "Vos identifiants pour le site de saisi des voeux";
		    $message = '
	Bonjour '.$admin['Nom'].' !

	Voici vos identifiants pour administrer le site de saisi des vœux.
	login : '.$admin['login'].'
	mot de passe : '.$admin['password'].'
	lien de connexion : https://www.lycee-marxdormoy-creteil.fr/voeux

	Utilisez ces identifiants judicieusement, rappelez-vous des sages paroles d\'Oncle Ben :
	« Un grand pouvoir implique de grandes responsabilités. »

	'.$admin['Nom'];
		    $headers = "From:" . $admin['mail'];
		    if (mail($to,$subject,$message, $headers)) {
		    	echo '<h3>L\'email a bien été envoyé.</h3>';
		    } else {
		    	echo '<h3>Attention il y a un problème dans l\'envoi du mail !</h3>
		    	<p>Contactez l\'admin du site principal, et demandez lui de regarder dans le fichier des erreurs rencontrées.</p>';
		    }
		}
	} 
	else { /* On va créer les bases de données */
		$sql = 'CREATE TABLE voeux_pp (
	  		Classe VARCHAR(2),
	  		Nom VARCHAR(55),
	  		login VARCHAR(32), 
	  		password VARCHAR(12), 
	  		mail VARCHAR(50), 
	  		modif DATETIME, 
	  		acces TINYINT,
	  	    PRIMARY KEY (Classe));';
		if ($conn->query($sql)) {
			echo '<p>La base de données des professeurs a bien été créée !</p>';
		} else {echo '<p>Il y a eu un problème dans la création de la base professeurs.</p>';echo '<br/>'.$sql;}
		
		$sql = 'INSERT INTO voeux_pp VALUES ("DI","Direction","direction","amodifier","exmple@ac-academie.fr","'.date('Y',time()).'-09-01 00:00:00", "2");';
		if ($conn->query($sql)) {
			echo '<p>Le compte de la direction a été créé. N\'oubliez pas de modifier le mot de passe et l\'adresse email dans la partie Gestion Profs.</p>';
		} else {
			echo '<p>Un problème inattendu est survenu pendant la création du compte de la direction.</p>';
		}

		$sql = 'INSERT INTO voeux_pp VALUES ("CO","Collègue","professeur","amodifier","aucun@mail.fr","'.date('Y',time()).'-09-01 00:00:00", "4");';
		if ($conn->query($sql)) {
			echo '<p>Le compte des collègues visiteurs a été créé. N\'oubliez pas de modifier le mot de passe dans la partie Gestion Profs.</p>';
		} else {
			echo '<p>Un problème inattendu est survenu pendant la création du compte descollègues visiteurs.</p>';
		}


		$sql = 'CREATE TABLE voeux_eleves (
	  		ID int(11) NOT NULL AUTO_INCREMENT, 
	  		Nom VARCHAR(55), 
	  		Classe VARCHAR(2), 
	  		g_choix_1 VARCHAR(7), 
	  		g_choix_2 VARCHAR(7), 
	  		g_choix_3 VARCHAR(7), 
	  		g_choix_4 VARCHAR(7), 
	  		g_choix_5 VARCHAR(8), 
	  		voeux_g tinyint(1), 
	  		t_choix_1 VARCHAR(7), 
	  		t_choix_2 VARCHAR(7), 
	  		voeux_t tinyint(1), 
	  		a_choix_1 VARCHAR(7), 
	  		voeux_a tinyint(1), 
	  		term_choix_1 VARCHAR(7), 
	  		term_choix_2 VARCHAR(7), 
	  		term_choix_3 VARCHAR(7), 
	  		term_choix_4 VARCHAR(7), 
	  		term_choix_5 VARCHAR(9), 
	  		term_choix_6 VARCHAR(7),
	  	    PRIMARY KEY (ID));';
	  	if ($conn->query($sql)) {
			echo '<p>La base de données des élèves a bien été créée !</p>';
		} else {echo '<p>Il y a eu un problème dans la création de la base élèves, peut-être existe-t-elle déjà ? Vous pouvez la réinitialiser dans l\'espace administrateur.</p>';}

		echo '<form autocomplete="off" method="post">Création du compte administrateur
		<br/>Nom : <input type="text" name="Nom" required>
		<br/>Login : <input type="text" name="login" required>
		<br/>Mot de passe : <input type="password" name="mdp" required>
		<br/>Vérification du mot de passe : <input type="password" name="mdp2" required>
		<br/>Email : <input type="text" name="email" required>
		<br/><input type="submit" name="valider" value="Valider">';
	} 
} else {
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	$email = test_input($_POST['email']);
	$nom = test_input($_POST['Nom']);
	$login = test_input($_POST['login']);
	$mdp = test_input($_POST['mdp']);
	$mdp2 = test_input($_POST['mdp2']);
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
	if ($mdp <> $mdp2) {
		$error = $error*11;
	}

	if ($error == 1) {
		$sql = 'INSERT INTO voeux_pp VALUES ("AD","'.$_POST['Nom'].'","'.$_POST['login'].'","'.$_POST['mdp'].'","'.$_POST['email'].'","'.date('Y',time()).'-09-01 00:00:00", "1");';
		if ($conn->query($sql)) {
			echo '<p>Le compte administrateur a bien été créé. Vous pouvez maintenant vous connecter <a href="index.php">ici</a> et commencer à créer des classes la partie Gestion profs.';
		} else {
			echo 'Il y a eu un problème dans la création du compte administrateur. Tout est réinitialisé. <a href="setup.php">Recommencer</a>';
			echo '<br/>'.$sql;
			$sql2 = 'DROP TABLE voeux_pp';
  			$conn->query($sql2); 
		}
	} else {
		echo '<strong>Un ou des problèmes ont été constaté(s)</strong> :<ul>';
		if ($error % 2 == 0) {
			echo '<li>Le nom contient des caractères non autorisés.</li>';
		}
		if ($error % 3 == 0) {
			echo '<li>Le login contient des caractères non autorisés.</li>';
		}
		if ($error % 5 == 0) {
			echo '<li>Le mot de passe contient des caractères non autorisés.</li>';
		}
		if ($error % 7 == 0) {
			echo '<li>l\'adresse email n\'est pas au bon format.</li>';
		}
		if ($error % 11 == 0) {
			echo '<li>Les mots de passe ne correspondent pas.</li>';
		}
			echo '</ul>';
		echo 'Il y a eu un problème dans la création du compte administrateur. Tout est réinitialisé. <a href="setup.php">Recommencer</a>';
		$sql2 = 'DROP TABLE voeux_pp';
		$conn->query($sql2); 
	}
}

echo '</body></html>';
$conn->close();

?>