<?php 
require '../connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Suppression de l'auteur dans la collection
    $collection = $database->selectCollection('auteurs');
    $collections = $database->selectCollection('documents');
    $deleteResult = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    $deleteResults = $collections->deleteMany(['auteur' => new MongoDB\BSON\ObjectId($id)]);

    // Vérification si la suppression a réussi
    if ($deleteResult->getDeletedCount() === 1) {
        // Redirection avec un message de succès
        header("Location: auteur.php?message=success");
    } else {
        // Redirection avec un message d'erreur
        header("Location: auteur.php?message=error");
    }
    exit();
} else {
    // Redirection si aucun ID n'est fourni
    header("Location: auteur.php?message=error");
    exit();
}
?>
