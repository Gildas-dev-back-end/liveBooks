<?php
session_start(); // Démarrez la session si ce n'est pas déjà fait

// Connexion à la base de données MongoDB
require '../connection.php'; // Assurez-vous que ce fichier contient la connexion à MongoDB

// Récupérer les documents
$documents = $database->selectCollection('documents')->find()->toArray();

// Compter le nombre total d'utilisateurs dans la base
$totalUtilisateurs = $database->selectCollection('comptes')->countDocuments();

// Compter le nombre total de documents dans la base
$totalDocuments = $database->selectCollection('documents')->countDocuments();

// Compter le nombre de documents uniques dans la collection 'favoris'
$uniqueFavoris = $database->selectCollection('favoris')->distinct('idDocument');
$totalFavoris = count($uniqueFavoris);


// Gestion des messages
$successMessage = isset($_SESSION['successMessage']) ? $_SESSION['successMessage'] : '';
$errorMessage = isset($_SESSION['errorMessage']) ? $_SESSION['errorMessage'] : '';

// Réinitialiser les messages
unset($_SESSION['successMessage']);
unset($_SESSION['errorMessage']);

// Récupérer l'ID de l'utilisateur connecté
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recherche = $_POST['valeur'];
    header("Location: livres.php?id=". $id ."&recherche=". $recherche);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Styles de la page */
        .hero {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 90vh;
            padding: 20px;
            background-color: #e2e0dee1;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background: url('../images/indoor-shot-cheerful-woman-covers-face-with-red-textbook-has-joyful-expression.jpg') no-repeat center center/cover;
            color: white; /* Couleur du texte */
        }

        .hero-content {
            width: 50%;
        }

        .hero-content h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #BB2233;
            margin-bottom: 10px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .search-bar input {
            flex: 1;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar button {
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background-color: #FA8603;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .search-bar button:hover {
            background-color: #BB2233;
        }

        .trending-documents {
            padding: 20px;
            background-color: #f9f9f9;
            text-align: center;
        }

        .trending-documents h2 {
            margin-bottom: 30px;
            font-size: 2rem;
            color: #333;
        }

        .trending-documents .document-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px; /* Ajustez l'espace entre les cartes */
        }

        .trending-documents .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 250px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .trending-documents .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .trending-documents .card-image {
            width: 100%;
            height: 150px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
        }

        .trending-documents .card-image img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .trending-documents .card-content {
            padding: 15px;
            text-align: center;
        }

        .benefits-section {
            padding: 4rem 0;
            background-color: white;
        }

        .benefits-section .card {
            border: none;
            border-radius: 10px;
        }

        .stats-section {
            padding: 60px 20px;
            background-color: #FFFFFF;
            text-align: center;
        }

        .stats-section .stat {
            display: inline-block;
            margin: 0 20px;
            text-align: center;
        }

        .stats-section .stat h3 {
            font-size: 3rem;
            color: #BB2233;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <?php include '../include/headerC.php'; ?>
    
    <!-- Hero Section -->
    <?php if (!empty($successMessage)): ?>
        <p style="color:green;"><?= htmlspecialchars($successMessage); ?></p>
    <?php endif; ?>
    
    <section class="hero">
        <div class="hero-content">
            <h1>Bienvenue sur LiveBook</h1>
            <p>Trouvez votre document favori en un clic grâce à notre plateforme moderne et rapide.</p>
            <form method="post">
                <div class="search-bar">
                    <input type="text" name="valeur" placeholder="Recherchez un document..." required>
                    <button type="submit">Rechercher</button>
                </div>
            </form>
            <?php if (!empty($errorMessage)): ?>
                <p style="color:red;"><?= htmlspecialchars($errorMessage); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Trending Documents Section -->
    <section class="trending-documents">
        <h2>Documents Tendance</h2>
        <div class="document-list" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
            <?php foreach ($documents as $document): ?>
                <div class="card">
                    <div class="card-image">
                        <button>
                            <img src="<?= htmlspecialchars($document['image']); ?>" alt="<?= htmlspecialchars($document['libelle']); ?>">
                        </button>
                    </div>
                    <div class="card-content">
                        <h5><?= htmlspecialchars($document['libelle']); ?></h5>
                        <div class="actions">
                        <?php if (empty($id)): ?>
                            <a href="login.php" class="btn btn-warning btn-sm" style="background-color: #f4a6b7; border-color: rgba(10, 15, 36, 0.85); color: black; transition: background-color 0.3s, transform 0.3s;">
                        <?php else: ?>
                            <a href="favoris.php?idD=<?= htmlspecialchars($document['_id']); ?>&lib=<?= htmlspecialchars($document['libelle']); ?>&id=<?= $id ?>&fic=<?= htmlspecialchars($document['fichier']); ?>&image=<?= htmlspecialchars($document['image']); ?>&descr=<?= htmlspecialchars($document['description']); ?>&maison=<?= htmlspecialchars($document['maison']); ?>&aut=<?= htmlspecialchars($document['auteur']); ?>" 
                            class="btn btn-warning btn-sm" 
                            style="background-color: #f4a6b7; /* Rouge pastel doux */ 
                                    border-color: rgba(10, 15, 36, 0.85); /* Limite rouge doux */ 
                                    color: black; 
                                    transition: background-color 0.3s, transform 0.3s;">
                        <?php endif; ?>
                            <i class="bi bi-heart"></i> Ajouter aux favoris
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="benefits-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12" data-aos="fade-left" data-aos-duration="800" data-aos-delay="300">
                    <div class="card bg-light shadow">
                        <div class="card-body">
                            <h2 class="card-title">Les bienfaits de la lecture</h2>
                            <p class="card-text">
                                La lecture enrichit notre esprit, améliore notre concentration et réduit le stress. Elle stimule la créativité et nous permet de voyager dans des univers fascinants, tout en développant notre vocabulaire et nos connaissances.
                            </p>
                            <a href="https://www.sciencedaily.com/releases/2013/09/130903090735.htm" target="_blank" class="btn btn-primary">
                                En savoir plus
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12" data-aos="fade-left">
                <img src="../images/side-view-man-wearing-linen-clothing-home.jpg" alt="Lecture bienfaisante" class="img-fluid rounded" style="width: 400px; max-width: 600px; height: 500px;">

                </div>
            </div>
        </div>
    </section>

    <!-- Section des chiffres animés -->
    <section class="stats-section">
        <h2>Nos Statistiques Generales</h2>
        <div class="stat">
            <h3 id="stat-docs">0</h3>
            <p>Documents ajoutés</p>
        </div>
        <div class="stat">
            <h3 id="stat-favoris">0</h3>
            <p>Documents favoris</p>
        </div>
        <div class="stat">
            <h3 id="stat-users">0</h3>
            <p>Utilisateurs</p>
        </div>
    </section>

    <!-- Scripts -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init(); // Initialiser AOS
    </script>
    <script>
        // Fonction pour animer les valeurs
        function animateValue(id, start, end, duration) {
            const element = document.getElementById(id);
            const range = end - start;
            const increment = range / (duration / 10);
            let current = start;
            const timer = setInterval(() => {
                current += increment;
                if (current >= end) {
                    clearInterval(timer);
                    current = end;
                }
                element.textContent = Math.floor(current);
            }, 10);
        }

        // Fonction de déclenchement des animations
        function triggerAnimations() {
            animateValue("stat-docs", 0, <?= $totalDocuments; ?>, 2000); // Documents ajoutés
            animateValue("stat-favoris", 0, <?= $totalFavoris; ?>, 2000); // Favoris enregistré
            animateValue("stat-users", 0, <?= $totalUtilisateurs; ?>, 2000); // Utilisateurs

        }

        // Détecter la visibilité de la section
        document.addEventListener("DOMContentLoaded", () => {
            const statsSection = document.querySelector(".stats-section");
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        triggerAnimations(); // Lancer les animations
                        observer.unobserve(statsSection); // Arrêter d'observer après le premier déclenchement
                    }
                });
            }, { threshold: 0.5 });

            observer.observe(statsSection);
        });
    </script>

    <?php include '../include/footerC.php'; ?>
</body>
</html>
