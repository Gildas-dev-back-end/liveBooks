<?php
require '../connection.php'; // Assurez-vous que le chemin est correct

$collection = $database->selectCollection('documents');
//recuperer les documents
$documents = $collection->find();

$collectionAuteur = $database->selectCollection('auteurs');

// Récupération des auteurs
$auteur = $collectionAuteur->find();
$auteurs = $collectionAuteur->find();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['idD'])) {
    // Récupérer les données du formulaire
    $isbn = $_POST['isbn'];
    $libelle = $_POST['libelle'];
    $domaine = $_POST['domaine'];
    $auteur = $_POST['auteur'];
    $type = $_POST['type'];
    $maison = $_POST['maison'];
    $description = $_POST['description'];

    // Gestion des fichiers
    $documentDir = '../fichiers/'; // Dossier pour les fichiers
    $imageDir = '../images/'; // Dossier pour les images

    $documentFile = $_FILES['document'];
    $imageFile = $_FILES['affiche'];

    // Vérification et téléchargement du fichier
    $documentPath = $documentDir . basename($documentFile['name']);
    $imagePath = $imageDir . basename($imageFile['name']);

    $uploadOk = 1;

    // Vérification du type de fichier pour le document
    $documentFileType = strtolower(pathinfo($documentPath, PATHINFO_EXTENSION));
    if ($documentFileType != 'pdf' && $documentFileType != 'doc' && $documentFileType != 'docx') {
        $errorMessage = "Désolé, seuls les fichiers PDF, DOC et DOCX sont autorisés.";
        $uploadOk = 0;
    }

    // Vérification du type de fichier pour l'image
    $imageFileType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
    if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
        $errorMessage = "Désolé, seuls les fichiers JPG, JPEG et PNG sont autorisés.";
        $uploadOk = 0;
    }

    // Vérification si $uploadOk est défini à 0 par une erreur
    if ($uploadOk == 0) {
        $errorMessage = "Désolé, votre fichier n'a pas été téléchargé.";
    } else {
        // Téléchargement du document
        if (move_uploaded_file($documentFile['tmp_name'], $documentPath) && move_uploaded_file($imageFile['tmp_name'], $imagePath)) {
            // Insertion dans la base de données
            $collection = $database->selectCollection('documents');
            $insert = $collection->insertOne([
                'isbn' => $isbn,
                'libelle' => $libelle,
                'domaine' => $domaine,
                'auteur' => new MongoDB\BSON\ObjectId($auteur),
                'type' => $type,
                'maison' => $maison,
                'description' => $description,
                'fichier' => $documentPath,
                'image' => $imagePath
            ]);
        }
    }

    if ($insert->getInsertedCount() === 1) {
        $successMessage = "Document ajouté avec succès.";
        $documents = $collection->find();
        $auteur = $collectionAuteur->find();
    } else {
        $errorMessage = "Désolé, une erreur est survenue lors du téléchargement de votre fichier.";
        $documents = $collection->find();
        $auteur = $collectionAuteur->find();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'  && isset($_POST['idD'])) {
    // Récupérer les données du formulaire
    $isbn = $_POST['isbn'];
    $libelle = $_POST['libelle'];
    $domaine = $_POST['domaine'];
    $auteur = $_POST['auteur'];
    $type = $_POST['type'];
    $maison = $_POST['maison'];
    $description = $_POST['description'];
    $documentId = $_POST['idD']; // ID du document à modifier

    // Gestion des fichiers
    $documentDir = '../fichiers/'; // Dossier pour les fichiers
    $imageDir = '../images/'; // Dossier pour les images

    $documentFile = $_FILES['document'];
    $imageFile = $_FILES['affiche'];

    // Récupération de l'ancien document
    $oldDocument = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($documentId)]);
    
    // Initialisation des chemins de fichiers
    $documentPath = $oldDocument->fichier;
    $imagePath = $oldDocument->image;

    // Vérification du fichier document
    if ($documentFile['name']) {
        $newDocumentPath = $documentDir . basename($documentFile['name']);
        $documentFileType = strtolower(pathinfo($newDocumentPath, PATHINFO_EXTENSION));
        if ($documentFileType != 'pdf' && $documentFileType != 'doc' && $documentFileType != 'docx') {
            $errorMessage = "Désolé, seuls les fichiers PDF, DOC et DOCX sont autorisés.";
        } else {
            if (move_uploaded_file($documentFile['tmp_name'], $newDocumentPath)) {
                $documentPath = $newDocumentPath; // Mettre à jour le chemin si le fichier est téléchargé
            }
        }
    }

    // Vérification de l'image
    if ($imageFile['name']) {
        $newImagePath = $imageDir . basename($imageFile['name']);
        $imageFileType = strtolower(pathinfo($newImagePath, PATHINFO_EXTENSION));
        if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
            $errorMessage = "Désolé, seuls les fichiers JPG, JPEG et PNG sont autorisés.";
        } else {
            if (move_uploaded_file($imageFile['tmp_name'], $newImagePath)) {
                $imagePath = $newImagePath; // Mettre à jour le chemin si l'image est téléchargée
            }
        }
    }

    // Mise à jour dans la base de données
    if (empty($errorMessage)) {
        $updateResult = $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($documentId)],
            ['$set' => [
                'isbn' => $isbn,
                'libelle' => $libelle,
                'domaine' => $domaine,
                'auteur' => new MongoDB\BSON\ObjectId($auteur),
                'type' => $type,
                'maison' => $maison,
                'description' => $description,
                'fichier' => $documentPath,
                'image' => $imagePath
            ]]
        );

        if ($updateResult->getModifiedCount() === 1) {
            $successMessage = "Document modifié avec succès.";
            $documents = $collection->find();
            $auteur = $collectionAuteur->find();
        } else {
            $errorMessage = "Aucune modification n'a été effectuée.";
            $documents = $collection->find();
            $auteur = $collectionAuteur->find();
        }
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestion des documents</title>
    <style>
        /* Couleurs personnalisées */
        .modal-header {
            background-color: rgba(10, 15, 36, 0.7);
            color: white;
        }
        .modal-footer {
            background-color: #fdf2e8;
        }
        table {
            background-color: #fdf2e8;  
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
        .cont {
            margin-top: 12rem;
        }
    </style>
</head>
<body>
<?php include '../include/headerA.php'; ?>
    <nav class="secondary-navbar">
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDocsModal"><i class="fas fa-plus"></i> AJOUTER</a>
    </nav>

    <div class="cont">
        <div class="container">
            <h3>GESTION DES DOCUMENTS</h3>
            <!-- Ajouter un document -->
            <div class="modal fade" id="addDocsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter un Document</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class="card-header text-center">
                                    <h3 style="color: black;">Enregistrez Un Document</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="isbn">ISBN</label>
                                        <input type="text" name="isbn" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="libelle">Libellé</label>
                                        <input type="text" name="libelle" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="domaine">Domaine</label>
                                        <input type="text" name="domaine" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="auteur">Auteur</label>
                                        <select class="form-control" name="auteur" required>
                                            <option value="#" disabled selected>Sélectionner l'auteur</option>
                                            <?php foreach ($auteur as $a): ?>
                                                <option value="<?= $a->_id; ?>"><?= htmlspecialchars($a->nom); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="type">Type Document</label>
                                        <select class="form-control" name="type" required>
                                            <option value="#" disabled selected>Sélectionner le Type</option>
                                            <option value="Roman">Roman</option>
                                            <option value="Biographie">Biographie</option>
                                            <option value="Manuel scolaire">Manuel scolaire</option>
                                            <option value="Livre de cuisine">Livre de cuisine</option>
                                            <option value="Poésie">Poésie</option>
                                            <option value="Magazine">Magazine</option>
                                            <option value="Article scientifique">Article scientifique</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="maison">Maison d'Édition</label>
                                        <select class="form-control" name="maison" required>
                                            <option value="#" disabled selected>Sélectionner la maison d'édition</option>
                                            <option value="Gallimard">Gallimard</option>
                                            <option value="Hachette">Hachette</option>
                                            <option value="Fayard">Fayard</option>
                                            <option value="Albin Michel">Albin Michel</option>
                                            <option value="Le Seuil">Le Seuil</option>
                                            <option value="Actes Sud">Actes Sud</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="document">Fichier</label>
                                    <input type="file" name="document" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="affiche">Image</label>
                                    <input type="file" name="affiche" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" required></textarea>
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

            <!-- Modifier un document -->
            <div class="modal fade" id="modifDocsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title fs-5" id="exampleModalLabel">Modification </h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class="card-header text-center">
                                    <h3 style="color: black;">Modification du document</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="isbn">ISBN</label>
                                        <input type="text" name="isbn" class="form-control" id="isbn" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="libelle">Libellé</label>
                                        <input type="text" name="libelle" class="form-control" id="libelle" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="domaine">Domaine</label>
                                        <input type="text" name="domaine" class="form-control" id="domaine" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="auteur">Auteur</label>
                                        <select class="form-control" name="auteur" required>
                                            <option value="" id="oldAuteur"></option>
                                            <?php foreach ($auteurs as $a): ?>
                                                <option value="<?= $a->_id; ?>"><?= htmlspecialchars($a->nom); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="type">Type Document</label>
                                        <select class="form-control" name="type" id="type" required>
                                            <option value="" id="oldType"></option>
                                            <option value="Roman">Roman</option>
                                            <option value="Biographie">Biographie</option>
                                            <option value="Manuel scolaire">Manuel scolaire</option>
                                            <option value="Livre de cuisine">Livre de cuisine</option>
                                            <option value="Poésie">Poésie</option>
                                            <option value="Magazine">Magazine</option>
                                            <option value="Article scientifique">Article scientifique</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="maison">Maison d'Édition</label>
                                        <select class="form-control" name="maison" id="maison" required>
                                            <option value="" id="oldMaison"></option>
                                            <option value="Gallimard">Gallimard</option>
                                            <option value="Hachette">Hachette</option>
                                            <option value="Fayard">Fayard</option>
                                            <option value="Albin Michel">Albin Michel</option>
                                            <option value="Le Seuil">Le Seuil</option>
                                            <option value="Actes Sud">Actes Sud</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="document">Fichier</label>
                                    <input type="file" name="document" id="document" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="affiche">Image</label>
                                    <input type="file" name="affiche" id="affiche" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" id="description" required></textarea>
                                </div>
                                <input type="hidden" name="documents" id="documents" value="">
                                <input type="hidden" name="affiches" id="affiches" value="">
                                <input type="hidden" name="idD" id="idD" value="">
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

            <!-- Liste des documents -->
            <section id="list" class="list">
                <table id="myTables" class="table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Images</th>
                            <th>ISBN</th>
                            <th>Auteurs</th>
                            <th>Libellés</th>
                            <th>Maison d'Édition</th>
                            <th>Domaines</th>
                            <th>Types</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document): ?>
                            <tr>
                                <td><img src="<?= htmlspecialchars($document->image); ?>" alt="" style="width:50px;height:auto;"/></td>
                                <td><?= htmlspecialchars($document->isbn); ?></td>
                                <?php $collectionNomAuteur = $database->selectCollection('auteurs');

                                // Récupération des auteurs
                                $NomAuteur = $collectionNomAuteur->findOne(["_id" =>  new MongoDB\BSON\ObjectId($document->auteur)]); 
                                ?>
                                <td><?= htmlspecialchars($NomAuteur->nom);  ?></td>
                                <td><?= htmlspecialchars($document->libelle); ?></td>
                                <td><?= htmlspecialchars($document->maison); ?></td>
                                <td><?= htmlspecialchars($document->domaine); ?></td>
                                <td><?= htmlspecialchars($document->type); ?></td>
                                <td><?= htmlspecialchars($document->description); ?></td>
                                <td>
                                    <a href="<?= htmlspecialchars($document->fichier); ?>" target="_blank" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a>
                                    <a data-id="<?= $document->_id; ?>" data-isbn="<?= $document->isbn; ?>" data-idA="<?= $document->auteur; ?>" data-nomA="<?= $NomAuteur->nom ?>" data-libelle="<?= $document->libelle; ?>"
                                       data-affiche="<?= $document->image; ?>" data-type="<?= $document['type']; ?>" data-domaine="<?= $document['domaine']; ?>" data-maison="<?= $document['maison']; ?>"
                                       data-fichier="<?= $document->fichier; ?>" data-description="<?= $document->description; ?>" onclick="modifier();"
                                       data-bs-toggle="modal" data-bs-target="#modifDocsModal" class="btn btn-warning btn-sm modif"><i class="bi bi-pencil"></i></a>
                                    <a onclick="confirme(`<?= $document['_id']; ?>`)" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
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
                    $("#isbn").val($(this).attr("data-isbn"));
                    $("#libelle").val($(this).attr("data-libelle"));
                    $("#domaine").val($(this).attr("data-domaine"));
                    $("#oldAuteur").val($(this).attr("data-idA"));
                    $("#oldAuteur").append($(this).attr("data-nomA"));
                    $("#oldType").val($(this).attr("data-type"));
                    $("#oldType").append($(this).attr("data-type"));
                    $("#oldMaison").val($(this).attr("data-maison"));
                    $("#oldMaison").append($(this).attr("data-maison"));
                    $("#documents").val($(this).attr("data-fichier"));
                    $("#affiches").val($(this).attr("data-affiche"));
                    $("#description").val($(this).attr("data-description"));
                    $("#idD").val($(this).attr("data-id"));
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
                    window.location.href = 'deleteD.php?id=' + id;
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
