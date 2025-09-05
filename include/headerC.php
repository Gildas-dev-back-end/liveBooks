<?php
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
    <title>Insert title here</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e2e0dee1;
        }

        .navbar {
            background-color: rgba(10, 15, 36, 0.85);
            backdrop-filter: blur(10px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        .navbar-brand {
            color: #FA8603;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-toggler {
            border: none;
            color: #FA8603;
        }

        .navbar-toggler:hover {
            color: #BB2233;
        }

        .nav-link {
            font-weight: bold;
            color: #FFFFFF;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #FA8603;
        }

        .navbar-nav .active {
            color: #FA8603 !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand text-light" href="Users?action=home">
                LiveBook
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-book-half" viewBox="0 0 16 16">
                    <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783" />
                </svg>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                    <?php if (isset($id) && !empty($id)): ?>
                        <a class="nav-link" href="index.php?id=<?=$id?>" data-action="home" style="color: WHITE;">Accueil</a>
                        <?php else: ?>
                            <a class="nav-link" href="index.php" data-action="home" style="color: WHITE;">Accueil</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item">
                    <?php if (isset($id) && !empty($id)): ?>
                        <a class="nav-link" href="livres.php?id=<?=$id?>" data-action="categories" style="color: WHITE;">Livres</a>
                        <?php else: ?>
                            <a class="nav-link" href="livres.php" data-action="categories" style="color: WHITE;">Livres</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item">
                    <?php if (isset($id) && !empty($id)): ?>
                        <a class="nav-link" href="favoris.php?id=<?=$id?>" style="color: WHITE;">Favoris</a>
                        <?php else: ?>
                            <a class="nav-link" href="login.php" style="color: WHITE;">Favoris</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item">
                        <?php if ($id != ""): ?>
                            <a class="nav-link" href="login.php" style="color: WHITE;">Se déconnecter</a>
                        <?php else: ?>
                            <a class="nav-link" href="login.php" data-action="login" style="color: WHITE;">Se connecter</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const currentAction = urlParams.get("action") || "home"; // Valeur par défaut si "action" est vide
        const navLinks = document.querySelectorAll(".nav-link");

        navLinks.forEach(link => {
            const action = link.dataset.action; // Utilisation d'un attribut personnalisé "data-action"
            if (action === currentAction) {
                link.classList.add("active");
            } else {
                link.classList.remove("active");
            }
        });
    });
    </script>

    <!-- Scripts -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
