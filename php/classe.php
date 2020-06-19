<?php
session_start();

if (isset($_SESSION['nom'])){

  include 'decrypt_db.php';
  include 'menu.php';
  include 'connect.php';

  if (!isset($_POST['valider']) and !isset($_POST['reset']) and !isset($_POST['reset2'])){ // Page pour choisir
    
    if ($_SESSION['acces'] == '1') {
    	$sql = 'SELECT DATE_FORMAT(modif, "%e/%m/%Y à %H:%i:%s") as modif FROM voeux_pp WHERE Classe="'.$_SESSION['classe'].'"';
		$req = $conn->query($sql);
		$result = mysqli_fetch_assoc($req);
		$_SESSION['date_modif'] = $result['modif'];
    }


    //On récupère la base de données des vœeux
    echo '<p>Vous pouvez modifier les résultats dans la liste ci-dessous. <strong>Il faut valider les choix à la fin !</strong></p>';


    echo '<div style="display: flex;justify-content: space-around; max-width: 1000px;"><form method="post" action="classe.php">
            <input type="submit" name="reset" value="Réinitialiser les vœux">
          </form>
          <form method="post" action="eleves.php?option=ajout">
            <input type="submit" name="ajout" value="Ajouter un.e élève">
          </form>
          <form method="post" action="eleves.php?option=multi_ajout">
            <input type="submit" name="ajout" value="Ajouter plusieurs élèves">
          </form>
          <div id="bouton"><input type="button" value="Afficher récap" onClick="masquer_afficher()"></div></td>
          </div>';

//Affichage du tableau récapitulatif
	if (in_array($_SESSION['classe'],array('2A','2B','2C','2D','2E','2F','2G','2H'))) {
		$array_spe = array_slice($conversion_vers_db, 0, 18);
	} else {
		$array_spe = array('HGGSP'=>'g_spe_1','HLP'=>'g_spe_2', 'LLCE'=>'g_spe_3', 'Maths'=>'g_spe_4', 'SPC'=>'g_spe_5', 'SVT'=>'g_spe_6', 'SES'=>'g_spe_7', 'STMG'=>'t_spe_1', 'Latin-o'=>'option1', 'HDA'=>'option2', 'Alleuro'=>'option3', 'Mathscomp'=>'option4','Mathsexp'=>'option5','DGEMC'=>'option6');
	}

	echo '<div id="recap" style="display: none;"><table style="border-collapse: collapse;border: solid;text-align: center;margin: 30px 0px 30px 0px;">
	<caption>Tableau récapitulatif des vœux</caption>
	<tr style="border: solid;">
	  <th style="border: solid;">Spécialités</th>';

	foreach ($array_spe as $spe) {
		echo '<td style="border: solid;">'.$conversion_depuis_db[$spe].'</td>';
	}
	
	echo '</tr>
	<tr><th style="border: solid;">Nombres de vœux</th>';

	foreach ($array_spe as $spe) {
		if (in_array($_SESSION['classe'],$_SESSION['liste_classes_seconde'])) {
			$sql = 'SELECT COUNT(*) FROM voeux_eleves WHERE (Classe="'.$_SESSION['classe'].'" AND (g_choix_1 ="'.$spe.'" OR g_choix_2 ="'.$spe.'" OR g_choix_3 ="'.$spe.'" OR g_choix_4 ="'.$spe.'" OR t_choix_1 ="'.$spe.'" OR t_choix_2 ="'.$spe.'" OR a_choix_1 ="'.$spe.'")) ';
		} else {
			$sql = 'SELECT COUNT(*) FROM voeux_eleves WHERE (Classe="'.$_SESSION['classe'].'" AND (term_choix_1 ="'.$spe.'" OR term_choix_2 ="'.$spe.'" OR term_choix_3 ="'.$spe.'" OR term_choix_4 ="'.$spe.'" OR term_choix_5 ="'.$spe.'" OR term_choix_6 ="'.$spe.'")) ';
		}
		$result = $conn->query($sql);
		$count = mysqli_fetch_assoc($result);
		echo '<td style="border: solid;">'.$count['COUNT(*)'].'</td>';
	}

	echo '</tr></table></div>';

    if (in_array($_SESSION['classe'],$_SESSION['liste_classes_seconde'])){
    //Affichage du tableau des vœux de SECONDE
    echo '
    <p>Seul les 3 premiers choix de la voie générale et le premier choix de la voie technologique sont comptés pour déterminer le nombre de groupes de la page bilan. 
       <br/>Les vœux 1 à 4 de la voie générale ne se font que sur les spécialités du lycée, le vœux 5 sur une spécialité en dehors.<br/>Le vœux « Autre » correspond au redoublement ou à une réorientation en CFA.</p>
    <p>La dernière mise à jour à eu lieu le '.$_SESSION['date_modif'].'</p>

    <form name="voeux" method="post" action="classe.php">
      <table style="border-collapse: collapse;">
        <tr>
          <th rowspan="2" colspan="3">Élèves</th>
          <th colspan="5" class="generale">Voie Générale</th>
          <th colspan="2" class="techno">Voie Techno</th>
          <th rowspan="2" class="autre">Autre</th>
        </tr>
        <tr>
          <th class="generale">1<sup>er</sup> choix</th>
          <th class="generale">2<sup>e</sup> choix</th>
          <th class="generale">3<sup>e</sup> choix</th>
          <th class="generale">4<sup>e</sup> choix</th>
          <th class="generale">5<sup>e</sup> choix</th>
          <th class="techno">1<sup>er</sup> choix</th>
          <th class="techno">2<sup>e</sup> choix</th>
        </tr>';

    $sql = 'SELECT ID, Nom, g_choix_1, g_choix_2, g_choix_3, g_choix_4, g_choix_5, t_choix_1, t_choix_2, a_choix_1, voeux_g, voeux_t, voeux_a FROM voeux_eleves WHERE Classe="'.$_SESSION['classe'].'" ORDER BY Nom';
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $_SESSION['id_modif']=array();
        
      // output data of each row
        while($row = $result->fetch_assoc()) {
          array_push($_SESSION['id_modif'], $row['ID']);
          // On crééer les variables pour les choix
          $choix1 = "choix1_".$row["ID"];
          $choix2 = "choix2_".$row["ID"];
          $choix3 = "choix3_".$row["ID"];
          $choix4 = "choix4_".$row["ID"];
          $choix5 = "choix5_".$row["ID"];
          $choix6 = "choix6_".$row["ID"];
          $choix7 = "choix7_".$row["ID"];
          $choix8 = "choix8_".$row["ID"];


          // On veut afficher les choix déjà fait dès le départ
          // Variables pour afficher les résultats
          $choix_selected = array('choix_1'=>array('','','','','','',''), 'choix_2'=>array('','','','','','',''), 'choix_3'=>array('','','','','','',''), 'choix_4'=>array('','','','','','',''), 'choix_5'=>array('','','',''), 'choix_6'=>array('','','','',''), 'choix_7'=>array('','','','',''), 'choix_8'=>array('',''));
          
          // Pour les choix généraux
          for ($i=1;$i<=4;$i++){
            switch ($row['g_choix_'.$i]){
              case 'g_spe_1':
                $choix_selected['choix_'.$i][0] = 'selected';
                break;
              case 'g_spe_2':
                $choix_selected['choix_'.$i][1] = 'selected';
                break;
              case 'g_spe_3':
                $choix_selected['choix_'.$i][2] = 'selected';
                break;
              case 'g_spe_4':
                $choix_selected['choix_'.$i][3] = 'selected';
                break;
              case 'g_spe_5':
                $choix_selected['choix_'.$i][4] = 'selected';
                break;
              case 'g_spe_6':
                $choix_selected['choix_'.$i][5] = 'selected';
                break;
              case 'g_spe_7':
                $choix_selected['choix_'.$i][6] = 'selected';
                break;
            }
          }
          switch ($row['g_choix_5']){
            case 'g_spe_8':
              $choix_selected['choix_5'][0] = 'selected';
              break;
            case 'g_spe_9':
              $choix_selected['choix_5'][1] = 'selected';
              break;
            case 'g_spe_10':
              $choix_selected['choix_5'][2] = 'selected';
              break;
            case 'g_spe_11':
              $choix_selected['choix_5'][3] = 'selected';
              break;
          }
          // Pour les choix techno
          for ($i=1;$i<=2;$i++){
            $ichoix = $i + 5;
            switch ($row['t_choix_'.$i]){
              case 't_spe_1':
                $choix_selected['choix_'.$ichoix][0] = 'selected';
                break;
              case 't_spe_2':
                $choix_selected['choix_'.$ichoix][1] = 'selected';
                break;
              case 't_spe_3':
                $choix_selected['choix_'.$ichoix][2] = 'selected';
                break;
              case 't_spe_4':
                $choix_selected['choix_'.$ichoix][3] = 'selected';
                break;
              case 't_spe_5':
                $choix_selected['choix_'.$ichoix][4] = 'selected';
                break;
            }
          }
          // Pour les autres choix
          switch ($row['a_choix_1']){
            case 'a_spe_1':
              $choix_selected['choix_8'][0] = 'selected';
              break;
            case 'a_spe_2':
              $choix_selected['choix_8'][1] = 'selected';
              break;
          }

          if (($row['voeux_g'] + $row['voeux_t'] + $row['voeux_a']) == 0) {
          	$voeux_manquant = 'style="color: red;border-color: black;"';
          } else {$voeux_manquant = '';}
          

          // On affiche chaque ligne du tableau
          echo "
            <tr>
              <td class='choix'".$voeux_manquant.">".$row["Nom"]."</td>
              <td class='choix' width='25px'><a href='eleves.php?id=".$row["ID"]."&option=modif' title=\"Modifier le nom ou la classe de l'élève\"><img src='../images/modifier.jpeg' width='20px' height='20px'></a></td>
              <td class='choix' width='25px'><a href='eleves.php?id=".$row["ID"]."&option=supprimer' title=\"Supprimer l'élève de la base de données\"><img src='../images/supprimer.jpeg' width='20px' height='20px'></a> </td>
              <td class='generale choix'><select name='".$choix1."'>
              <OPTION value = '' >
              <OPTION value = 'HGGSP' ".$choix_selected['choix_1'][0].">Hist.-Géo GSP
              <OPTION value = 'HLP' ".$choix_selected['choix_1'][1].">Humanités LP
              <OPTION value = 'LLCE' ".$choix_selected['choix_1'][2].">LLCE
              <OPTION value = 'Maths' ".$choix_selected['choix_1'][3].">Maths
              <OPTION value = 'SPC' ".$choix_selected['choix_1'][4].">Phys.-Chim.
              <OPTION value = 'SVT' ".$choix_selected['choix_1'][5].">SVT
              <OPTION value = 'SES' ".$choix_selected['choix_1'][6].">SES
              </select></td>

              <td class='generale choix'><select name='".$choix2."'>
              <OPTION value = '' >
              <OPTION value = 'HGGSP' ".$choix_selected['choix_2'][0].">Hist.-Géo GSP
              <OPTION value = 'HLP' ".$choix_selected['choix_2'][1].">Humanités LP
              <OPTION value = 'LLCE' ".$choix_selected['choix_2'][2].">LLCE
              <OPTION value = 'Maths' ".$choix_selected['choix_2'][3].">Maths
              <OPTION value = 'SPC' ".$choix_selected['choix_2'][4].">Phys.-Chim.
              <OPTION value = 'SVT' ".$choix_selected['choix_2'][5].">SVT
              <OPTION value = 'SES' ".$choix_selected['choix_2'][6].">SES
              </select></td>

              <td class='generale choix'><select name='".$choix3."'>
              <OPTION value = '' >
              <OPTION value = 'HGGSP' ".$choix_selected['choix_3'][0].">Hist.-Géo GSP
              <OPTION value = 'HLP' ".$choix_selected['choix_3'][1].">Humanités LP
              <OPTION value = 'LLCE' ".$choix_selected['choix_3'][2].">LLCE
              <OPTION value = 'Maths' ".$choix_selected['choix_3'][3].">Maths
              <OPTION value = 'SPC' ".$choix_selected['choix_3'][4].">Phys.-Chim.
              <OPTION value = 'SVT' ".$choix_selected['choix_3'][5].">SVT
              <OPTION value = 'SES' ".$choix_selected['choix_3'][6].">SES
              </select></td>

              <td class='generale choix'><select name='".$choix4."'>
              <OPTION value = '' >
              <OPTION value = 'HGGSP' ".$choix_selected['choix_4'][0].">Hist.-Géo GSP
              <OPTION value = 'HLP' ".$choix_selected['choix_4'][1].">Humanités LP
              <OPTION value = 'LLCE' ".$choix_selected['choix_4'][2].">LLCE
              <OPTION value = 'Maths' ".$choix_selected['choix_4'][3].">Maths
              <OPTION value = 'SPC' ".$choix_selected['choix_4'][4].">Phys.-Chim.
              <OPTION value = 'SVT' ".$choix_selected['choix_4'][5].">SVT
              <OPTION value = 'SES' ".$choix_selected['choix_4'][6].">SES
              </select></td>

              <td class='generale choix'><select name='".$choix5."'>
              <OPTION value = '' >
              <OPTION value = 'Ing' ".$choix_selected['choix_5'][0].">Sc. Ingénieur
              <OPTION value = 'Num' ".$choix_selected['choix_5'][1].">Numérique
              <OPTION value = 'Latin' ".$choix_selected['choix_5'][2].">Latin
              <OPTION value = 'Arts' ".$choix_selected['choix_5'][3].">Arts
              </select></td>

              <td class='techno choix'><select name='".$choix6."'>
              <OPTION value = '' >
              <OPTION value = 'STMG' ".$choix_selected['choix_6'][0].">STMG
              <OPTION value = 'ST2S' ".$choix_selected['choix_6'][1].">ST2S
              <OPTION value = 'STI2D' ".$choix_selected['choix_6'][2].">STI2D
              <OPTION value = 'STD2A' ".$choix_selected['choix_6'][3].">STD2A
              <OPTION value = 'STL' ".$choix_selected['choix_6'][4].">STL
              </select></td>

              <td class='techno choix'><select name='".$choix7."'>
              <OPTION value = '' >
              <OPTION value = 'STMG' ".$choix_selected['choix_7'][0].">STMG
              <OPTION value = 'ST2S' ".$choix_selected['choix_7'][1].">ST2S
              <OPTION value = 'STI2D' ".$choix_selected['choix_7'][2].">STI2D
              <OPTION value = 'STD2A' ".$choix_selected['choix_7'][3].">STD2A
              <OPTION value = 'STL' ".$choix_selected['choix_7'][4].">STL
              </select></td>

              <td class='autre choix'><select name='".$choix8."'>
              <OPTION value = '' >
              <OPTION value = 'Pro' ".$choix_selected['choix_8'][0].">Pro
              <OPTION value = 'Autre' ".$choix_selected['choix_8'][1].">Autre
              </select></td>
              </tr>";
        }
    } //fin if si il y a au moins un élève
    } //fin if vœux seconde



    //Affichage du tableau des vœux de PREMIÈRE
    elseif (in_array($_SESSION['classe'],$_SESSION['liste_classes_premiere'])){

    echo '
    <p style="margin-bottom:20px;">Pour chaque élève, vous devez choisir les deux spécialités qu\'il ou elle veut garder.
    <br/>Puis s\'il ou elle le souhaite une ou deux option(s) parmi le pack 1 : Latin, HDA, Allemand euro.
    <br/>Puis s\'il ou elle le souhaite une option parmi le pack 2 : Mathématiques expertes ; Mathématiques complémentaires ; Droit et Grands Enjeux du Monde Contemporain.</p>
    <p><strong>Attention, il faut bien le préciser aux élèves : Le fait de choisir des spécialités ou des options ne vaut pas inscription, cela dépendra des spécialités ouvertes et des contraintes d\'emploi du temps pour les options (notamment pour le choix Latin+HDA).</strong></p>
    <p>La dernière mise à jour à eu lieu le '.$_SESSION['date_modif'].'</p>

    <form name="voeux" method="post" action="classe.php">
      <table style="border-collapse: collapse;">
        <tr>
          <th rowspan="2" colspan="3">Élèves</th>
          <th colspan="2" class="generale">Voie Générale</th>
          <th colspan="2" class="option1">Option pack 1</th>
          <th rowspan="2" class="option2">Option pack 2</th>
          <th rowspan="2" class="techno">Réorientation</th>
        </tr>
        <tr>
          <th class="generale">1<sup>er</sup> choix</th>
          <th class="generale">2<sup>e</sup> choix</th>
          <th class="option1">1<sup>er</sup> choix</th>
          <th class="option1">2<sup>e</sup> choix</th>
        </tr>';

    $sql = 'SELECT ID, Nom, term_choix_1, term_choix_2, term_choix_3, term_choix_4, term_choix_5, term_choix_6 FROM voeux_eleves WHERE Classe="'.$_SESSION['classe'].'" ORDER BY Nom';
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $_SESSION['id_modif']=array();
        
      // output data of each row
        while($row = $result->fetch_assoc()) {
          array_push($_SESSION['id_modif'], $row['ID']);
          // On crééer les variables pour les choix
          $choix1 = "choix1_".$row["ID"];
          $choix2 = "choix2_".$row["ID"];
          $choix3 = "choix3_".$row["ID"];
          $choix4 = "choix4_".$row["ID"];
          $choix5 = "choix5_".$row["ID"];
          $choix6 = "choix6_".$row["ID"];

          // On veut afficher les choix déjà fait dès le départ
          // Variables pour afficher les résultats
          $choix_selected = array('choix_1'=>array('','','','','','',''), 'choix_2'=>array('','','','','','',''), 'choix_3'=>array('','',''),  'choix_4'=>array('','',''), 'choix_5'=>array('','',''), 'choix_6'=>array(''));
          
          // Pour les choix de spécialités
          for ($i=1;$i<=2;$i++){
            switch ($row['term_choix_'.$i]){
              case $conversion_vers_db['HGGSP']:
                $choix_selected['choix_'.$i][0] = 'selected';
                break;
              case $conversion_vers_db['HLP']:
                $choix_selected['choix_'.$i][1] = 'selected';
                break;
              case $conversion_vers_db['LLCE']:
                $choix_selected['choix_'.$i][2] = 'selected';
                break;
              case $conversion_vers_db['Maths']:
                $choix_selected['choix_'.$i][3] = 'selected';
                break;
              case $conversion_vers_db['SPC']:
                $choix_selected['choix_'.$i][4] = 'selected';
                break;
              case $conversion_vers_db['SVT']:
                $choix_selected['choix_'.$i][5] = 'selected';
                break;
              case $conversion_vers_db['SES']:
                $choix_selected['choix_'.$i][6] = 'selected';
                break;
            } //fin swicth
          }// fin for
          // Pour les choix d'option pack 1
          for ($i=3;$i<=4;$i++){
            switch ($row['term_choix_'.$i]){
              case $conversion_vers_db['Latin-o']:
                $choix_selected['choix_'.$i][0] = 'selected';
                break;
              case $conversion_vers_db['HDA']:
                $choix_selected['choix_'.$i][1] = 'selected';
                break;
              case $conversion_vers_db['Alleuro']:
                $choix_selected['choix_'.$i][2] = 'selected';
                break;
            } //fin swicth
          } //fin for
          // Pour les choix d'option pack 2
          switch ($row['term_choix_5']){
            case $conversion_vers_db['Mathscomp']:
              $choix_selected['choix_5'][0] = 'selected';
              break;
            case $conversion_vers_db['Mathsexp']:
              $choix_selected['choix_5'][1] = 'selected';
              break;
            case $conversion_vers_db['DGEMC']:
              $choix_selected['choix_5'][2] = 'selected';
              break;
            } //fin swicth
          // Pour la réorientation
          if ($row['term_choix_6']==$conversion_vers_db['STMG']){
            $choix_selected['choix_6'][0] = 'selected';
          }

          if (($row['term_choix_1'] == '') | ($row['term_choix_2'] == '')) {
          	$voeux_manquant = 'style="color: red;border-color: black;"';
          } else {$voeux_manquant = '';}
          

          // On affiche chaque ligne du tableau
          echo '
            <tr>
              <td class="choix" '.$voeux_manquant.'>'.$row['Nom'].'</td>
              <td class="choix" width="25px"><a href="eleves.php?id='.$row["ID"].'&option=modif" title="Modifier le nom ou la classe de l\'élève"><img src="../images/modifier.jpeg" width="20px" height="20px" alt="Modifier"></a></td>
              <td class="choix" width="25px"><a href="eleves.php?id='.$row["ID"].'&option=supprimer" title="Supprimer l\'élève de la base de données"><img src="../images/supprimer.jpeg" width="20px" height="20px"></a></td>
              <td class="generale choix"><select name="'.$choix1.'">
              <OPTION value = "" >
              <OPTION value = "HGGSP" '.$choix_selected['choix_1'][0].'>Hist.-Géo GSP
              <OPTION value = "HLP" '.$choix_selected['choix_1'][1].'>Humanités LP
              <OPTION value = "LLCE" '.$choix_selected['choix_1'][2].'>LLCE
              <OPTION value = "Maths" '.$choix_selected['choix_1'][3].'>Maths
              <OPTION value = "SPC" '.$choix_selected['choix_1'][4].'>Phys.-Chim.
              <OPTION value = "SVT" '.$choix_selected['choix_1'][5].'>SVT
              <OPTION value = "SES" '.$choix_selected['choix_1'][6].'>SES
              </select></td>

              <td class="generale choix"><select name="'.$choix2.'">
              <OPTION value = "" >
              <OPTION value = "HGGSP" '.$choix_selected['choix_2'][0].'>Hist.-Géo GSP
              <OPTION value = "HLP" '.$choix_selected['choix_2'][1].'>Humanités LP
              <OPTION value = "LLCE" '.$choix_selected['choix_2'][2].'>LLCE
              <OPTION value = "Maths" '.$choix_selected['choix_2'][3].'>Maths
              <OPTION value = "SPC" '.$choix_selected['choix_2'][4].'>Phys.-Chim.
              <OPTION value = "SVT" '.$choix_selected['choix_2'][5].'>SVT
              <OPTION value = "SES" '.$choix_selected['choix_2'][6].'>SES
              </select></td>

              <td class="option1 choix"><select name="'.$choix3.'">
              <OPTION value = "" >
              <OPTION value = "Latin-o" '.$choix_selected['choix_3'][0].'>Latin
              <OPTION value = "HDA" '.$choix_selected['choix_3'][1].'>HDA
              <OPTION value = "Alleuro" '.$choix_selected['choix_3'][2].'>Allemand euro
              </select></td>

              <td class="option1 choix"><select name="'.$choix4.'">
              <OPTION value = "" >
              <OPTION value = "Latin-o" '.$choix_selected['choix_4'][0].'>Latin
              <OPTION value = "HDA" '.$choix_selected['choix_4'][1].'>HDA
              <OPTION value = "Alleuro" '.$choix_selected['choix_4'][2].'>Allemand euro
              </select></td>

              <td class="option2 choix"><select name="'.$choix5.'">
              <OPTION value = "" >
              <OPTION value = "Mathscomp" '.$choix_selected['choix_5'][0].'>Maths complémentaires
              <OPTION value = "Mathsexp" '.$choix_selected['choix_5'][1].'>Maths expertes
              <OPTION value = "DGEMC" '.$choix_selected['choix_5'][2].'>DGEMC
              </select></td>

              <td class="techno choix"><select name="'.$choix6.'">
              <OPTION value = "" >
              <OPTION value = "STMG" '.$choix_selected['choix_6'][0].'>STMG
              </select></td>
            </tr>';
        }
    } //fin if si il y a au moins un élève
    } //fin if vœux de PREMIÈRE

    
    echo '
          <tr><td colspan="9" style="text-align:center"><input type="submit" name="valider" value="Valider"></td></tr></table></form>';
  } //fin page pour choisir





  // Si on a validé des choix
  elseif (isset($_POST['valider'])){
      
      // Mise à jour de la base de données
      $ok = True; // Variable pour vérifier que la maj a bien été faite
      foreach ($_SESSION['id_modif'] as $id){


        //Modif élèves de SECONDE
        if (in_array($_SESSION['classe'],$_SESSION['liste_classes_seconde'])){

          $set = 'g_choix_1="'.$conversion_vers_db[$_POST['choix1_'.$id]].'", g_choix_2="'.$conversion_vers_db[$_POST['choix2_'.$id]].'", g_choix_3="'.$conversion_vers_db[$_POST['choix3_'.$id]].'", g_choix_4="'.$conversion_vers_db[$_POST['choix4_'.$id]].'", g_choix_5="'.$conversion_vers_db[$_POST['choix5_'.$id]].'", t_choix_1="'.$conversion_vers_db[$_POST['choix6_'.$id]].'", t_choix_2="'.$conversion_vers_db[$_POST['choix7_'.$id]].'", a_choix_1="'.$conversion_vers_db[$_POST['choix8_'.$id]].'"';


        //Modif élèves de PREMIERE
        }else{
          $set = 'term_choix_1="'.$conversion_vers_db[$_POST['choix1_'.$id]].'", term_choix_2="'.$conversion_vers_db[$_POST['choix2_'.$id]].'", term_choix_3="'.$conversion_vers_db[$_POST['choix3_'.$id]].'", term_choix_4="'.$conversion_vers_db[$_POST['choix4_'.$id]].'", term_choix_5="'.$conversion_vers_db[$_POST['choix5_'.$id]].'", term_choix_6="'.$conversion_vers_db[$_POST['choix6_'.$id]].'"';
        } //fin modif premiere
        


        // On injecte cette liste dans la base de données
        $sql = "UPDATE voeux_eleves SET ".$set." WHERE ID=".$id;
        if ($conn->query($sql) === TRUE) {echo "";}else {$ok = False;}
       

     if (in_array($_SESSION['classe'],$_SESSION['liste_classes_seconde'])){
        // On cherche à savoir si l'élève de seconde à fait un choix dans la voie concernée
        if (($_POST['choix1_'.$id]=='') and ($_POST['choix2_'.$id]=='') and ($_POST['choix3_'.$id]=='') and ($_POST['choix4_'.$id]=='') and ($_POST['choix5_'.$id]=='')){
          $sql = 'UPDATE voeux_eleves SET voeux_g="0" WHERE ID='.$id;
          $conn->query($sql);}
        else{
          $sql = 'UPDATE voeux_eleves SET voeux_g="1" WHERE ID='.$id;
          $conn->query($sql);}
        
        if (($_POST['choix6_'.$id]=='') and ($_POST['choix7_'.$id]=='')){
          $sql = 'UPDATE voeux_eleves SET voeux_t="0" WHERE ID='.$id;
          $conn->query($sql);}
        else{
          $sql = 'UPDATE voeux_eleves SET voeux_t="1" WHERE ID='.$id;
          $conn->query($sql);}
        
        if ($_POST['choix8_'.$id] == ''){
          $sql = 'UPDATE voeux_eleves SET voeux_a="0" WHERE ID='.$id;
          $conn->query($sql);}
        else{
          $sql = 'UPDATE voeux_eleves SET voeux_a="1" WHERE ID='.$id;
          $conn->query($sql);}
      } //fin if

      } // Fin de la boucle foreach
     


      // Mise à jour de la dernière modif + formatage de la date pour affichage
      $date = date('Y-m-d').' '.date('H:i:s');
      $sql = 'UPDATE voeux_pp SET modif="'.$date.'" WHERE Classe="'.$_SESSION['classe'].'"';
      $conn->query($sql);
      $sql = 'SELECT DATE_FORMAT(modif, "%e/%m/%Y à %H:%i:%s") as modif FROM voeux_pp WHERE Classe="'.$_SESSION['classe'].'"';
      $req = $conn->query($sql);
      $result = $req->fetch_assoc();
      $_SESSION['date_modif'] = $result['modif'];


      if ($ok){
        echo '<h2>Bravo vous avez bien travaillé !</h2>';
        if ($_SESSION['login'] != 'admin' and in_array($_SESSION['classe'],$_SESSION['liste_classes_seconde'])) {
          if (getdate()['seconds']%3 == 0){echo '<img src="images/valider.jpg" alt="valider"/>';}}
        echo '<p>La mise à jour a été faite, <a href="classe.php">cliquez ici</a> pour voir le résultat</p>';}
      else{echo '<p>Il y a un problème dans la mise à jour, prévenez Romuald !</p>';}
    }

  // Si l'utilisateur a appuyé sur Réinitialiser
  elseif (isset($_POST['reset'])){
    echo '<p> Voulez-vous vraiment réinitialiser les vœux de la classe de '.$_SESSION['classe'].' ? <br/>Cette opération est irréversible, il faudra ensuite saisir tous les vœux de la classe.</p>
          <form method="post" action="classe.php">
            <input type="submit" name="reset2" value="Réinitialiser les vœux">
          </form>';
  }

  // Si l'utilisateur a confirmer la réinitialisation
  elseif (isset($_POST['reset2'])){
    foreach ($_SESSION['id_modif'] as $id){
      if (in_array($_SESSION['classe'],$_SESSION['liste_classes_seconde'])){
        $set = 'g_choix_1="", g_choix_2="", g_choix_3="", g_choix_4="", g_choix_5="", voeux_g="0", t_choix_1="", t_choix_2="",voeux_t="0", a_choix_1="", voeux_a="0"';
      }else{
        $set = 'term_choix_1="", term_choix_2="", term_choix_3="", term_choix_4="", term_choix_5="", term_choix_6=""';
      }
      $sql = "UPDATE voeux_eleves SET ".$set." WHERE ID=".$id;
      $conn->query($sql);
    }
    
    echo '<p>La réinitialisation a été effectuée. <a href="classe.php">Cliquez ici</a> pour voir le résultat.</p>';
      

      // Réinitialisation de la date de dernière modif + formatage de la date pour affichage
      $sql = 'UPDATE voeux_pp SET modif="2019-09-01 00:00:00" WHERE Classe="'.$_SESSION['classe'].'"';
      $conn->query($sql);
      $sql = 'SELECT DATE_FORMAT(modif, "%e/%m/%Y à %H:%i:%s") as modif FROM voeux_pp WHERE Classe="'.$_SESSION['classe'].'"';
      $req = $conn->query($sql);
      $result = $req->fetch_assoc();
      $_SESSION['date_modif'] = $result['modif'];

  }
  echo '  </div>
  <footer><p>2019-'.date('Y',time()).' - <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="https://licensebuttons.net/l/by-nc-sa/4.0/80x15.png" title="Ce site est mis à disposition selon les termes de la Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International."/></a> -  <a href="https://github.com/polro/orientation_lycee">Romuald Pol</a></p>
</footer>
</body>
</html>';
    $conn->close();
}
else{Header('Location:../index.php');}
?>