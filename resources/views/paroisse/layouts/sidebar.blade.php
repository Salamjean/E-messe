<!-- Overlay pour mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="mdc-drawer mdc-drawer--dismissible" id="sidebar">
    <!-- En-tête du menu latéral -->
    <div class="mdc-drawer__header">
        <a href="{{ route('paroisse.dashboard') }}" class="brand-logo">
            <img src="{{ optional(Auth::guard('paroisse')->user())->profile_picture
                ? asset('storage/' . Auth::guard('paroisse')->user()->profile_picture)
                : asset('assets/assets/images/sancta.jpg') }}"
                style="width: 50%; margin-left:50px" alt="logo">
        </a>
    </div>

    <!-- Contenu du menu -->
    <div class="mdc-drawer__content">
        <!-- Informations utilisateur -->
        <div class="user-info">
            <p class="name text-center">{{ Auth::guard('paroisse')->user()->name }}</p>
            <p class="email text-center">{{ Auth::guard('paroisse')->user()->email }}</p>
        </div>

        <div class="mdc-list-group">
            <nav class="mdc-list mdc-drawer-menu">

                <!-- Tableau de bord -->
                <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{ route('paroisse.dashboard') }}">
                        <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon">dashboard</i>
                        Tableau de bord
                    </a>
                </div>

                <!-- Ajouter un évènement -->
                <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{ route('event.index') }}">
                        <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon">event</i>
                        Ajouter un évènement
                    </a>
                </div>

                <!-- Montant de demande -->
                <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{ route('paroisse.offrande') }}">
                        <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon">attach_money</i>
                        Montant de Messes
                    </a>
                </div>
                <!-- Faire un reversement -->
                <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{ route('reversement.list_reversement') }}">
                        <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon">compare_arrows</i>
                        Reversement
                    </a>
                </div>
                <!-- Menu Déroulant: Messes demandées -->
                <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-expansion-panel-link d-flex align-items-center justify-content-between" href="#"
                        data-toggle="expansionPanel" data-target="messes-menu">
                        <span class="d-flex align-items-center">
                            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon me-2">
                                account_balance_wallet
                            </i>
                            <span>Messes demandées</span>
                        </span>
                        <i class="mdc-drawer-arrow material-icons">chevron_right</i>
                    </a>

                    <div class="mdc-expansion-panel" id="messes-menu">
                        <nav class="mdc-list mdc-drawer-submenu">
                            <div class="mdc-list-item mdc-drawer-item">
                                <a class="mdc-drawer-link" href="{{ route('demandes.messes.validate') }}">
                                    En attente confirmation
                                </a>
                            </div>
                            <div class="mdc-list-item mdc-drawer-item">
                                <a class="mdc-drawer-link" href="{{ route('demandes.messes.index') }}">
                                    A célébrées
                                </a>
                            </div>
                            <div class="mdc-list-item mdc-drawer-item">
                                <a class="mdc-drawer-link" href="{{ route('demandes.messes.history') }}">
                                    Historique célébrations
                                </a>
                            </div>
                        </nav>
                    </div>
                </div>

                <!-- Menu Déroulant: Paroissien -->
                <div class="mdc-list-item mdc-drawer-item">
                    @php
                        $isParoissienActive = Route::is('paroissien.*');
                    @endphp

                    <a class="mdc-expansion-panel-link d-flex align-items-center justify-content-between {{ $isParoissienActive ? 'expanded' : '' }}"
                        href="#" data-toggle="expansionPanel" data-target="menu-paroissien">
                        <span class="d-flex align-items-center">
                            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon me-2">
                                account_balance_wallet
                            </i>
                            <span>Paroissien</span>
                        </span>
                        <i class="mdc-drawer-arrow material-icons">chevron_right</i>
                    </a>

                    <div class="mdc-expansion-panel {{ $isParoissienActive ? 'expanded' : '' }}" id="menu-paroissien">
                        <nav class="mdc-list mdc-drawer-submenu">

                            <div class="mdc-list-item mdc-drawer-item">
                                <a class="mdc-drawer-link {{ Route::is('paroissien.create') ? 'active' : '' }}"
                                    href="{{ route('paroissien.create') }}">
                                    Ajoute un paroissien
                                </a>
                            </div>

                            <div class="mdc-list-item mdc-drawer-item">
                                <a class="mdc-drawer-link {{ Route::is('paroissien.index') ? 'active' : '' }}"
                                    href="{{ route('paroissien.index') }}">
                                    Liste des paroissiens
                                </a>
                            </div>

                        </nav>
                    </div>
                </div>

                <!-- Menu Déroulant: Retraits -->
                <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel"
                        data-target="menu-retrait">
                        <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon">
                            account_balance_wallet
                        </i>
                        Retrait
                        <i class="mdc-drawer-arrow material-icons">chevron_right</i>
                    </a>

                    <div class="mdc-expansion-panel" id="menu-retrait">
                        <nav class="mdc-list mdc-drawer-submenu">
                            <div class="mdc-list-item mdc-drawer-item">
                                <a class="mdc-drawer-link" href="{{ route('paroisse.retrait.create') }}">Demander</a>
                            </div>
                            <div class="mdc-list-item mdc-drawer-item">
                                <a class="mdc-drawer-link" href="{{ route('paroisse.retraits') }}">En attente</a>
                            </div>
                            <div class="mdc-list-item mdc-drawer-item">
                                <a class="mdc-drawer-link" href="{{ route('paroisse.history') }}">Historiques</a>
                            </div>
                        </nav>
                    </div>
                </div>

                <br>

                <div class="ms-5">
                    <img src="{{ asset('assetsPoste/assets/images/sidebar/logo.svg') }}" style="width: 70%;"
                        alt="logo">
                </div>
            </nav>
        </div>
    </div>
