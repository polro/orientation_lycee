<?php
// Démarrage de la session
session_start();

if (in_array($_SESSION['login'],array('admin','direction'))){

  include 'decrypt_db.php';
  include 'menu.php';
  include 'connect.php';

  $new_classes = array('1A','1B','1C','1D','1E');
  foreach ($new_classes as $classe){
    echo '
<table>
  <tr>
    <th></th>
  </tr>';
  } //fin foreach

  echo '</body>
</html>';
}


elseif (isset($_SESSION['login'])){
  include 'menu.php';
  echo '<h1>Vous n\'avez pas accès à cette page. Vous venez de briser notre CONFIANCE.</h1>';}

else{Header('Location:../index.html');}
?>