<!-- Download Modal -->
<div class="modal fade" id="downloadAppModal" tabindex="-1" aria-labelledby="downloadAppModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content download-modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pb-5">
                <div class="modal-icon mb-4">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="modal-title mb-3" id="downloadAppModalLabel">Télécharger E-MESSE</h3>
                <p class="text-muted mb-4">Choisissez votre plateforme pour télécharger l'application</p>

                <div class="d-grid gap-3 col-10 mx-auto">
                    <a href="#" class="btn btn-store btn-apple">
                        <i class="fab fa-apple"></i>
                        <div class="store-text">
                            <span>Télécharger sur</span>
                            <strong>App Store</strong>
                        </div>
                    </a>

                    <a href="https://play.google.com/store/apps/details?id=com.kks.maparoisse"
                        class="btn btn-store btn-google">
                        <i class="fab fa-google-play"></i>
                        <div class="store-text">
                            <span>DISPONIBLE SUR</span>
                            <strong>Google Play</strong>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal Styles */
    .download-modal-content {
        border-radius: 25px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--light-gold) 0%, var(--primary-gold) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        color: white;
        font-size: 2.5rem;
        box-shadow: 0 5px 15px rgba(197, 165, 114, 0.4);
    }

    .btn-store {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px 25px;
        border-radius: 15px;
        text-align: left;
        transition: all 0.3s ease;
        text-decoration: none;
        border: 2px solid transparent;
    }

    .btn-store i {
        font-size: 2.5rem;
        margin-right: 15px;
    }

    .store-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .store-text span {
        font-size: 0.8rem;
        text-transform: uppercase;
    }

    .store-text strong {
        font-size: 1.2rem;
        font-weight: 700;
    }

    .btn-apple {
        background-color: #000;
        color: #fff;
    }

    .btn-apple:hover {
        background-color: #333;
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .btn-google {
        background-color: white;
        color: #333;
        border: 2px solid #e0e0e0;
    }

    .btn-google:hover {
        background-color: #f8f9fa;
        border-color: #ccc;
        color: #333;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
</style>
