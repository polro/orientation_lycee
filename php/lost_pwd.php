<?php 

include 'connect.php';
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if (isset($_POST['valider'])) {
	$email = test_input($_POST['email']);
	$nom = test_input($_POST['Nom']);
	if ((filter_var($email, FILTER_VALIDATE_EMAIL)) and (preg_match("/^[a-zA-ZéÉèÈëËàÀïÏîÎöÖôÔ\- ]*$/",$nom))) {
		$sql = 'SELECT Nom, mail FROM voeux_pp WHERE Classe="AD";';
		$req = $conn->query($sql);
		$admin = mysqli_fetch_assoc($req);

		$sql = 'SELECT login, password FROM voeux_pp WHERE Classe="CO";';
		$req = $conn->query($sql);
		$visiteur = mysqli_fetch_assoc($req);

		$to = $admin['mail'];
	    $subject = "Identifiants pour le site de saisi des voeux";
	    $message = ' Un accès au compte visiteur du site de saisi de vœux vient d\'être demandé par '.$nom.' avec comme adresse email '.$email.'. Vous avez un modèle de mail disponible ci-dessous pour lui envoyer les identifiants.

	    _________________________________________________________________ 

Bonjour '.$nom.',

Voici les identifiants pour visualiser le bilan des choix des élèves du lycée.
login : '.$visiteur['login'].'
mot de passe : '.$visiteur['password'].'
lien de connexion : https://www.lycee-marxdormoy-creteil.fr/voeux

Bien cordialement,

'.$admin['Nom'];
	    $headers = "From: " . $admin['mail'].'\n';
	    $headers .='Content-Type: text/plain; charset="utf-8"'." ";
	    if (mail($to,$subject,$message, $headers)) {
	    	Header('Location:../index.php?mail=ok');
	    } else {
	    	Header('Location:../index.php?mail=pasok');
	    }
	    //echo $to.'<br/>'.$subject.'<br/>'.$message.'<br/>'.$headers;
	}
		else {
			Header('Location:../index.php?mail=pasok');
		}
}


echo '<!DOCTYPE html>
<html lang="fr" >
  <head>
    <title>Lycée Marx Dormoy</title>
    <meta name="author" content="Romuald Pol" />
    <link href="../styles/style.css" rel="stylesheet" type="text/css" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="icon" href="images/favicon.ico" />
  </head>
  <body>

    <div style="display:flex;justify-content: space-around;">
      <img src="../images/logo.png" alt="logo" width="120" height="120"/>
      <h1>Site de saisi des vœux d\'orientation<br/>du lycée Marx Dormoy</h1>
    </div>
    <ul class="menu">
      <li class="menu"><a href="bilan-seconde.php" class="menu">Bilan de 2<sup>nd</sup></a></li>
      <li class="menu"><a href="bilan-premiere.php" class="menu">Bilan de 1<sup>ère</sup></a></li>
    </ul>';
	    
if ($_GET['option'] == 'pp') {
    if (!isset($_POST['valider'])) {
    	$sql = 'SELECT Classe FROM voeux_pp ORDER BY Classe;';
	   	$result = $conn->query($sql);
	   	if ($result->num_rows > 0) {
	   		$liste_classes = array();
			while($classe = $result->fetch_assoc()) {
				if ((strripos($classe['Classe'], '2') !== False) | (strripos($classe['Classe'], '1') !== False)) {
					array_push($liste_classes,$classe['Classe']);
				}
			}
	   	}
    	echo '<form autocomplete="off" method="post">
      Quelle est la classe dont vous êtes le PP ? 
      <select name="classe">';
    	foreach ($liste_classes as $classe) {
    	 	echo '<option value="'.$classe.'">'.$classe;
    	} 
      	echo '</select><br />
      Votre adresse email : <input type="text" name="mail" required ><br />
      <input type="submit" name="valider" value="Envoyer">
    </form>';
	} else {
		$email = test_input($_POST['mail']);
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$sql = 'SELECT Classe, Nom, login, password, mail FROM voeux_pp WHERE Classe="'.$_POST['classe'].'"';
			$result = $conn->query($sql);
			$prof = $result->fetch_assoc();
			
			if ($email == $prof['mail']) {
				$sql = 'SELECT Nom, mail FROM voeux_pp WHERE Classe="AD"';
		    	$req = $conn->query($sql);
		    	$admin = mysqli_fetch_assoc($req);

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
			    $headers = "From:" . $admin['mail'].'\n';
	    		$headers .='Content-Type: text/plain; charset="utf-8"'." ";
			    if (mail($to,$subject,$message, $headers)) {
			    	echo '<h3>Un email contenant vos indentifiants a bien été envoyé sur l\'adresse email renseignée.</h3>
			    	<p><a href="../index.html">Cliquez ici</a> pour vous connecter.';
			    } else {
			    	echo '<h3>Attention il y a un problème dans l\'envoi du mail !</h3>
			    	<p>Contactez l\'admin du site principal, et demandez lui de regarder dans le fichier des erreurs rencontrées.</p>';
			    }
			} else {echo 'L\'email enregistrée dans la base de données n\'est pas celle que vous avez écrite.';}
		} else {echo 'Mauvais format de l\'adresse email.';}
	}

  	echo '
  	<footer><p>2019-'.date('Y',time()).' - <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="https://licensebuttons.net/l/by-nc-sa/4.0/80x15.png" title="Ce site est mis à disposition selon les termes de la Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International."/></a> -  <a href="https://github.com/polro/orientation_lycee">Romuald Pol</a></p>
</footer>
</body>
	</html>';
		$conn->close();
} elseif ($_GET['option'] == 'visiteur') {
	echo '<p>Veuillez saisir votre nom et votre adresse email ci-dessous, le gestionnaire du site recevra un mail avec ces informations et vous enverra un mail avec les identifiants dès qu\'il aura le temps.</p>
	<form autocomplete="off" method="post">
	Nom Prénom : <input type="text" name="Nom" required>
	<br/>
	Adresse email : <input type="text" name="email" required>
	<br/>
	<input type="submit" name="valider" value="Envoyer">
	</form>
	<footer><p>2019-'.date('Y',time()).' - <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="https://licensebuttons.net/l/by-nc-sa/4.0/80x15.png" title="Ce site est mis à disposition selon les termes de la Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International."/></a> -  <a href="https://github.com/polro/orientation_lycee">Romuald Pol</a></p>
</footer>
</body>
</html>';
}

?>