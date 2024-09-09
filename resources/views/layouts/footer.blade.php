<!-- Footer Section -->
 <style>
    footer {
    background-color: #000; /* Fond noir pour le footer */
    color: #fff; /* Texte en blanc */
}

footer h5 {
    font-weight: 700;
}

footer a {
    color: #fff;
    text-decoration: none;
}

footer a:hover {
    color: #ff0000; /* Rouge vif au survol des liens */
}

footer .list-unstyled li {
    margin-bottom: 0.5rem;
}

footer .bi {
    font-size: 1.25rem;
    vertical-align: middle;
}

footer .d-flex {
    align-items: center;
}
 </style>
<footer class="bg-dark text-light py-4" style="height: auto; margin-top: 11.5vh;">
    <div class="container">
        <div class="row">
            <!-- Informations de Contact -->
            <div class="col-md-4 mb-3">
                <h5 class="text-danger">Contact</h5>
                <ul class="list-unstyled">
                    <li><i class="bi bi-geo-alt"></i>&nbsp;&nbsp;Bruxelles, Belgique</li>
                    <li><i class="bi bi-envelope"></i><a href="mailto:contact@lowbudget.be">&nbsp;&nbsp;contact@lowbudget.be</a></li>
                    <li><i class="bi bi-telephone"></i><a href="tel:+32498196523"></a>&nbsp;&nbsp;+32498/19.65.23</li>
                </ul>
            </div>

            <!-- Liens Utiles -->
            <div class="col-md-4 mb-3">
                <h5 class="text-danger">Liens Utiles</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-light">Accueil</a></li>
                    <li><a href="#" class="text-light">Produits</a></li>
                    <li><a href="#" class="text-light">À Propos</a></li>
                    <li><a href="#" class="text-light">Contact</a></li>
                </ul>
            </div>

            <!-- Réseaux Sociaux -->
            <div class="col-md-4 mb-3">
                <h5 class="text-danger">Suivez-nous</h5>
                <div class="d-flex">
                    <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-light me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-light me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-light"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>

        <!-- Bas de page -->
        <div class="text-center mt-4">
            <p class="mb-0">&copy; {{ date('Y') }} Low budget gaming pc. Tous droits réservés.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
