<?php 
require '../connection.php';

$collection = $database->selectCollection('auteurs');

// Récupération des auteurs
$auteur = $collection->find();

// Vérification si le formulaire a été soumis pour l'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['id'])) {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $nationalite = $_POST['nation'];

    // Insertion dans la collection
    $insert = $collection->insertOne([
        'nom' => $nom,
        'prenom' => $prenom,
        'nationalite' => $nationalite,
    ]);

    // Message de succès
    if ($insert->getInsertedCount() === 1) {
        $successMessage = "Auteur ajouté avec succès.";
        $auteur = $collection->find();
    } else {
        $errorMessage = "Erreur lors de l'ajout de l'auteur.";
    }
}

// Vérification si le formulaire de modification a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $nationalite = $_POST['nation'];

    // Mise à jour dans la collection
    $filter = ['_id' => new MongoDB\BSON\ObjectId($id)];
    $update = [
        '$set' => [
            'nom' => $nom,
            'prenom' => $prenom,
            'nationalite' => $nationalite,
        ]
    ];

    $updateResult = $collection->updateOne($filter, $update);

    // Message de succès
    if ($updateResult->getModifiedCount() === 1) {
        $successMessage = "Auteur modifié avec succès.";
        $auteur = $collection->find();
    } else {
        $errorMessage = "Erreur lors de la modification de l'auteur.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des auteurs</title>
    <style>
        /* Couleurs personnalisées */
        .modal-header {
            background-color: rgba(10, 15, 36, 0.7);
            color: white;
        }
        .modal-footer {
            background-color: #fdf2e8;
        }
        .cont { 
            margin-top: 12rem;
        }
        .secondary-navbar {
            position: fixed;
            top: 60px; 
            width: 100%; 
            background-color: rgba(10, 15, 36, 0.85); 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); 
            z-index: 1030; 
            padding: 10px 0; 
            display: flex;
            justify-content: center; 
        }
        .secondary-navbar .btn {
            margin: 0 10px; 
            color: white; 
            background-color: rgba(10, 15, 36, 0.7); 
            border-color: rgba(10, 15, 36, 0.85); 
            font-weight: bold;
        }
        .secondary-navbar .btn:hover {
            background-color: rgba(10, 15, 36, 0.9); 
            border-color: #FA8603;
        }
    </style>
</head>
<body>
    <?php include '../include/headerA.php'; ?>
    
    <nav class="secondary-navbar">
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus"></i> AJOUTER</a>
    </nav>
    
    <div class="cont">
        <div class="container">
            <h3>GESTION DES AUTEURS</h3>
            
            <!-- Ajouter un auteur -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class='card-header text-center'>
                                    <h3 style="color:black;">Enregistrement Auteur</h3>
                                </div>
                                <div class='form-group'>
                                    <label for='nom'>Nom</label>
                                    <input type='text' name='nom' class='form-control' value="" required>
                                </div>
                                <div class='form-group'>
                                    <label for='prenom'>Prénom</label>
                                    <input type='text' name='prenom' class='form-control' value="" required>
                                </div>
                                <div class='form-group'>
                                    <label for='nation'>Nationalité</label>
                                    <input type='text' name='nation' class='form-control' value="" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Ajouter</button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modifier un auteur -->
            <div class="modal fade" id="modifModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class='card-header text-center'>
                                    <h3 style="color:black;">Modification de l'Auteur</h3>
                                    <h2 class="titre"></h2>
                                </div>
                                <input type='hidden' name='id' class='form-control' id='id' value="" required>
                                <div class='form-group'>
                                    <label for='nom'>Nom</label>
                                    <input type='text' name='nom' class='form-control' id='nom' value="" required>
                                </div>
                                <div class='form-group'>
                                    <label for='prenom'>Prénom</label>
                                    <input type='text' name='prenom' class='form-control' id='prenom' value="" required>
                                </div>
                                <div class='form-group'>
                                    <label for='nation'>Nationalité</label>
                                    <input type='text' name='nation' class='form-control' id='nation' value="" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Modifier</button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                            text: "Auteur supprimé avec succès.",
                            icon: "success"
                        });
                    } else if (message === 'error') {
                        Swal.fire({
                            title: "Erreur!",
                            text: "Erreur lors de la suppression de l'auteur.",
                            icon: "error"
                        });
                    }
                </script>
            <?php endif; ?>
            
            <!-- Liste des auteurs -->
            <section id="list" class="list">
                <table id="myTables" class="table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nom Auteur</th>
                            <th>Prénom Auteur</th>
                            <th>Nationalité</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($auteur as $auteurs): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($auteurs->nom); ?></td>
                                <td><?php echo htmlspecialchars($auteurs->prenom); ?></td>
                                <td><?php echo htmlspecialchars($auteurs->nationalite); ?></td>
                                <td>
                                    <a onclick="modifier();" data-id="<?php echo $auteurs['_id']; ?>" data-nom="<?php echo $auteurs['nom']; ?>" data-prenom="<?php echo $auteurs['prenom']; ?>" data-nation="<?php echo $auteurs['nationalite']; ?>"  data-bs-toggle="modal" data-bs-target="#modifModal" class="btn btn-warning btn-sm modif"><i class="fas fa-pencil-alt"></i></a>
                                    <a onclick="confirme(`<?php echo $auteurs['_id']; ?>`);" class="btn btn-danger btn-sm delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>

    <script>
        function modifier() {
            $(document).ready(function() {
                $(".modif").on('click', function() {
                    $("#nom").val($(this).attr("data-nom"));
                    $("#prenom").val($(this).attr("data-prenom"));
                    $("#nation").val($(this).attr("data-nation"));
                    $("#id").val($(this).attr("data-id"));
                });
            });
        }
        
        function confirme(id) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Vous ne pourrez pas revenir en arrière !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer!',
                cancelButtonText: 'Non, annuler !'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete.php?id=' + id;
                } else if (result.isDismissed) {
                    Swal.fire(
                        'Annulé',
                        'Votre fichier est en sécurité :)',
                        'error'
                    );
                }
            });
        }
    </script>

    <?php include '../include/footerA.php'; ?>
    
    <script type="text/javascript">
        new DataTable('#myTables', {
            responsive: true
        });
    </script>
</body>
</html>
