<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="/" class="logo">
                        <img src="{{asset('assets/assets/images/sancta.jpg')}}" style="width:90px; margin-top:10px" alt="">
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                      <li><a href="/" class="active">Accueil</a></li>
                      <li><a href="#">Paroisses</a></li>
                      <li><a href="#">Messes</a></li>
                      <li><a href="#">Contactez-nous</a></li>
                      <li><a href="{{route('login')}}" style="background-color: #f35525; color:black"><i class="fa-solid fa-arrow-right-to-bracket" style="background-color: black;color:white"></i></i>Se connecter</a></li>
                  </ul>   
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
  </header>

  <style>
    /* Le bouton Se connecter sera toujours visible dans la navigation */
.nav li:last-child {
    display: block !important;
}

/* Pour les écrans mobiles (max-width: 768px) */
@media (max-width: 768px) {
    /* Styles spécifiques pour mobile si nécessaire */
    .nav li:last-child a {
        background-color: black !important;
        padding: 12px px;
        font-size: 14px;
        color: red
    }
    
    /* Ajustement de l'icône pour mobile */
    .nav li:last-child a i {
        font-size: 16px;
    }

    .seco{
        background-color: red
    }
}

/* Pour les écrans desktop (min-width: 769px) */
@media (min-width: 769px) {
    /* Styles spécifiques pour desktop si nécessaire */
    .nav li:last-child a {
        background-color: #e67e22; /* Exemple de style */
        border-radius: 4px;
        margin-left: 10px;
        color: red
    }
    
    .nav li:last-child a:hover {
        background-color: #d35400; /* Couleur au survol */
    }
}
  </style>