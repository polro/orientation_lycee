<?php
// Démarrage de la session
session_start();

if (isset($_SESSION['nom'])){
include 'connect.php';
$conversion_depuis_db = array('g_spe_1' =>'Hist','g_spe_2'=>'Litt', 'g_spe_3'=>'Langues', 'g_spe_4'=>'Maths', 'g_spe_5'=>'SPC', 'g_spe_6'=>'SVT', 'g_spe_7'=>'SES', 'g_spe_8'=>'Ing', 'g_spe_9'=>'Num', 'g_spe_10'=>'Latin', 'g_spe_11'=>'Arts', 't_spe_1'=>'STMG', 't_spe_2'=>'ST2S', 't_spe_3'=>'STI2D', 't_spe_4'=>'STD2A', 't_spe_5'=>'STL', 'a_spe_1'=>'Pro', 'a_spe_2'=>'Autre');
$conversion_vers_db = array('Hist'=>'g_spe_1','Litt'=>'g_spe_2', 'Langues'=>'g_spe_3', 'Maths'=>'g_spe_4', 'SPC'=>'g_spe_5', 'SVT'=>'g_spe_6', 'SES'=>'g_spe_7', 'Ing'=>'g_spe_8', 'Num'=>'g_spe_9', 'Latin'=>'g_spe_10', 'Arts'=>'g_spe_11', 'STMG'=>'t_spe_1', 'ST2S'=>'t_spe_2', 'STI2D'=>'t_spe_3', 'STD2A'=>'t_spe_4', 'STL'=>'t_spe_5', 'Pro'=>'a_spe_1', 'Autre'=>'a_spe_2');
  // On affiche l'entête de la page 
  include 'menu.php';

  $sql = 'SELECT MAX(DATE_FORMAT(modif, "%e/%m/%Y à %Hh %i")) as modif FROM voeux_pp WHERE Classe="2A" OR Classe="2B" OR Classe="2C" OR Classe="2D" OR Classe="2E" OR Classe="2F" OR Classe="2G" OR Classe="2H"';
  $req = $conn->query($sql);
  $result = $req->fetch_assoc();
  echo '<div class="noimprim">
    <p>La dernière mise à jour du lycée en seconde a été faite le '.$result['modif'].'.</p>';



// On regarde les vœux manquant
$voeux_manquant_2a = 0;$voeux_manquant_2b = 0;$voeux_manquant_2c = 0;
$voeux_manquant_2d = 0;$voeux_manquant_2e = 0;$voeux_manquant_2f = 0;
$voeux_manquant_2g = 0;$voeux_manquant_2h = 0;
$voeux_multiples = 0;
$classes = array('2A','2B','2C','2D','2E','2F','2G','2H');

foreach ($classes as $classe){
  $sql = 'SELECT voeux_g, voeux_t, voeux_a FROM voeux_eleves WHERE Classe="'.$classe.'"';
  $req = $conn->query($sql);
  while($eleve = $req->fetch_assoc()) {
    if ($eleve['voeux_g']+$eleve['voeux_t']+$eleve['voeux_a'] == 0){
      switch ($classe){
        case '2A':
          $voeux_manquant_2a++;
          break;
        case '2B':
          $voeux_manquant_2b++;
          break;
        case '2C':
          $voeux_manquant_2c++;
          break;
        case '2D':
          $voeux_manquant_2d++;
          break;
        case '2E':
          $voeux_manquant_2e++;
          break;
        case '2F':
          $voeux_manquant_2f++;
          break;
        case '2G':
          $voeux_manquant_2g++;
          break;
        case '2H':
          $voeux_manquant_2h++;
          break;
      } //fin switch
    } //fin if
  
    if ($eleve['voeux_g']+$eleve['voeux_t']+$eleve['voeux_a'] > 1){
      $voeux_multiples++;}

  } //fin while
} //fin foreach

$total_manquant = $voeux_manquant_2a+$voeux_manquant_2b+$voeux_manquant_2c+$voeux_manquant_2d+$voeux_manquant_2e+$voeux_manquant_2f+$voeux_manquant_2g+$voeux_manquant_2h;

echo '<p>Il y a <strong>'.$voeux_multiples.' vœux multiples</strong>, i.e. des élèves ont fait des choix dans au moins 2 voies (générale, technologique, professionnelle). 
<br/><br/><strong>Il manque encore '.$total_manquant.' élèves</strong> dont les vœux n\'ont pas été saisis. Voici la répartition :</p>
<table>
  <tr>
    <th class="bilan">Classe</th>
    <th class="bilan">2A</th>
    <th class="bilan">2B</th>
    <th class="bilan">2C</th>
    <th class="bilan">2D</th>
    <th class="bilan">2E</th>
    <th class="bilan">2F</th>
    <th class="bilan">2G</th>
    <th class="bilan">2H</th>
  </tr>
  <tr>
    <td class="bilan">Nbr d\'élèves<br/>manquants</td>
    <td class="bilan">'.$voeux_manquant_2a.'</td>
    <td class="bilan">'.$voeux_manquant_2b.'</td>
    <td class="bilan">'.$voeux_manquant_2c.'</td>
    <td class="bilan">'.$voeux_manquant_2d.'</td>
    <td class="bilan">'.$voeux_manquant_2e.'</td>
    <td class="bilan">'.$voeux_manquant_2f.'</td>
    <td class="bilan">'.$voeux_manquant_2g.'</td>
    <td class="bilan">'.$voeux_manquant_2h.'</td>
  </tr>
</table>
<p>Les nombres entre parenthèses représentent le nombre d\'élèves qui n\'ont fait des vœux qu\'en générale et sans vœux hors lycée.</p>
</div>';


//On prépare le tableau
$nbr_choix = array('Hist'=>array(0,0,0,0), 'Litt'=>array(0,0,0,0), 'Langues'=>array(0,0,0,0), 'Maths'=>array(0,0,0,0), 'SPC'=>array(0,0,0,0), 'SVT'=>array(0,0,0,0), 'SES'=>array(0,0,0,0), 'Ing'=>array(0), 'Num'=>array(0), 'Latin'=>array(0), 'Arts'=>array(0), 'STMG'=>array(0,0), 'ST2S'=>array(0,0), 'STI2D'=>array(0,0), 'STD2A'=>array(0,0), 'STL'=>array(0,0), 'Pro'=>array(0), 'Autre'=>array(0));

$nbr_choix_min = array('Hist'=>array(0,0,0,0), 'Litt'=>array(0,0,0,0), 'Langues'=>array(0,0,0,0), 'Maths'=>array(0,0,0,0), 'SPC'=>array(0,0,0,0), 'SVT'=>array(0,0,0,0), 'SES'=>array(0,0,0,0));

$sql = 'SELECT g_choix_1, g_choix_2, g_choix_3, g_choix_4, g_choix_5, voeux_g, voeux_t, voeux_a FROM voeux_eleves WHERE classe IN ("2A","2B","2C","2D","2E","2F","2G","2H")';
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($eleve = $result->fetch_assoc()) {
    if ($eleve['voeux_g'] == 1){
      for ($i=1;$i<=4;$i++){
        $nbr_choix[$conversion_depuis_db[$eleve['g_choix_'.$i]]][$i-1]++;
        if (($eleve['voeux_t']+$eleve['voeux_a']>1) or $eleve['g_choix_5'] != ''){
          $nbr_choix_min[$conversion_depuis_db[$eleve['g_choix_'.$i]]][$i-1]++;
        } //fin if
      }//fin for
    }//fin if
  } //fin while
} //fin if

