<?php 

include 'connect.php';

//fonction pour réécrire correctement les champs de saisi.
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
} 

$mdp = test_input($_POST['pwd']);
$login = test_input($_POST['login']);
$error = 1;
if (!preg_match("/^[a-zA-Z ]*$/",$login)) {
	$error = $error*3;
}
if (!preg_match("/^[a-zA-Z0-9 ]*$/",$mdp)) {
	$error = $error*5;
}

if ($error == 1) {
	//  Récupération de l'utilisateur et de son pass hashé
	$sql = 'SELECT Classe, Nom, password, acces, DATE_FORMAT(modif, "%e/%m/%Y à %H:%i:%s") as modif FROM voeux_pp WHERE login="'.$_POST['login'].'"';
	$req = $conn->query($sql);

	if ($req->num_rows > 0) {
	    $result = $req->fetch_assoc();
	}else {
		include 'menu.php';
	    echo 'Mauvais identifiant ou mot de passe ! <a href=$../inpex.php">Retournez à l\'écran d\'accueil</a>.';
	}


	if ($_POST['pwd'] == $result['password']) {
	   	session_start();
	   	$_SESSION['login'] = $_POST['login'];
	   	$_SESSION['nom'] = $result['Nom'];
	   	$_SESSION['classe'] = $result['Classe'];
	   	$_SESSION['date_modif'] = $result['modif'];
	   	$_SESSION['acces'] = $result['acces'];

	   	$sql = 'SELECT Classe FROM voeux_pp ORDER BY Classe;';
	   	$result = $conn->query($sql);
	   	if ($result->num_rows > 0) {
	   		$_SESSION['liste_classes_seconde'] = array();
			$_SESSION['liste_classes_premiere'] = array();
			while($classe = $result->fetch_assoc()) {
				if (strripos($classe['Classe'], '2') !== False) {
					array_push($_SESSION['liste_classes_seconde'],$classe['Classe']);
				} elseif (strripos($classe['Classe'], '1') !== False) {
					array_push($_SESSION['liste_classes_premiere'],$classe['Classe']);
				}
			}
	   	}
		
	   
	   	if ($_SESSION['acces'] == '1') {
	     	Header('Location:admin.php');
	     }
	   	elseif ($_SESSION['acces'] == '3') {
	     	Header('Location:classe.php');
	    } else {
	    	Header('Location:bilan-seconde.php');
	    }
	}
	else {
	   include 'menu.php';
	    echo 'Mauvais identifiant ou mot de passe ! <a href=$../inpex.php">Retournez à l\'écran d\'accueil</a>.';
	}
	$conn->close();
}
else {Header('Location:../index.php');}
?>