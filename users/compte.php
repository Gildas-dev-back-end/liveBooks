<?php 
require '../connection.php';

$collection = $database->selectCollection('comptes');

// Vérification si le formulaire a été soumis pour l'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $motdepasse = $_POST['pwd']; // Remplacez 'password' par 'pwd' pour correspondre au nom du champ

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Adresse e-mail invalide.";
        header("Location: compte.php?message=error&error=" . urlencode($errorMessage));
        exit();
    }

    // Vérification si l'email existe déjà
    $existingAccount = $collection->findOne(['email' => $email]);
    if ($existingAccount) {
        $errorMessage = "Cet e-mail est déjà utilisé.";
        header("Location: compte.php?message=error&error=" . urlencode($errorMessage));
        exit();
    }

    // Insertion dans la collection
    $insert = $collection->insertOne([
        'email' => $email,
        'nom' => $nom,
        'motdepasse' => password_hash($motdepasse, PASSWORD_DEFAULT), // Hash le mot de passe
    ]);

    // Message de succès
    if ($insert->getInsertedCount() === 1) {
        $successMessage = "Compte ajouté avec succès.";
        header("Location: login.php?message=success");
    } else {
        $errorMessage = "Erreur lors de l'ajout du compte.";
        header("Location: login.php?message=error&error=" . urlencode($errorMessage));
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; 
            padding: 1rem;
        }

        .login-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container img {
            width: 100px;
            margin-bottom: 1rem;
        }

        .login-container h2 {
            margin-bottom: 1rem;
            color: #BB2233;
        }

        .form-control {
            border-radius: 20px;
            padding: 10px 15px;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .btn-login {
            border-radius: 20px;
            background-color: rgba(10, 15, 36, 0.9);
            color: #fff;
            padding: 10px 15px;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }

        .btn-login:hover {
            background-color: rgba(10, 15, 36, 0.9); /* Couleur au survol */
            border-color:  #FA8603;
        }
    </style>
</head>
<body>
    <?php include '../include/headerC.php'; ?>
    <div class="login-wrapper">
        <div class="login-container">
            <?php if (!empty($errorMessage)): ?>
                <p id="messageRow" style="color:red;"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
            <?php if (!empty($successMessage)): ?>
                <p id="messageRow" style="color:green;"><?php echo $successMessage; ?></p>
            <?php endif; ?>
            <a class="navbar-brand" href="Users?action=home" style="color:rgba(10, 15, 36, 0.9);">
                LiveBook
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-book-half" viewBox="0 0 16 16">
                    <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783" />
                </svg>
            </a>
            <h2 style="color:rgba(10, 15, 36, 0.9);">Créer un compte</h2>
            <form method="post">
                <input type="email" class="form-control" name="email" placeholder="Adresse e-mail" required>
                <input type="text" class="form-control" name="nom" placeholder="Nom utilisateur" required>
                <input type="password" class="form-control" name="pwd" placeholder="Mot de passe" required>
                <button type="submit" class="btn btn-login w-100" style="color: white; background-color: rgba(10, 15, 36, 0.9);">Créer</button>
            </form>
        </div>
    </div>
    <script>
        // Vérifiez si le message de succès ou d'erreur est présent
        <?php if (!empty($successMessage) || !empty($errorMessage)): ?>
            document.getElementById('messageRow').style.display = 'table-row'; // Affiche la ligne
            setTimeout(function() {
                document.getElementById('messageRow').style.display = 'none'; // Cache la ligne après 3 secondes
            }, 3000);
        <?php endif; ?>
    </script>
     <?php if (isset($_GET['message'])): ?>
                <script>
                    let message = "<?php echo $_GET['message']; ?>";
                    if (message === 'error') {
                        Swal.fire({
                            title: "Erreur!",
                            text: message,
                            icon: "error"
                        });
                    }
                </script>
            <?php endif; ?>

    <!-- Scripts Bootstrap -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
