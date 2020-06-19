<?php
// Démarrage de la session
session_start();

if (isset($_SESSION['nom'])){  
	echo '<!DOCTYPE html>
<html lang="fr" >
  <head>
    <title>Lycée Marx Dormoy</title>
    <meta name="author" content="Romuald Pol" />
    <link href="../styles/style.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../styles/impression.css" rel="stylesheet" type="text/css" media="print" />
    <script src="../js/script.js"></script>
    <link rel="icon" href="../images/favicon.ico" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
  </head>
  <body>
    <div class="content">
    <div style="display:flex;justify-content: space-around;">
      <img src="../images/logo.png" alt="logo" width="120" height="120"/>
      <h1>Site de saisi des vœux d\'orientation<br/>du lycée Marx Dormoy</h1>
    </div>
    <div class="noimprim">
    <ul class="menu">';
  if ($_SERVER['PHP_SELF'] == "/voeux/php/bilan-seconde.php")
      {echo '
      <li class="menu active"><a href="bilan-seconde.php" class="menu active">Bilan de 2<sup>nd</sup></a></li>';}
  else {echo '
      <li class="menu"><a href="bilan-seconde.php" class="menu">Bilan de 2<sup>nd</sup></a></li>';}

  if ($_SERVER['PHP_SELF'] == "/voeux/php/bilan-premiere.php")
      {echo '
      <li class="menu active"><a href="bilan-premiere.php" class="menu">Bilan de 1<sup>ère</sup></a></li>';}
  else {echo '
      <li class="menu"><a href="bilan-premiere.php" class="menu">Bilan de 1<sup>ère</sup></a></li>';}


  if ($_SESSION['acces'] == '3'){
    if ($_SERVER['PHP_SELF'] == "/voeux/php/classe.php")
      {echo '
      <li class="menu active"><a href="classe.php" class="menu">Modifier les vœux</a></li>';}
    else {echo '
      <li class="menu"><a href="classe.php" class="menu">Modifier les vœux</a></li>';}
  } elseif ($_SESSION['acces'] == '1') {
    if ($_SERVER['PHP_SELF'] == "/voeux/php/admin.php")
      {echo '
      <li class="menu active"><a href="admin.php" class="menu">Gestion des classes</a></li>';}
    else {echo '
      <li class="menu"><a href="admin.php" class="menu">Gestion des classes</a></li>';}

    if ($_SERVER['PHP_SELF'] == "/voeux/php/gestion_prof.php")
      {echo '
      <li class="menu active"><a href="gestion_prof.php" class="menu">Gestion des profs</a></li>';}
    else {echo '
      <li class="menu"><a href="gestion_prof.php" class="menu">Gestion des profs</a></li>';}
  }


  if (in_array($_SESSION['acces'],array('1','2'))){
    if ($_SERVER['PHP_SELF'] == "/voeux/php/creation_groupe.php")
      {echo '
      <li class="menu active"><a href="creation_groupe.php" class="menu">Création des groupes</a></li>';}
    else {echo '
      <li class="menu"><a href="creation_groupe.php" class="menu">Création des groupes</a></li>';}
  }

  echo '
      <li class="menu"  style="float:right"><a href="Disconnect.php" class="menu">Déconnexion</a></li>
    </ul>';

  if ($_SESSION['acces'] == '1') {
    echo '
    <p class="menu"> Bonjour '.$_SESSION['nom'].', j\'espère que tu as passé une bonne journée !</p>';
  } elseif ($_SESSION['acces'] == '2'){
   	echo '
    <p class="menu"> Bonjour '.$_SESSION['nom'].'</p>';
  } elseif ($_SESSION['acces'] == '4'){
  	echo '
    <p class="menu"> Bonjour collègue, <br/>Bienvenue sur ce bilan des choix des élèves.</p>';
  } else{
    echo '
    <p class="menu">Bonjour '. $_SESSION['nom'] .', vous êtes l\'enseignant.e en charge de la classe de '.$_SESSION['classe'].'.</p>';
  }

  echo '</div>';


}
else
{Header('Location:../index.php');}
?>