foreach ($conversion_vers_db as $decrypt => $crypt){
  if (in_array($decrypt,array('Hist', 'Litt', 'Langues', 'Maths', 'SPC', 'SES', 'SVT'))){ //on compte les vœux en général au lycée
    /*for ($i=1;$i<=4;$i++){
      $sql = 'SELECT COUNT(g_choix_'.$i.') AS '.$decrypt.' FROM voeux_eleves WHERE g_choix_'.$i.'="'.$crypt.'"';
      $req = $conn->query($sql);
      $result = $req->fetch_assoc();
      $nbr_choix[$decrypt][$i-1] = $result[$decrypt];
    }//fin for */
  }//fin if
  elseif (in_array($decrypt,array('Ing', 'Num', 'Latin','Arts'))){ //on compte les vœux en général hors lycée
    $sql = 'SELECT COUNT(g_choix_5) AS '.$decrypt.' FROM voeux_eleves WHERE g_choix_5="'.$crypt.'"';
    $req = $conn->query($sql);
    $result = $req->fetch_assoc();
    $nbr_choix[$decrypt][0] = $result[$decrypt];
  }//fin elseif
  elseif (in_array($decrypt,array('STMG', 'ST2S', 'STL', 'STI2D', 'STD2A'))){
    for ($i=1;$i<=2;$i++){ // on compte les vœux en technologie
      $sql = 'SELECT COUNT(t_choix_'.$i.') AS '.$decrypt.' FROM voeux_eleves WHERE t_choix_'.$i.'="'.$crypt.'"';
      $req = $conn->query($sql);
      $result = $req->fetch_assoc();
      $nbr_choix[$decrypt][$i-1] = $result[$decrypt];
    }//fin for
  }//fin elseif
  else{
    // on compte les vœux en pro et autre
    $sql = 'SELECT COUNT(a_choix_1) AS '.$decrypt.' FROM voeux_eleves WHERE a_choix_1="'.$crypt.'"';
    $req = $conn->query($sql);
    $result = $req->fetch_assoc();
    $nbr_choix[$decrypt][0] = $result[$decrypt];
  }//fin else
} //fin foreach



