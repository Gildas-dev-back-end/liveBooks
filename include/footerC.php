
</head>
<footer class="bg-light text-center text-lg-start border-top mt-5">
    <div class="container py-4">
        <div class="row">
            <!-- Section À propos -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h5 class="text-uppercase mb-3" style="color: #BB2233;">À propos</h5>
                <p style="color: #0A0F24;">
                    LiveBook est une plateforme dédiée à la gestion et au partage de documents, avec un accent sur l'expérience utilisateur et la modernité.
                </p>
            </div>
            <!-- Section Liens rapides -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h5 class="text-uppercase mb-3" style="color: #BB2233;">Liens rapides</h5>
                <ul class="list-unstyled">
                    <li><?php if (isset($id) && !empty($id)): ?>
                        <a href="favoris.php?id=<?=$id?>" class="text-decoration-none" style="color: #0A0F24;">Favoris</a>
                        <?php else: ?>
                            <a href="login.php" class="text-decoration-none" style="color: #0A0F24;">Favoris</a>
                        <?php endif; ?>
                    </li>
                    <li><a href="Livres.php" class="text-decoration-none" style="color: #0A0F24;">Tout les livres</a></li>
                </ul>
            </div>
            <!-- Section Contact -->
            <div class="col-lg-4 col-md-12">
                <h5 class="text-uppercase mb-3" style="color: #BB2233;">Contact</h5>
                <ul class="list-unstyled" style="color: #0A0F24;">
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FA8603" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                        </svg> +237 698 44 80 24
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FA8603" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                        </svg> contact@livebook.com
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FA8603" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                        </svg> Ngaoundéré, Cameroun
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Section droits réservés -->
    <div class="text-center py-3" style="background-color: rgba(10, 15, 36, 0.9); color: #FAE3CF;">
        2024 <a class="text-light" href="../pages/index.php" style="text-decoration:none; color: #FAE3CF;">
            LiveBook 
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#FAE3CF" class="bi bi-book-half" viewBox="0 0 16 16">
                <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
            </svg>
        </a>.  © <b class=" text-warning" style="color: #FA8603;">EvoDevs</b> Tous droits réservés.
    </div>

</footer>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>