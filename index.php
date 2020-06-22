<?php
include 'php/connect.php';
?>

<!DOCTYPE html>
<html lang="fr" >
  <head>
    <title>Lycée <?php echo $lycee; ?></title>
    <meta name="author" content="Romuald Pol" />
    <link href="styles/style.css" rel="stylesheet" type="text/css" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="icon" href="images/favicon.ico" />
  </head>
  <body>
  <div class="content">
    <div style="display:flex;justify-content: space-around;">
      <img src="images/logo.png" alt="logo" width="120" height="120"/>
      <h1>Site de saisi des vœux d'orientation<br/>du lycée <?php echo $lycee; ?></h1>
    </div>
    <ul class="menu">
      <li class="menu"><a href="php/bilan-seconde.php" class="menu">Bilan de 2<sup>nd</sup></a></li>
      <li class="menu"><a href="php/bilan-premiere.php" class="menu">Bilan de 1<sup>ère</sup></a></li>
    </ul>
  
    <h1>Bienvenue</h1> 
    <p>Vous êtes sur le site de saisi des vœeux des élèves du lycée <?php echo $lycee; ?>.</p>
    <p>Pour pouvoir continuer sur ce site, vous devez d'abord vous connecter.</p> 

    <form action="php/VerifId.php" method="post">
      Votre login : <input type="text" name="login">
      <br />
      Votre mot de passe : <input type="password" name="pwd"><br />
      <input type="submit" value="Connexion">
    </form>
    <p style="font-size: 0.8em;">Si vous êtes un professeur principal du lycée et que vous avez oublié vos identifiants <a href="php/lost_pwd.php?option=pp">cliquez ici</a>.</p>
    <p style="font-size: 0.8em;">Si vous êtes un professeur du lycée et que vous voulez les identifiants pour voir les bilans <a href="php/lost_pwd.php?option=visiteur">demandez ici</a>.</p>

    <?php 
    if ($_GET['mail'] == 'ok') {
    	echo '<p>Un mail a bien été envoyé au gestionnaire du site, il vous recontactera le plus rapidement possible.</p>';
    } elseif ($_GET['mail'] == 'pasok') {
    	echo '<p>Le mail n\'a pu être envoyé au gestionnaire du site, cela peut-être dû à une maivaise adresse email qui a été saisie ou à un problème du serveur.
    	<br/>Nous nous excusons pour la gêne occasionnée.</p>';
    }
    ?>
</div>
    <footer><p>2019-<?php echo date('Y',time()); ?> - <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="images/cc.png" title="Ce site est mis à disposition selon les termes de la Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International."/></a> -  <a href="https://github.com/polro/orientation_lycee">Romuald Pol</a></p>
</footer>
</body>
</html>