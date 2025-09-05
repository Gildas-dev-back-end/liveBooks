<?php
require 'vendor/autoload.php'; // Correction de la syntaxe require

$uri = "mongodb://localhost:27017";
$client = new MongoDB\Client($uri); // Correction de la classe Client (C majuscule)

$database = $client->selectDatabase('liveBooks');

//create collection
//$collection = $database->createCollection('nom_de_lacollection');

?>
