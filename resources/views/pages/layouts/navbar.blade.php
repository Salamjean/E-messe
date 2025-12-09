<header class="modern-navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <div class="navbar-logo">
            <a href="/">
                <img src="{{ asset('assets/assets/images/sancta.png') }}" alt="E-Messe Logo">
                {{-- <span class="logo-text">E-Messe</span> --}}
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="navbar-menu">
            <ul class="nav-links">

                <li>
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        Accueil
                    </a>
                </li>

                <li>
                    <a href="{{ route('fonctionnalites') }}"
                        class="nav-link {{ request()->routeIs('fonctionnalites') ? 'active' : '' }}">
                        Fonctionnalités
                    </a>
                </li>

                <li>
                    <a href="{{ route('messes') }}" class="nav-link {{ request()->routeIs('messes') ? 'active' : '' }}">
                        Messe
                    </a>
                </li>

                <li>
                    <a href="{{ route('paroisses') }}"
                        class="nav-link {{ request()->routeIs('paroisses') ? 'active' : '' }}">
                        Paroisse
                    </a>
                </li>

                <li>
                    <a href="{{ route('evenements') }}"
                        class="nav-link {{ request()->routeIs('evenements') ? 'active' : '' }}">
                        Événements
                    </a>
                </li>

                <li>
                    <a href="{{ route('avantages') }}"
                        class="nav-link {{ request()->routeIs('avantages') ? 'active' : '' }}">
                        Avantages
                    </a>
                </li>

                <li>
                    <a href="{{ route('contact') }}"
                        class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                        Contact
                    </a>
                </li>

            </ul>
        </nav>


        <!-- Login Button -->
        <div class="navbar-actions">
            <a href="{{ route('login') }}" class="btn-login">Se connecter</a>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<style>
    /* Modern Navbar Styles */
    .modern-navbar {
        background: #ffffff;
        border-bottom: 2px solid #e5e5e5;
        padding: 0;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .navbar-container {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 40px;
        height: 70px;
    }

    /* Logo Styles */
    .navbar-logo a {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        transition: opacity 0.3s ease;
    }

    .navbar-logo a:hover {
        opacity: 0.8;
    }

    .navbar-logo img {
        width: 45px;
        height: 45px;
        object-fit: contain;
    }

    .logo-text {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        letter-spacing: -0.5px;
    }

    /* Navigation Menu */
    .navbar-menu {
        flex: 1;
        display: flex;
        justify-content: center;
        margin: 0 30px;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 35px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-link {
        color: #333;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        position: relative;
        transition: color 0.3s ease;
        white-space: nowrap;
    }

    .nav-link:hover,
    .nav-link.active {
        color: #C5A572;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 0;
        height: 2px;
        background: #C5A572;
        transition: width 0.3s ease;
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 100%;
    }

    /* Login Button */
    .navbar-actions {
        display: flex;
        align-items: center;
    }

    .btn-login {
        background: linear-gradient(135deg, #C5A572 0%, #B89456 100%);
        color: #ffffff;
        text-decoration: none;
        padding: 12px 32px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(197, 165, 114, 0.25);
        white-space: nowrap;
    }

    .btn-login:hover {
        background: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(197, 165, 114, 0.35);
        color: #C5A572;
        border: 1px solid #C5A572;
    }

    .btn-login:active {
        transform: translateY(0);
    }

    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
        display: none;
        flex-direction: column;
        gap: 5px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
    }

    .mobile-menu-toggle span {
        display: block;
        width: 25px;
        height: 3px;
        background: #333;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .navbar-container {
            padding: 0 25px;
        }

        .nav-links {
            gap: 25px;
        }

        .nav-link {
            font-size: 14px;
        }
    }

    @media (max-width: 768px) {
        .navbar-container {
            padding: 0 20px;
            height: 65px;
        }

        .navbar-logo img {
            width: 40px;
            height: 40px;
        }

        .logo-text {
            font-size: 18px;
        }

        .navbar-menu {
            position: fixed;
            top: 65px;
            left: -100%;
            width: 100%;
            height: calc(100vh - 65px);
            background: #ffffff;
            flex-direction: column;
            justify-content: flex-start;
            padding: 30px 20px;
            transition: left 0.3s ease;
            margin: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-menu.active {
            left: 0;
        }

        .nav-links {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
            width: 100%;
        }

        .nav-link {
            font-size: 16px;
            padding: 10px 0;
            width: 100%;
        }

        .nav-link::after {
            bottom: 5px;
        }

        .navbar-actions {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            z-index: 1001;
        }

        .btn-login {
            width: 100%;
            text-align: center;
            padding: 14px 32px;
        }

        .mobile-menu-toggle {
            display: flex;
        }

        .mobile-menu-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(8px, 8px);
        }

        .mobile-menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -7px);
        }
    }

    @media (max-width: 480px) {
        .navbar-container {
            padding: 0 15px;
        }

        .logo-text {
            display: none;
        }
    }
</style>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const navbarMenu = document.querySelector('.navbar-menu');

        if (menuToggle && navbarMenu) {
            menuToggle.addEventListener('click', function() {
                menuToggle.classList.toggle('active');
                navbarMenu.classList.toggle('active');
            });

            // Close menu when clicking on a link
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    menuToggle.classList.remove('active');
                    navbarMenu.classList.remove('active');
                });
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.navbar-container')) {
                    menuToggle.classList.remove('active');
                    navbarMenu.classList.remove('active');
                }
            });
        }
    });
</script>