</aside>

<script>
    // Toggle du sidebar pour mobile/tablet
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggler = document.querySelector('.sidebar-toggler');

        // Toggle du sidebar
        if (sidebarToggler) {
            sidebarToggler.addEventListener('click', function() {
                sidebar.classList.toggle('open');
                sidebarOverlay.classList.toggle('active');
                document.body.classList.toggle('sidebar-open');
            });
        }

        // Fermer le sidebar en cliquant sur l'overlay
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            });
        }

        // Gérer les menus déroulants
        document.querySelectorAll('[data-toggle="expansionPanel"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('data-target');
                const targetPanel = document.getElementById(targetId);

                // Fermer tous les autres panels
                document.querySelectorAll('.mdc-expansion-panel').forEach(panel => {
                    if (panel.id !== targetId) {
                        panel.classList.remove('open');
                    }
                });

                // Basculer les flèches
                document.querySelectorAll('.mdc-drawer-arrow').forEach(arrow => {
                    if (arrow !== this.querySelector('.mdc-drawer-arrow')) {
                        arrow.style.transform = 'rotate(0deg)';
                    }
                });

                // Toggle du panel cliqué
                targetPanel.classList.toggle('open');
                const arrow = this.querySelector('.mdc-drawer-arrow');
                if (targetPanel.classList.contains('open')) {
                    arrow.style.transform = 'rotate(90deg)';
                } else {
                    arrow.style.transform = 'rotate(0deg)';
                }
            });
        });

        // Fermer automatiquement le sidebar sur mobile après un clic sur un lien
        const sidebarLinks = document.querySelectorAll('.mdc-drawer-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 991) {
                    sidebar.classList.remove('open');
                    sidebarOverlay.classList.remove('active');
                    document.body.classList.remove('sidebar-open');
                }
            });
        });
    });
</script>

