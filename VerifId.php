<?php 

include 'connect.php';


//  Récupération de l'utilisateur et de son pass hashé
$sql = 'SELECT Classe, nom, password, DATE_FORMAT(modif, "%e/%m/%Y à %H:%i:%s") as modif FROM voeux_pp WHERE login="'.$_POST['login'].'"';
$req = $conn->query($sql);

if ($req->num_rows > 0) {
    $result = $req->fetch_assoc();
}else {
   echo 'Mauvais identifiant ou mot de passe !';
}


if ($_POST['pwd'] == $result['password']) {
   session_start();
   $_SESSION['login'] = $_POST['login'];
   $_SESSION['nom'] = $result['nom'];
   $_SESSION['classe'] = $result['Classe'];
   $_SESSION['date_modif'] = $result['modif'];
   if ($_POST['login'] == 'admin'){
     Header('Location:admin.php');}
   else{
     Header('Location:menu.php');}
}
else {
   echo 'Mauvais identifiant ou mot de passe !';
}