// Création du nombre de groupes
$nbr_grp = array('Hist'=>0, 'Litt'=>0, 'Langues'=>0, 'Maths'=>0, 'SPC'=>0, 'SES'=>0, 'SVT'=>0, 'STMG'=>0);
foreach (array('Hist', 'Litt', 'Langues', 'Maths', 'SPC', 'SES', 'SVT', 'STMG') as $spe){
  if ($spe == 'STMG'){
    if ($nbr_choix[$spe][0]%24==0){$nbr_grp[$spe] = (int)($nbr_choix[$spe][0]/24);}
    else{$nbr_grp[$spe] = (int)($nbr_choix[$spe][0]/24) + 1;}
  } //fin if
  else{
    $nbr_eleves = array_sum(array_slice($nbr_choix[$spe],0,3));
    if ($nbr_eleves%35==0){$nbr_grp[$spe] = (int)($nbr_eleves/35);}
    else{$nbr_grp[$spe] = (int)($nbr_eleves/35) + 1;}
  } //fin else
} //fin foreach

// Création du nombre de groupes minimum
$nbr_grp_min = array('Hist'=>0, 'Litt'=>0, 'Langues'=>0, 'Maths'=>0, 'SPC'=>0, 'SES'=>0, 'SVT'=>0);
foreach (array('Hist', 'Litt', 'Langues', 'Maths', 'SPC', 'SES', 'SVT') as $spe){
    $nbr_eleves = array_sum(array_slice($nbr_choix_min[$spe],0,3));
    if ($nbr_eleves%35==0){$nbr_grp_min[$spe] = (int)($nbr_eleves/35);}
    else{$nbr_grp_min[$spe] = (int)($nbr_eleves/35) + 1;}
} //fin foreach




