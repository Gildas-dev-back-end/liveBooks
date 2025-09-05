<?php
require '../connection.php'; // Assurez-vous que le chemin est correct
session_start();
include '../include/headerC.php'; // Inclure le fichier d'en-tête

$collection = $database->selectCollection('documents');


if (isset($_GET['recherche']) && !empty($_GET['recherche'])) {
    $valeur = $_GET['recherche'];

    // Création du filtre de recherche
    $filtre = [
        '$or' => [
            ['domaine' => ['$regex' => $valeur, '$options' => 'i']], // Recherche insensible à la casse
            ['libelle' => ['$regex' => $valeur, '$options' => 'i']],
            ['type' => ['$regex' => $valeur, '$options' => 'i']],
            ['maison' => ['$regex' => $valeur, '$options' => 'i']],
            ['isbn' => ['$regex' => $valeur, '$options' => 'i']],
            ['description' => ['$regex' => $valeur, '$options' => 'i']]
        ]
    ];

    // Exécuter la requête avec le filtre
    $documents = $collection->find($filtre);
}else{
    // Récupérer les documents
    $documents = $collection->find();
}


// Récupérer l'ID de l'utilisateur connecté
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
}else{
    $id = "";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Document Disponible</title>
    <link rel="stylesheet" href="path/to/font-awesome.css"> <!-- Pour les icônes -->
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Bootstrap pour un style rapide -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .cont {
            margin-top: 8rem;
        }
        .filter-bar {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }
        .filter-bar select, .filter-bar input {
            padding: 0.5rem;
        }
        .books-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .book-item {
            background-color: white;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        .book-item img {
            max-width: 100px;
            height: auto;
            margin-right: 1rem;
        }
        .book-info {
            flex-grow: 1;
        }
        .book-info h5 {
            margin: 0;
            font-size: 1.25rem;
        }
        .actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .actions a {
            text-decoration: none;
            text-align: center;
            padding: 0.5rem;
            font-size: 0.875rem;
            color: white;
            border-radius: 4px;
        }

        /* Style pour le bouton "Voir" */
        .btn-primary {
            background-color: #66b3ff; /* Bleu pastel */
            border-color: #66b3ff; /* Limite bleue subtile */
            color: black; /* Texte blanc pour un contraste clair */
            transition: background-color 0.3s, transform 0.3s; /* Douce transition */
        }

        .btn-primary:hover {
            background-color: #3399ff; /* Bleu un peu plus vif au survol */
            transform: scale(1.05); /* Effet d'agrandissement léger pour un retour visuel interactif */
        }

        /* Style pour le bouton "Ajouter aux favoris" */
        .btn-warning {
            background-color: #ffb3cc; /* Rose pastel doux */
            border-color: #ffb3cc; /* Limite rose doux */
            color: white;
            transition: background-color 0.3s, transform 0.3s; /* Douce transition */
        }

        .btn-warning:hover {
            background-color: #ff80a6; /* Rose plus vif au survol */
            transform: scale(1.05); /* Effet d'agrandissement léger au survol */
        }

        /* Arrondir les coins des boutons */
        .btn-primary, .btn-warning {
            border-radius: 30px; /* Coins arrondis pour un aspect plus doux */
            padding: 0.6rem 1.2rem; /* Espacement interne pour plus de confort visuel */
        }

        #searchInput {
            width: 30%;
        }
    </style>
</head>
<body>
    <div class="cont">
        <div class="container">
            <!-- Filtre et barre de recherche -->
            <h3>LIVRES DISPONIBLE</h3>
            <div class="filter-bar">
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher des livres..." onkeyup="filterBooks()">
            </div>

            <!-- Liste des livres -->
            <div class="books-list" id="booksList">
                <?php foreach ($documents as $document): ?>
                    <div class="book-item" data-category="<?= htmlspecialchars($document['domaine']) ?>">
                        <img src="<?= htmlspecialchars($document['image']) ?>" alt="<?= htmlspecialchars($document['libelle']) ?>">
                        <div class="book-info">
                            <h5><strong><?= htmlspecialchars($document['libelle']) ?></strong></h5><br>
                            <?php $collectionNomAuteur = $database->selectCollection('auteurs');

                                // Récupération des auteurs
                                $NomAuteur = $collectionNomAuteur->findOne(["_id" =>  new MongoDB\BSON\ObjectId($document->auteur)]); 
                                ?>
                            <p><strong>Auteur :</strong> <?= htmlspecialchars($NomAuteur->nom) ?></p>
                            <p><strong>Éditeur :</strong> <?= htmlspecialchars($document['maison']) ?></p>
                            <p><strong>Description :</strong> <?= htmlspecialchars($document['description']) ?></p>
                        </div>
                        <div class="actions">
                        <?php if ($id != ""): ?>
                                <a href="<?= htmlspecialchars($document['fichier']) ?>" target="_blanc" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye"></i> Voir
                                </a>
                                <a href="favoris.php?idD=<?= htmlspecialchars($document['_id']); ?>&lib=<?= htmlspecialchars($document['libelle']); ?>&id=<?= $id ?>&fic=<?= htmlspecialchars($document['fichier']); ?>&image=<?= htmlspecialchars($document['image']); ?>&descr=<?= htmlspecialchars($document['description']); ?>&maison=<?= htmlspecialchars($document['maison']); ?>&aut=<?= htmlspecialchars($document['auteur']); ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-heart"></i> Ajouter aux favoris
                                </a>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye"></i> Voir
                                </a>
                                <a href="login.php" class="btn btn-warning btn-sm">
                                    <i class="bi bi-heart"></i> Ajouter aux favoris
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php include '../include/footerC.php'; // Inclure le fichier de pied de page ?>
    
    <script>
    function filterBooks() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const booksList = document.getElementById('booksList');
        const bookItems = booksList.getElementsByClassName('book-item');

        for (let i = 0; i < bookItems.length; i++) {
            const bookTitle = bookItems[i].getElementsByClassName('book-info')[0].getElementsByTagName('strong')[0].textContent.toLowerCase();
            if (bookTitle.includes(filter)) {
                bookItems[i].style.display = ""; // Affiche l'élément
            } else {
                bookItems[i].style.display = "none"; // Cache l'élément
            }
        }
    }
    </script>
</body>
</html>
