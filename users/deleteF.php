<?php 
require '../connection.php';

if (isset($_GET['idU'], $_GET['id'])) {
    $id = $_GET['id'];
    $idU = $_GET['idU'];

    // Suppression de l'auteur dans la collection
    $collection = $database->selectCollection('favoris');
    $deleteResult = $collection->deleteOne(
        [
            '_id' => new MongoDB\BSON\ObjectId($id),
            'idUtilisateur' => new MongoDB\BSON\ObjectId($idU)
        ]);

    // Vérification si la suppression a réussi
    if ($deleteResult->getDeletedCount() === 1) {
        // Redirection avec un message de succès
        header("Location: favoris.php?message=success&id=" . $idU);
    } else {
        // Redirection avec un message d'erreur
        header("Location: favoris.php?message=error&id=" . $idU);
    }
    exit();
} else {
    // Redirection si aucun ID n'est fourni
    header("Location: favoris.php?message=error");
    exit();
}
?>
