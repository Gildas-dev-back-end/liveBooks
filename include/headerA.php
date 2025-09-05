<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Insert title here</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
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
            background-color: #fdf2e8;
        }

        .navbar {
            background-color: rgba(10, 15, 36, 0.85);
            backdrop-filter: blur(10px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
            z-index: 1050;
            position: fixed;
            top: 0;
            width: 100%;
        }

        .navbar-brand {
            color: #FA8603;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-brand svg {
            margin-left: 0.5rem;
            vertical-align: middle;
        }

        .navbar-toggler {
            border: none;
            color: #FA8603;
        }

        .navbar-toggler:hover {
            color: #BB2233;
        }

        .nav-link {
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.3s ease;
            font-weight: bold;
        }

        .nav-link:hover {
            color: #FA8603;
        }

        .nav-item {
            margin: 0 0.5rem;
        }

        .navbar-nav .active {
            color: #FA8603 !important;
        }

        footer h5 {
            color: #BB2233;
            font-weight: bold;
        }

        footer ul {
            padding: 0;
        }

        footer ul li {
            margin-bottom: 0.5rem;
        }

        footer ul li i {
            color: #FA8603;
        }

        footer ul li a:hover {
            color: #BB2233;
            text-decoration: underline;
        }

        .bg-primary {
            background-color: #BB2233 !important;
        }

        /* Pour les écrans de petite taille */
        @media (max-width: 768px) {
            .secondary-navbar {
                top: 0;
            }
            .navbar-collapse {
                z-index: 1060;
                background-color: rgba(10, 15, 36, 0.85);
                position: absolute;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand text-light" href="Admin.php?action=home">
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
                        <a class="nav-link active" href="index.php" data-action="home" style="color: WHITE;">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="auteur.php" data-action="auteur" style="color: WHITE;">Gestion des auteurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="document.php" data-action="document" style="color: WHITE;">Gestion des documents</a>
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

    <!-- Scripts JavaScript Bootstrap -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
