<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-content">
            <!-- Logo et description -->
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="{{ asset('assets/assets/images/logo_footer.svg') }}" alt="Logo">
                    {{-- <span class="brand-name">E-Messe</span> --}}
                </div>
                <p class="footer-description">La plateforme de gestion des réservations de messes.</p>
            </div>

            <!-- Colonnes de liens -->
            <div class="footer-columns">
                <!-- Produit -->
                <div class="footer-column">
                    <h3 class="footer-title">Produit</h3>
                    <ul class="footer-links">
                        <li><a href="#fonctionnalites">Fonctionnalités</a></li>
                        <li><a href="#gestion">Gestion des Messes</a></li>
                        <li><a href="#paroisses">Pour les paroisses</a></li>
                    </ul>
                </div>

                <!-- Ressources -->
                <div class="footer-column">
                    <h3 class="footer-title">Ressources</h3>
                    <ul class="footer-links">
                        <li><a href="#documentation">Documentation</a></li>
                        <li><a href="#support">Support</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="footer-column">
                    <h3 class="footer-title">Contact</h3>
                    <ul class="footer-links">
                        <li><a href="mailto:info@emesse.com">Email: info@emesse.com</a></li>
                        <li><a href="tel:+225017175500">Tél: +225 01 71 75 50 00</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom">
            <div class="copyright">
                <p>{{ date('Y') }} E-MESSE. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</footer>

<style>
    /* General cleanup and base styles */
    .site-footer {
        background: linear-gradient(135deg, #f5f7fa 0%, #e3e8ed 100%);
        padding: 60px 0 20px;
        margin-top: 80px;
        border-top: 1px solid #d1d5db;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    @media (min-width: 1024px) {

        /* Apply on PC only (e.g., screens larger than 1024px) */
        .footer-content {
            margin-left: -80px !important;
        }
    }

    .footer-content {
        display: grid;
        grid-template-columns: 1.5fr 2.5fr;
        gap: 60px;
        margin-bottom: 40px;
    }

    .footer-brand {
        display: flex;
        flex-direction: column;
        gap: 16px;
        /* Removed problematic fixed margins and alignments */
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .footer-logo img {
        height: 45px;
        width: auto;
        object-fit: contain;
    }

    .brand-name {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        letter-spacing: -0.5px;
    }

    .footer-description {
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
        max-width: 300px;
        margin: 0;
        margin-left: -13px !important;
        /* Removed problematic fixed margins */
    }

    .footer-columns {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
    }

    .footer-column {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .footer-title {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin: 0;
        margin-bottom: 4px;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .footer-links li a {
        color: #6b7280;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .footer-links li a:hover {
        color: #3b82f6;
        transform: translateX(4px);
    }

    .footer-bottom {
        padding-top: 30px;
        border-top: 1px solid #d1d5db;
    }

    .copyright {
        text-align: center;
    }

    .copyright p {
        color: #6b7280;
        font-size: 14px;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .copyright p::before {
        content: "©";
        font-size: 16px;
    }

    /* Responsive Design */

    /* Tablet and small desktop screens */
    @media (max-width: 1024px) {
        .site-footer {
            padding: 50px 0 20px;
            margin-top: 70px;
        }

        .footer-content {
            grid-template-columns: 1fr;
            /* Stack content vertically */
            gap: 40px;
        }

        .footer-columns {
            grid-template-columns: repeat(2, 1fr);
            /* Two columns for links */
            gap: 30px;
        }

        .footer-brand {
            align-items: center;
            /* Center brand content */
            text-align: center;
        }

        .footer-logo {
            justify-content: center;
            /* Center logo within brand */
        }

        .footer-description {
            max-width: 80%;
            /* Adjust max-width for better readability */
        }
    }

    /* Mobile screens */
    @media (max-width: 640px) {
        .site-footer {
            padding: 40px 0 20px;
            margin-top: 60px;
        }

        .footer-content {
            gap: 30px;
        }

        .footer-columns {
            grid-template-columns: 1fr;
            /* Stack link columns vertically */
            gap: 25px;
        }

        .footer-brand {
            align-items: center;
            text-align: center;
        }

        .footer-logo {
            justify-content: center;
        }

        .footer-description {
            max-width: 90%;
            margin: 0 auto;
            /* Center description text block */
        }

        .footer-column {
            text-align: center;
            /* Center text for each column */
        }

        .footer-links {
            align-items: center;
            /* Center links within columns */
        }

        .footer-links li a:hover {
            transform: translateX(0) scale(1.05);
            /* Adjust hover for touch devices */
        }
    }

    /* Animation pour le chargement */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .site-footer {
        animation: fadeInUp 0.6s ease-out;
    }

    .footer-column {
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }

    .footer-column:nth-child(1) {
        animation-delay: 0.1s;
    }

    .footer-column:nth-child(2) {
        animation-delay: 0.2s;
    }

    .footer-column:nth-child(3) {
        animation-delay: 0.3s;
    }
</style>