<style>
    /* =================================
       SIDEBAR STYLES DE BASE
    ================================= */
    .mdc-drawer.mdc-drawer--dismissible {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 270px !important;
        min-width: 270px !important;
        background: #fff;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        overflow-y: auto;
        overflow-x: hidden;
        transition: transform 0.3s ease-in-out;
    }

    .mdc-drawer-app-content {
        margin-left: 270px !important;
        transition: margin-left 0.3s ease-in-out;
    }

    .mdc-expansion-panel {
        display: none;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .mdc-expansion-panel.open {
        display: block;
    }

    .mdc-drawer-arrow {
        transition: transform 0.3s ease;
    }

    /* Overlay pour mobile/tablet */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .sidebar-overlay.active {
        display: block;
        opacity: 1;
    }

    /* =================================
       RESPONSIVE - TABLET (< 992px)
    ================================= */
    @media (max-width: 991px) {

        /* Cacher le sidebar par défaut sur tablet */
        .mdc-drawer.mdc-drawer--dismissible {
            transform: translateX(-100%);
        }

        /* Afficher le sidebar quand ouvert */
        .mdc-drawer.mdc-drawer--dismissible.open {
            transform: translateX(0);
        }

        /* Enlever la marge du contenu principal */
        .mdc-drawer-app-content {
            margin-left: 0 !important;
        }

        /* Empêcher le scroll du body quand sidebar ouvert */
        body.sidebar-open {
            overflow: hidden;
        }

        /* Header du sidebar plus compact */
        .mdc-drawer__header {
            padding: 15px 10px;
        }

        .mdc-drawer__header img {
            width: 40% !important;
            margin-left: 30px !important;
        }

        /* Items du menu plus petits */
        .mdc-drawer-link {
            font-size: 14px;
            padding: 10px 15px;
        }

        .mdc-list-item__start-detail {
            font-size: 20px !important;
        }
    }

    /* =================================
       RESPONSIVE - MOBILE (< 768px)
    ================================= */
    @media (max-width: 767px) {

        /* Sidebar prend toute la largeur sur mobile */
        .mdc-drawer.mdc-drawer--dismissible {
            width: 280px !important;
            min-width: 280px !important;
        }

        /* User info dans le sidebar */
        .user-info {
            padding: 10px 15px;
        }

        .user-info .name {
            font-size: 15px;
            margin-bottom: 5px;
        }

        .user-info .email {
            font-size: 12px;
        }

        /* Logo en bas du sidebar plus petit */
        .ms-5 {
            margin-left: 2rem !important;
        }

        .ms-5 img {
            width: 60% !important;
        }
    }

    /* =================================
       RESPONSIVE - PETIT MOBILE (< 576px)
    ================================= */
    @media (max-width: 575px) {

        /* Sidebar prend presque toute la largeur */
        .mdc-drawer.mdc-drawer--dismissible {
            width: 85vw !important;
            min-width: 85vw !important;
            max-width: 300px;
        }

        /* Header encore plus compact */
        .mdc-drawer__header {
            padding: 10px;
        }

        .mdc-drawer__header img {
            width: 35% !important;
            margin-left: 20px !important;
        }

        /* Menu items */
        .mdc-drawer-link {
            font-size: 13px;
            padding: 8px 12px;
        }

        /* Sous-menus */
        .mdc-drawer-submenu .mdc-drawer-link {
            font-size: 12px;
            padding-left: 40px;
        }
    }

    /* =================================
       AMÉLIORATION DE L'UX
    ================================= */

    /* Scroll personnalisé pour le sidebar */
    .mdc-drawer.mdc-drawer--dismissible::-webkit-scrollbar {
        width: 6px;
    }

    .mdc-drawer.mdc-drawer--dismissible::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .mdc-drawer.mdc-drawer--dismissible::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .mdc-drawer.mdc-drawer--dismissible::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Effet hover sur les liens */
    .mdc-drawer-link {
        transition: background-color 0.2s ease, padding-left 0.2s ease;
    }

    .mdc-drawer-link:hover {
        background-color: rgba(0, 0, 0, 0.05);
        padding-left: 20px;
    }

    /* Animation des icônes */
    .mdc-list-item__start-detail {
        transition: transform 0.2s ease;
    }

    .mdc-drawer-link:hover .mdc-list-item__start-detail {
        transform: scale(1.1);
    }
</style>