// On affiche les tableaux
echo '
<table class="bilan">
  <caption class="generale">Voie Générale</caption>
  <tr>
    <th class="bilan">Spécialité</th>
    <th class="bilan">Histoire</th>
    <th class="bilan">Littérature</th>
    <th class="bilan">Langues</th>
    <th class="bilan">Maths</th>
    <th class="bilan">Sc. Phys.-Ch.</th>
    <th class="bilan">S.V.T.</th>
    <th class="bilan">S.E.S.</th>
    <th class="bilan">Sc. Ingénieur</th>
    <th class="bilan">Numérique</th>
    <th class="bilan">Latin</th>
    <th class="bilan">Arts</th>
  <tr>
  <tr>
    <td class="bilan">Nombre de vœux 1</td>
    <td class="bilan">'.$nbr_choix['Hist'][0].' ('.$nbr_choix_min['Hist'][0].')</td>
    <td class="bilan">'.$nbr_choix['Litt'][0].' ('.$nbr_choix_min['Litt'][0].')</td>
    <td class="bilan">'.$nbr_choix['Langues'][0].' ('.$nbr_choix_min['Langues'][0].')</td>
    <td class="bilan">'.$nbr_choix['Maths'][0].' ('.$nbr_choix_min['Maths'][0].')</td>
    <td class="bilan">'.$nbr_choix['SPC'][0].' ('.$nbr_choix_min['SPC'][0].')</td>
    <td class="bilan">'.$nbr_choix['SVT'][0].' ('.$nbr_choix_min['SVT'][0].')</td>
    <td class="bilan">'.$nbr_choix['SES'][0].' ('.$nbr_choix_min['SES'][0].')</td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
  </tr>
  <tr>
    <td class="bilan">Nombre de vœux 2</td>
    <td class="bilan">'.$nbr_choix['Hist'][1].' ('.$nbr_choix_min['Hist'][1].')</td>
    <td class="bilan">'.$nbr_choix['Litt'][1].' ('.$nbr_choix_min['Litt'][1].')</td>
    <td class="bilan">'.$nbr_choix['Langues'][1].' ('.$nbr_choix_min['Langues'][1].')</td>
    <td class="bilan">'.$nbr_choix['Maths'][1].' ('.$nbr_choix_min['Maths'][1].')</td>
    <td class="bilan">'.$nbr_choix['SPC'][1].' ('.$nbr_choix_min['SPC'][1].')</td>
    <td class="bilan">'.$nbr_choix['SVT'][1].' ('.$nbr_choix_min['SVT'][1].')</td>
    <td class="bilan">'.$nbr_choix['SES'][1].' ('.$nbr_choix_min['SES'][1].')</td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
  </tr>
  <tr>
    <td class="bilan">Nombre de vœux 3</td>
    <td class="bilan">'.$nbr_choix['Hist'][2].' ('.$nbr_choix_min['Hist'][2].')</td>
    <td class="bilan">'.$nbr_choix['Litt'][2].' ('.$nbr_choix_min['Litt'][2].')</td>
    <td class="bilan">'.$nbr_choix['Langues'][2].' ('.$nbr_choix_min['Langues'][2].')</td>
    <td class="bilan">'.$nbr_choix['Maths'][2].' ('.$nbr_choix_min['Maths'][2].')</td>
    <td class="bilan">'.$nbr_choix['SPC'][2].' ('.$nbr_choix_min['SPC'][2].')</td>
    <td class="bilan">'.$nbr_choix['SVT'][2].' ('.$nbr_choix_min['SVT'][2].')</td>
    <td class="bilan">'.$nbr_choix['SES'][2].' ('.$nbr_choix_min['SES'][2].')</td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
  </tr>
  <tr>
    <td class="bilan">Nombre de vœux 4</td>
    <td class="bilan">'.$nbr_choix['Hist'][3].' ('.$nbr_choix_min['Hist'][3].')</td>
    <td class="bilan">'.$nbr_choix['Litt'][3].' ('.$nbr_choix_min['Litt'][3].')</td>
    <td class="bilan">'.$nbr_choix['Langues'][3].' ('.$nbr_choix_min['Langues'][3].')</td>
    <td class="bilan">'.$nbr_choix['Maths'][3].' ('.$nbr_choix_min['Maths'][3].')</td>
    <td class="bilan">'.$nbr_choix['SPC'][3].' ('.$nbr_choix_min['SPC'][3].')</td>
    <td class="bilan">'.$nbr_choix['SVT'][3].' ('.$nbr_choix_min['SVT'][3].')</td>
    <td class="bilan">'.$nbr_choix['SES'][3].' ('.$nbr_choix_min['SES'][3].')</td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
  </tr>
  <tr>
    <td class="bilan">Nombre de vœux <br/>hors lycée</td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan">'.$nbr_choix['Ing'][0].'</td>
    <td class="bilan">'.$nbr_choix['Num'][0].'</td>
    <td class="bilan">'.$nbr_choix['Latin'][0].'</td>
    <td class="bilan">'.$nbr_choix['Arts'][0].'</td>
  </tr>
  <tr>
    <td class="bilan">Nombre d\'élèves<br/>sur vœux 1, 2 et 3</td>
    <td class="bilan">'.array_sum(array_slice($nbr_choix['Hist'],0,3)).' ('.array_sum(array_slice($nbr_choix_min['Hist'],0,3)).')</td>
    <td class="bilan">'.array_sum(array_slice($nbr_choix['Litt'],0,3)).' ('.array_sum(array_slice($nbr_choix_min['Litt'],0,3)).')</td>
    <td class="bilan">'.array_sum(array_slice($nbr_choix['Langues'],0,3)).' ('.array_sum(array_slice($nbr_choix_min['Langues'],0,3)).')</td>
    <td class="bilan">'.array_sum(array_slice($nbr_choix['Maths'],0,3)).' ('.array_sum(array_slice($nbr_choix_min['Maths'],0,3)).')</td>
    <td class="bilan">'.array_sum(array_slice($nbr_choix['SPC'],0,3)).' ('.array_sum(array_slice($nbr_choix_min['SPC'],0,3)).')</td>
    <td class="bilan">'.array_sum(array_slice($nbr_choix['SVT'],0,3)).' ('.array_sum(array_slice($nbr_choix_min['SVT'],0,3)).')</td>
    <td class="bilan">'.array_sum(array_slice($nbr_choix['SES'],0,3)).' ('.array_sum(array_slice($nbr_choix_min['SES'],0,3)).')</td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
  </tr>
  <tr>
    <td class="bilan">Nombre de groupes<br/>de 35 élèves<br/>sur vœux 1, 2 et 3</td>
    <td class="bilan">'.$nbr_grp['Hist'].' ('.$nbr_grp_min['Hist'].')</td>
    <td class="bilan">'.$nbr_grp['Litt'].' ('.$nbr_grp_min['Litt'].')</td>
    <td class="bilan">'.$nbr_grp['Langues'].' ('.$nbr_grp_min['Langues'].')</td>
    <td class="bilan">'.$nbr_grp['Maths'].' ('.$nbr_grp_min['Maths'].')</td>
    <td class="bilan">'.$nbr_grp['SPC'].' ('.$nbr_grp_min['SPC'].')</td>
    <td class="bilan">'.$nbr_grp['SVT'].' ('.$nbr_grp_min['SVT'].')</td>
    <td class="bilan">'.$nbr_grp['SES'].' ('.$nbr_grp_min['SES'].')</td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
  </tr>
