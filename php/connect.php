<?php
$lycee = 'Nom du lycée';
$nom_domaine = 'https://www.nom-de-domaine.fr/';

$servername = 'localhost';
$username = 'root';
$password = 'N4De4RECUZqCHS4';
$database = 'voeux';
// Create connection
$conn = new mysqli($servername, $username, $password, $database);
$conn->set_charset('utf8');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>