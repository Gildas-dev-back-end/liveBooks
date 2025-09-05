<?php 
session_start(); // Démarre la session
require '../connection.php';

$collection = $database->selectCollection('comptes');

// Vérification si le formulaire a été soumis pour la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $motdepasse = $_POST['pwd'];

    // Recherche de l'utilisateur dans la collection
    $user = $collection->findOne(['email' => $email]);

    // Vérification si l'utilisateur existe
    if ($user) {
        // Vérification du mot de passe
        if (password_verify($motdepasse, $user['motdepasse'])) {
            // Démarrage de la session et stockage des informations de l'utilisateur
            $_SESSION['user_id'] = (string)$user['_id']; // Stocke l'ID de l'utilisateur
            $_SESSION['email'] = $user['email']; // Optionnel : stocke l'email

            // Redirection vers index.php
            header("Location: index.php?id=". $user['_id'] );
            exit();
        } else {
            $errorMessage = "Mot de passe incorrect.";
        }
    } else {
        $errorMessage = "Aucun compte trouvé avec cette adresse e-mail.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
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
            border-color: #FA8603;
        }
    </style>
</head>
<body>
    <?php include '../include/headerC.php'; ?>

        <?php if (!empty($errorMessage)): ?>
                <script>
                    function alertError() {
                        Swal.fire({
                            title: "Erreur de compte!",
                            text: "<?php echo $errorMessage; ?>",
                            icon: "error"
                        });
                    }
                    alertError();
                </script>
            <?php endif; ?>
    <div class="login-wrapper">
        <div class="login-container">
            <?php if (!empty($errorMessage)): ?>
                <script>
                    function alertError() {
                        Swal.fire({
                            title: "Erreur de compte!",
                            text: $errorMessage,
                            icon: "error"
                        });
                    }
                    alertError();
                </script>
            <?php endif; ?>
            <a class="navbar-brand" href="Users?action=home" style="color:rgba(10, 15, 36, 0.9);">
                LiveBook
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-book-half" viewBox="0 0 16 16">
                    <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783" />
                </svg>
            </a>
            <h2 style="color:rgba(10, 15, 36, 0.9);">Se connecter</h2>
            <form method="post">
                <input type="email" class="form-control" name="email" placeholder="Adresse e-mail" required>
                <input type="password" class="form-control" name="pwd" placeholder="Mot de passe" required>
                <button type="submit" class="btn btn-login w-100" style="color: white; background-color: rgba(10, 15, 36, 0.9);">Connexion</button>
            </form>
            <p class="footer">Pas encore inscrit ? <a href="compte.php" class="link">Créer un compte</a></p>
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
                    if (message === 'success') {
                        Swal.fire({
                            title: "Succès!",
                            text: "Compte creer avec success !",
                            icon: "success"
                        });
                    } else if (message === 'error') {
                        Swal.fire({
                            title: "Erreur!",
                            text: " Erreur lors de la creation du Compte",
                            icon: "error"
                        });
                    }
                </script>
            <?php endif; ?>

    <!-- Scripts Bootstrap -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
