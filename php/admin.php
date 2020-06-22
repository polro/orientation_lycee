<?php
// Démarrage de la session
session_start();

if ($_SESSION['acces'] == '1'){

  if ($_GET['modif_classe'] <> '') {
  	$_SESSION['classe'] = $_GET['modif_classe'];
    Header('Location: classe.php');exit;
    
  } else {
    include 'menu.php';
    
    echo '
    <form style="padding: 20px 0px 0px 50px;" action="admin.php" method="get">
      <table style="min-width: 700px;">
        <caption>Modifier les vœux d\'une classe</caption>
        <tr>';
    foreach ($_SESSION['liste_classes_seconde'] as $classe) {
    	echo '<td><input type="submit" name="modif_classe" value="'.$classe.'"></td>';
    }        
    echo '
        </tr>
        <tr>';
    foreach ($_SESSION['liste_classes_premiere'] as $classe) {
    	echo '<td><input type="submit" name="modif_classe" value="'.$classe.'"></td>';
    }  
        
    echo '</tr>
      </table>
    </form>
    <form style="padding: 50px 0px 0px 50px;" action="admin.php" method="get">
      <table style="min-width: 700px;">
        <caption>Supprimer tous les élèves d\'une classe ?</caption>
        <tr>';
    foreach ($_SESSION['liste_classes_seconde'] as $classe) {
          echo '<td><input type="submit" name="suppr_classe" value="'.$classe.'"></td>';
    }
    echo '
        </tr>
        <tr>';
    foreach ($_SESSION['liste_classes_premiere'] as $classe) {
          echo '<td><input type="submit" name="suppr_classe" value="'.$classe.'"></td>';
    }
    echo '</tr>
      </table>
    </form>';

    if ($_GET['suppr_classe'] <> ''){
  	echo '<div style="padding: 50px;">Es-tu sûr de vouloir supprimer tous les élèves de la classe de '.$_GET['suppr_classe'].' ?
  	<h2>Cette action est irréversible !</h2>
  	<form action="admin.php?classe='.$_GET['suppr_classe'].'" method="post">
  	  <input type="submit" name="valider_suppr" value="Oui">
  	  <input type="submit" name="valider_suppr" value="Non">
  	</form></div>';
    }

    if (isset($_POST['valider_suppr'])) {
  	  if ($_POST['valider_suppr'] == 'Oui') {
  		$sql = 'DELETE FROM voeux_eleves WHERE Classe="'.$_GET['classe'].'"';
  		$result = $conn->query($sql);
  	  }
    }


    echo '
    <form style="padding: 50px 0px 0px 50px;" action="admin.php" method="get">
      Opérations sur la base de données des élèves et profs 
      <br/><input type="submit" name="admin_bd" value="Réinitialiser" title="La base de données des élèves (à faire en début d\'année)">
      <input type="submit" name="admin_bd" value="Sauvegarder" title="Dernière sauvegarde : '.date ("d F Y H:i:s.", filemtime('../sauvegarde_db/sauvegarde.sql')).'">
      <input type="submit" name="admin_bd" value="Restaurer" title="Dernière sauvegarde : '.date ("d F Y H:i:s.", filemtime('../sauvegarde_db/sauvegarde.sql')).'"> 
    </form>';

    if ($_GET['admin_bd'] == 'Réinitialiser') {
  	  echo '<div style="padding: 50px 0px 0px 50px;">Es-tu sûr de vouloir réinitialiser la base de données de tous les élèves du lycée ?
  	<h2>Cette action est irréversible !</h2>
  	<form action="admin.php" method="post">
  	  <input type="submit" name="valider_reset" value="Oui">
  	  <input type="submit" name="valider_reset" value="Non">
  	</form></div>';
    }

    if ($_GET['admin_bd'] == 'Sauvegarder') {
  	  system('mysqldump --host='.$servername.' --user='.$username.' --password='.$password.' '.$database.' > ../sauvegarde_db/sauvegarde.sql');
    }

    if ($_GET['admin_bd'] == 'Restaurer') {
  	  system('cat ../sauvegarde_db/sauvegarde.sql | mysql --host='.$servername.' --user='.$username.' --password='.$password.' '.$database);
    }

    if (isset($_POST['valider_reset'])) {
  	  if ($_POST['valider_reset'] == 'Oui') {
  		$sql = 'DROP TABLE voeux_eleves';
  		$result = $conn->query($sql);
  		$sql = 'CREATE TABLE voeux_eleves (
  		ID int(11) NOT NULL AUTO_INCREMENT, 
  		Nom varchar(55), 
  		Classe varchar(2), 
  		g_choix_1 varchar(7), 
  		g_choix_2 varchar(7), 
  		g_choix_3 varchar(7), 
  		g_choix_4 varchar(7), 
  		g_choix_5 varchar(8), 
  		voeux_g tinyint(1), 
  		t_choix_1 varchar(7), 
  		t_choix_2 varchar(7), 
  		voeux_t tinyint(1), 
  		a_choix_1 varchar(7), 
  		voeux_a tinyint(1), 
  		term_choix_1 varchar(7), 
  		term_choix_2 varchar(7), 
  		term_choix_3 varchar(7), 
  		term_choix_4 varchar(7), 
  		term_choix_5 varchar(9), 
  		term_choix_6 varchar(7),
  	    PRIMARY KEY (ID));';
  		$result = $conn->query($sql);
  	  }
    }

    echo '  </div>
<footer><p>2019-'.date('Y',time()).' - <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="../images/cc.png" title="Ce site est mis à disposition selon les termes de la Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International."/></a> -  <a href="https://github.com/polro/orientation_lycee">Romuald Pol</a></p>
</footer>
    </body>
       </html> ';
  }
  $conn->close();
}

else{
  Header('Location: ../index.php');}
?>