<?php
session_start();

if (in_array($_SESSION['acces'],array('1','2'))){

  include 'decrypt_db.php';
  include 'menu.php';
  include 'connect.php';

  echo '<h2>Création de groupe</h2>
<div class="noimprim">
<p>Ici vous pouvez sélectionner des spécialités et/ou des options, et les élèves qui auront choisis cela seront affichés.</p>
<table class="bilan">
  <form method="post" action="creation_groupe.php">
    <caption>Groupe de première</caption>
    <tr>
      <th></th>
      <th class="bilan">Hist.-Géo GSP</th>
      <th class="bilan">Humanités LP</th>
      <th class="bilan">LLCE</th>
      <th class="bilan">Maths</th>
      <th class="bilan">Phys.-Ch.</th>
      <th class="bilan">SVT</th>
      <th class="bilan">SES</th>
      <th class="bilan">STMG</th>
    </tr>
    <tr>
      <th class="bilan">Avec</th>
      <td class="bilan"><input type="checkbox" name="grp_prem[]" value="HGGSP"></td>
      <td class="bilan"><input type="checkbox" name="grp_prem[]" value="HLP"></td>
      <td class="bilan"><input type="checkbox" name="grp_prem[]" value="LLCE"></td>
      <td class="bilan"><input type="checkbox" name="grp_prem[]" value="Maths"></td>
      <td class="bilan"><input type="checkbox" name="grp_prem[]" value="SPC"></td>
      <td class="bilan"><input type="checkbox" name="grp_prem[]" value="SVT"></td>
      <td class="bilan"><input type="checkbox" name="grp_prem[]" value="SES"></td>
      <td class="bilan"><input type="checkbox" name="grp_prem[]" value="STMG"></td>
    </tr>
    <tr>
      <th class="bilan">Sans</th>
      <td class="bilan"><input type="checkbox" name="sans_prem[]" value="HGGSP"></td>
      <td class="bilan"><input type="checkbox" name="sans_prem[]" value="HLP"></td>
      <td class="bilan"><input type="checkbox" name="sans_prem[]" value="LLCE"></td>
      <td class="bilan"><input type="checkbox" name="sans_prem[]" value="Maths"></td>
      <td class="bilan"><input type="checkbox" name="sans_prem[]" value="SPC"></td>
      <td class="bilan"><input type="checkbox" name="sans_prem[]" value="SVT"></td>
      <td class="bilan"><input type="checkbox" name="sans_prem[]" value="SES"></td>
      <td class="bilan"><input type="checkbox" name="sans_prem[]" value="STMG"></td>
    </tr>
    <tr>
      <td colspan="8" style="text-align: center;"><input type="submit" name="validate_prem" value="Valider"></td>
    </tr>
  </form>
</table>

<form method="post" action="creation_groupe.php">
  <table class="bilan">
    <caption>Groupe de terminale</caption>
    <tr>
      <th></th>
      <th colspan="7" class="bilan">Choix de spécialités</th>
    </tr>
    <tr>
      <th></th>
      <th class="bilan">Hist.-Géo GSP</th>
      <th class="bilan">Humanités LP</th>
      <th class="bilan">LLCE</th>
      <th class="bilan">Maths</th>
      <th class="bilan">Phys.-Ch.</th>
      <th class="bilan">SVT</th>
      <th class="bilan">SES</th>
    </tr>
    <tr>
      <th class="bilan">Avec</th>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="HGGSP"></td>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="HLP"></td>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="LLCE"></td>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="Maths"></td>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="SPC"></td>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="SVT"></td>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="SES"></td>
    </tr>
    <tr>
      <th class="bilan">Sans</th>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="HGGSP"></td>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="HLP"></td>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="LLCE"></td>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="Maths"></td>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="SPC"></td>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="SVT"></td>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="SES"></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="7" class="bilan vide"></td></tr>
    <tr>
      <th></th>
      <th colspan="7" class="bilan">Choix d\'options ou de réorientation</th>
    </tr>
    <tr>
      <th></th>
      <th class="bilan">Latin</th>
      <th class="bilan">HDA</th>
      <th class="bilan">Allemand euro</th>
      <th class="bilan">Maths comp.</th>
      <th class="bilan">Maths exp.</th>
      <th class="bilan">DGEMC</th>
      <th class="bilan">STMG</th>
    </tr>
    <tr>
      <th class="bilan">Avec</th>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="Latin-o"></td>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="HDA"></td>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="Alleuro"></td>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="Mathscomp"></td>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="Mathsexp"></td>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="DGEMC"></td>
      <td class="bilan"><input type="checkbox" name="grp_term[]" value="STMG"></td>
    </tr>
    <tr>
      <th class="bilan">Sans</th>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="Latin-o"></td>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="HDA"></td>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="Alleuro"></td>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="Mathscomp"></td>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="Mathsexp"></td>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="DGEMC"></td>
      <td class="bilan"><input type="checkbox" name="sans_term[]" value="STMG"></td>
    </tr>
    <tr>
      <td colspan="14" style="text-align: center;"><input type="submit" name="validate_term" value="Valider"></td>
    </tr>
  </table>
</form>
</div>

';
/*Affichage tableau pour les futures premières */
  if (isset($_POST['validate_prem'])){ 
  	$liste_classes = '("'.join('","',$_SESSION['liste_classes_seconde']).'")';
    $sql = 'SELECT Nom, Classe, g_choix_1, g_choix_2, g_choix_3, t_choix_1 FROM voeux_eleves WHERE Classe IN '.$liste_classes.' ORDER BY Nom';
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      echo '
<table style="padding-top:50px;margin:auto;">
  <tr>
    <td style="text-aligne:center; padding: 10px 25px 10px 25px;">Liste des élèves choisissant : 
<ul style="margin-bottom:30px;">';
      foreach ($_POST['grp_prem'] as $choix_grp){
        echo '<li>'.$choix_grp.'</li>';
      } //fin foreach
      echo '
</ul></td>';
      if (isset($_POST['sans_prem'])){
         echo '<td style="text-aligne:center; padding: 10px 25px 10px 25px;">et ne choisissant pas :
<ul style="margin-bottom:30px;">';
        foreach ($_POST['sans_prem'] as $choix_grp){
          echo '<li>'.$choix_grp.'</li>';
        } //fin foreach
      }// fin if
echo '
</ul><td></tr></table>
<table class="bilan">
  <tr>
    <th class="bilan">Nombre d\'élèves</th>
    <th class="bilan">Nom</th>
    <th class="bilan">Classe</th>
    <th class="bilan generale">1<sup>er</sup> choix</th>
    <th class="bilan generale">2<sup>e</sup> choix</th>
    <th class="bilan generale">3<sup>e</sup> choix</th>
    <th class="bilan techno">choix Techno</th>
  </tr>';
      $numero_eleve = 1;
      while($eleve = $result->fetch_assoc()) {
        $eleve_OK = False;
        $eleve_sans = False;
        foreach ($_POST['grp_prem'] as $choix_grp){
          if (in_array($conversion_vers_db[$choix_grp],array($eleve['g_choix_1'],$eleve['g_choix_2'],$eleve['g_choix_3'],$eleve['t_choix_1']))){
            $eleve_OK = True;
            break;
          } //fin if
        } //fin foreach
        if (isset($_POST['sans_prem'])){
          foreach ($_POST['sans_prem'] as $choix_grp){
            if (in_array($conversion_vers_db[$choix_grp],array($eleve['g_choix_1'],$eleve['g_choix_2'],$eleve['g_choix_3'],$eleve['t_choix_1']))){
              $eleve_sans = True;
              break;
            } //fin if
          } //fin foreach
        } //fin if
        if ($eleve_OK and !$eleve_sans){
          echo '
  <tr>
    <td class="bilan">'.$numero_eleve++.'</td>
    <td class="bilan">'.$eleve['Nom'].'</td>
    <td class="bilan">'.$eleve['Classe'].'</td>';
           if (in_array($conversion_depuis_db[$eleve['g_choix_1']],$_POST['grp_prem'])){
             echo '<td class="bilan"><strong>'.$conversion_depuis_db[$eleve['g_choix_1']].'</strong></td>';}
           else {echo '<td class="bilan">'.$conversion_depuis_db[$eleve['g_choix_1']].'</td>';}
           if (in_array($conversion_depuis_db[$eleve['g_choix_2']],$_POST['grp_prem'])){
             echo '<td class="bilan"><strong>'.$conversion_depuis_db[$eleve['g_choix_2']].'</strong></td>';}
           else {echo '<td class="bilan">'.$conversion_depuis_db[$eleve['g_choix_2']].'</td>';}
           if (in_array($conversion_depuis_db[$eleve['g_choix_3']],$_POST['grp_prem'])){
             echo '<td class="bilan"><strong>'.$conversion_depuis_db[$eleve['g_choix_3']].'</strong></td>';}
           else {echo '<td class="bilan">'.$conversion_depuis_db[$eleve['g_choix_3']].'</td>';}
           if (in_array($conversion_depuis_db[$eleve['t_choix_1']],$_POST['grp_prem'])){
             echo '<td class="bilan"><strong>'.$conversion_depuis_db[$eleve['t_choix_1']].'</strong></td>
  </tr>';}
           else {echo '<td class="bilan">'.$conversion_depuis_db[$eleve['t_choix_1']].'</td>
  </tr>';}

        } //fin if
      } //fin while création eleve
      echo '</table>';
    } //fin if result num_rows>0

  } //fin if post[validate_prem]



  if (isset($_POST['validate_term'])){ //Affichage tableau terminale
  	$liste_classes = '("'.join('","',$_SESSION['liste_classes_premiere']).'")';
    $sql = 'SELECT Nom, Classe, term_choix_1, term_choix_2, term_choix_3, term_choix_4, term_choix_5, term_choix_6 FROM voeux_eleves WHERE Classe IN '.$liste_classes.' ORDER BY Nom';
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      echo '
<table style="padding-top:50px;margin:auto;">
  <tr>
    <td style="text-aligne:center; padding: 10px 25px 10px 25px;">Liste des élèves choisissant : 
<ul style="margin-bottom:30px;">';
      foreach ($_POST['grp_term'] as $choix_grp){
        echo '<li>'.$choix_grp.'</li>';
      } //fin foreach
      echo '
</ul></td>';
      if (isset($_POST['sans_term'])){
         echo '<td style="text-aligne:center; padding: 10px 25px 10px 25px;">et ne choisissant pas :
<ul style="margin-bottom:30px;">';
        foreach ($_POST['sans_prem'] as $choix_grp){
          echo '<li>'.$choix_grp.'</li>';
        } //fin foreach
      }// fin if
echo '
</ul><td></tr></table>
<table class="bilan">
  <tr>
    <th class="bilan">Nombre d\'élèves</th>
    <th class="bilan">Nom</th>
    <th class="bilan">Classe</th>
    <th class="bilan generale">1<sup>ère</sup> spé</th>
    <th class="bilan generale">2<sup>e</sup> spé</th>
    <th class="bilan option1">1<sup>ère</sup> option</th>
    <th class="bilan option1">2<sup>ère</sup> option</th>
    <th class="bilan option2">3<sup>ère</sup> option</th>
    <th class="bilan techno">Réorientation</th>
  </tr>';
      $numero_eleve = 1;
      while($eleve = $result->fetch_assoc()) {
        $eleve_OK = False;
        $eleve_sans = False;
        foreach ($_POST['grp_term'] as $choix_grp){
          if (in_array($conversion_vers_db[$choix_grp],array($eleve['term_choix_1'],$eleve['term_choix_2'],$eleve['term_choix_3'],$eleve['term_choix_4'],$eleve['term_choix_5'],$eleve['term_choix_6']))){
            $eleve_OK = True;
            break;
          } //fin if
        } //fin foreach
        if (isset($_POST['sans_term'])){
          foreach ($_POST['sans_term'] as $choix_grp){
            if (in_array($conversion_vers_db[$choix_grp],array($eleve['term_choix_1'],$eleve['term_choix_2'],$eleve['term_choix_3'],$eleve['term_choix_4'],$eleve['term_choix_5'],$eleve['term_choix_6']))){
              $eleve_sans = True;
              break;
            } //fin if
          } //fin foreach
        } //fin if
        if ($eleve_OK and !$eleve_sans){
          echo '
  <tr>
    <td class="bilan">'.$numero_eleve++.'</td>
    <td class="bilan">'.$eleve['Nom'].'</td>
    <td class="bilan">'.$eleve['Classe'].'</td>';
           if (in_array($conversion_depuis_db[$eleve['term_choix_1']],$_POST['grp_term']))
             {echo '<td class="bilan"><strong>'.$conversion_depuis_db[$eleve['term_choix_1']].'</strong></td>';}
           else {echo '<td class="bilan">'.$conversion_depuis_db[$eleve['term_choix_1']].'</td>';}

           if (in_array($conversion_depuis_db[$eleve['term_choix_2']],$_POST['grp_term']))
             {echo '<td class="bilan"><strong>'.$conversion_depuis_db[$eleve['term_choix_2']].'</strong></td>';}
           else {echo '<td class="bilan">'.$conversion_depuis_db[$eleve['term_choix_2']].'</td>';}

           if (in_array($conversion_depuis_db[$eleve['term_choix_3']],$_POST['grp_term']))
             {echo '<td class="bilan"><strong>'.$conversion_depuis_db[$eleve['term_choix_3']].'</strong></td>';}
           else {echo '<td class="bilan">'.$conversion_depuis_db[$eleve['term_choix_3']].'</td>';}

           if (in_array($conversion_depuis_db[$eleve['term_choix_4']],$_POST['grp_term']))
             {echo '<td class="bilan"><strong>'.$conversion_depuis_db[$eleve['term_choix_4']].'</strong></td>';}
           else {echo '<td class="bilan">'.$conversion_depuis_db[$eleve['term_choix_4']].'</td>';}

           if (in_array($conversion_depuis_db[$eleve['term_choix_5']],$_POST['grp_term'])){
             echo '<td class="bilan"><strong>'.$conversion_depuis_db[$eleve['term_choix_5']].'</strong></td>';}
           else {echo '<td class="bilan">'.$conversion_depuis_db[$eleve['term_choix_5']].'</td>';}

           if (in_array($conversion_depuis_db[$eleve['term_choix_6']],$_POST['grp_term'])){
             echo '<td class="bilan"><strong>'.$conversion_depuis_db[$eleve['term_choix_6']].'</strong></td>
  </tr>';}
           else {echo '<td class="bilan">'.$conversion_depuis_db[$eleve['term_choix_6']].'</td>
  </tr>';}

        } //fin if
      } //fin while création eleve
      echo '</table>';
    } //fin if result num_rows>0

  } //fin if post[validate_term]

  	echo '  
  	<footer class="noimprim"><p>2019-'.date('Y',time()).' - <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="https://licensebuttons.net/l/by-nc-sa/4.0/80x15.png" title="Ce site est mis à disposition selon les termes de la Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International."/></a> -  <a href="https://github.com/polro/orientation_lycee">Romuald Pol</a></p>
</footer>
</body>
</html>';
    $conn->close();
}
elseif (isset($_SESSION['nom'])){
  include 'menu.php';
  echo '<h1>Vous n\'avez pas accès à cette page '.$_SESSION['nom'].'. Vous venez de briser notre CONFIANCE.</h1>  
  <footer><p>2019-'.date('Y',time()).' - <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="https://licensebuttons.net/l/by-nc-sa/4.0/80x15.png" title="Ce site est mis à disposition selon les termes de la Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International."/></a> -  <a href="https://github.com/polro/orientation_lycee">Romuald Pol</a></p>
</footer>
  </body>
</html>';}

else{Header('Location:../index.php');}
?>