<?php
session_start(); // Assurez-vous que la session est démarrée
// Inclure les fichiers nécessaires pour la connexion à la base de données et les messages de session
require '../connection.php'; // Chemin vers votre fichier de connexion à la base de données
require '../include/headerC.php'; // Inclure l'en-tête

$favoris = [];

// Vérifier si l'ID utilisateur est présent dans l'URL et non vide
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    
    // Vérifier si l'ID est un ObjectId valide
    if (preg_match('/^[a-f0-9]{24}$/', $id)) {
        $collection = $database->selectCollection('favoris');
        
        // Récupérer les favoris de l'utilisateur
        $favoris = $collection->find(["idUtilisateur" => new MongoDB\BSON\ObjectId($id)]);
    }
}

// Vérifier si tous les paramètres pour l'ajout sont présents dans l'URL
if (isset($_GET['idD'], $_GET['lib'], $_GET['fic'], $_GET['id'], $_GET['image'], $_GET['descr'], $_GET['maison'], $_GET['aut'])) {
    // Récupérer les paramètres et sécuriser les données
    $idD = htmlspecialchars($_GET['idD']);
    $lib = htmlspecialchars($_GET['lib']);
    $fic = htmlspecialchars($_GET['fic']);
    $image = htmlspecialchars($_GET['image']);
    $descr = htmlspecialchars($_GET['descr']);
    $maison = htmlspecialchars($_GET['maison']);
    $aut = htmlspecialchars($_GET['aut']);
    $idU = htmlspecialchars($_GET['id']);
    
    // Vérifier si l'utilisateur a déjà ce document en favoris
    $favoriExiste = $collection->findOne([
        'idUtilisateur' => new MongoDB\BSON\ObjectId($idU),
        'idDocument' => new MongoDB\BSON\ObjectId($idD)
    ]);

    if ($favoriExiste) {
        $errorMessage = "Ce document est déjà dans vos favoris.";
    } else {
        // Créer un tableau avec les données à insérer
        $newFavori =  [
            'idDocument' => new MongoDB\BSON\ObjectId($idD),
            'idUtilisateur' => new MongoDB\BSON\ObjectId($idU),
            'libelle' => $lib,
            'fichier' => $fic,
            'image' => $image,
            'description' => $descr,
            'maison' => $maison,
            'auteur' => $aut
        ];
        
        // Insérer le document dans la collection
        try {
            $collection->insertOne($newFavori);
            $successMessage = "Favori ajouté avec succès.";
        } catch (Exception $e) {
            $errorMessage = "Erreur lors de l'ajout du favori : " . $e->getMessage();
        }
    }
}

// Vous pouvez continuer à utiliser $favoris dans votre logique de page
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Documents Favoris</title>
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
        /* Styles pour les boutons */
        .btn-primary {
            background-color: #66b3ff;
            border-color: #66b3ff;
            color: black;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-primary:hover {
            background-color: #3399ff;
            transform: scale(1.05);
        }
        .btn-warning {
            background-color: #ffb3cc;
            border-color: #ffb3cc;
            color: white;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-warning:hover {
            background-color: #ff80a6;
            transform: scale(1.05);
        }
        .btn-primary, .btn-warning {
            border-radius: 30px;
            padding: 0.6rem 1.2rem;
        }
        .secondary-navbar {
            position: fixed;
            top: 60px; 
            width: 100%; 
            background-color: rgba(10, 15, 36, 0.85); 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); 
            padding: 10px 0; 
            display: flex;
            justify-content: center; 
            z-index: 1040; 
        }
        .secondary-navbar .btn {
            margin: 0 10px; 
            color: white; 
            background-color: rgba(10, 15, 36, 0.85); 
            border-color: rgba(10, 15, 36, 0.85); 
            font-weight: bold;
        }
        .secondary-navbar .btn:hover {
            background-color: rgba(10, 15, 36, 0.9); 
            border-color: #FA8603;
        }
        
        #searchInput {
            width: 30%;
        }
    </style>
</head>
<body>

<div class="cont">
    <div class="container">
        <h3>FAVORIES</h3>
        <div class="filter-bar">
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un livres favoris..." onkeyup="filterBooks()">
            </div>
        
        <?php if (!empty($successMessage)): ?>
                <script>
                    function alertSuccess() {
                        Swal.fire({
                            title: "Succès!",
                            text: "<?php echo $successMessage; ?>",
                            icon: "success"
                        });
                    }
                    alertSuccess();
                </script>
            <?php endif; ?>
            
            <?php if (!empty($errorMessage)): ?>
                <script>
                    function alertError() {
                        Swal.fire({
                            title: "Erreur!",
                            text: "<?php echo $errorMessage; ?>",
                            icon: "error"
                        });
                    }
                    alertError();
                </script>
            <?php endif; ?>

            <?php if (isset($_GET['message'])): ?>
                <script>
                    let message = "<?php echo $_GET['message']; ?>";
                    if (message === 'success') {
                        Swal.fire({
                            title: "Succès!",
                            text: "Favoris supprimé avec succès.",
                            icon: "success"
                        });
                    } else if (message === 'error') {
                        Swal.fire({
                            title: "Erreur!",
                            text: "Erreur lors de la suppression du favoris.",
                            icon: "error"
                        });
                    }
                </script>
            <?php endif; ?>
    
        <!-- Liste des livres -->
        <div class="books-list" id="booksList">
            <?php foreach ($favoris as $favori): ?>
                <div class="book-item" data-category="">
                    <img src="<?php echo htmlspecialchars($favori['image']); ?>" alt="">
                    <div class="book-info">
                        <h5><strong><?php echo htmlspecialchars($favori['libelle']); ?></strong></h5><br>
                        <?php $collectionNomAuteur = $database->selectCollection('auteurs');

                                // Récupération des auteurs
                        $NomAuteur = $collectionNomAuteur->findOne(["_id" =>  new MongoDB\BSON\ObjectId($favori->auteur)]); 
                        ?>
                        <p><strong>Auteur :</strong> <?php echo htmlspecialchars($NomAuteur->nom); ?></p>
                        <p><strong>Éditeur :</strong> <?php echo htmlspecialchars($favori['maison']); ?></p>
                        <p><strong>Description :</strong> <?php echo htmlspecialchars($favori['description']); ?></p>
                    </div>
                    <div class="actions">
                        <a href="<?php echo htmlspecialchars($favori['fichier']); ?>" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> Voir
                        </a>
                        <a onclick="confirme(`<?php echo htmlspecialchars($favori['_id']); ?>`, `<?php echo htmlspecialchars($id); ?>`)" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Supprimer
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include '../include/footerC.php'; // Inclure le pied de page ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirme(idF, idU) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous ne pourrez pas revenir en arrière !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, supprimer!',
        cancelButtonText: 'Non, annuler !'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'deleteF.php?id=' + idF + '&idU=' + idU;
        } else if (result.isDismissed) {
            Swal.fire(
                'Annulé',
                'Votre fichier est en sécurité :)',
                'error'
            );
        }
    });
}

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
