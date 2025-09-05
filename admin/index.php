<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin page</title>

    <style>
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
            overflow: hidden;
            color: white; /* Couleur du texte */
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Couleur noire avec transparence */
            z-index: 1;
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

        .hero-content p {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include '../include/headerA.php'; ?> <!-- Inclusion du fichier headerA.php -->

    <section class="hero">
        <div class="hero-content">
            <h1>Bienvenue sur LiveBook</h1>
            <p>Gérer les Auteurs et leur Documents.</p>
        </div>
    </section>

    <?php include '../include/footerA.php'; ?> <!-- Inclusion du fichier footer.php -->
</body>
</html>