</table>
<table class="bilan">
  <caption class="techno">Voie Technologique</caption>
  <tr>
    <th class="bilan">Spécialité</th>
    <th class="bilan">STMG</th>
    <th class="bilan">ST2S</th>
    <th class="bilan">STI2D</th>
    <th class="bilan">STD2A</th>
    <th class="bilan">STL</th>
  </tr>
  <tr>
    <td class="bilan">Nombre de vœux 1</td>
    <td class="bilan">'.$nbr_choix['STMG'][0].'</td>
    <td class="bilan">'.$nbr_choix['ST2S'][0].'</td>
    <td class="bilan">'.$nbr_choix['STI2D'][0].'</td>
    <td class="bilan">'.$nbr_choix['STD2A'][0].'</td>
    <td class="bilan">'.$nbr_choix['STL'][0].'</td>
  </tr>
  <tr>
    <td class="bilan">Nombre de vœux 2</td>
    <td class="bilan">'.$nbr_choix['STMG'][1].'</td>
    <td class="bilan">'.$nbr_choix['ST2S'][1].'</td>
    <td class="bilan">'.$nbr_choix['STI2D'][1].'</td>
    <td class="bilan">'.$nbr_choix['STD2A'][1].'</td>
    <td class="bilan">'.$nbr_choix['STL'][1].'</td>
  </tr>
  <tr>
    <td class="bilan">Nombre de groupes de 24 <br>élèves sur le vœux 1</td>
    <td class="bilan">'.$nbr_grp['STMG'].'</td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
    <td class="bilan vide"></td>
  </tr>
</table>
<table class="bilan">
  <caption class="autre">Autres Voies</caption>
  <tr>
    <th class="bilan"></th>
    <th class="bilan">Professionnel</th>
    <th class="bilan">Redoublement/CFA</th>
  </tr>
  <tr>
    <td class="bilan">Nombre d\'élèves</td>
    <td class="bilan">'.$nbr_choix['Pro'][0].'</td>
    <td class="bilan">'.$nbr_choix['Autre'][0].'</td>
  </tr>
</table>';
  echo '</body>
</html>';
}


else
{Header('Location:index.html');}
?>
