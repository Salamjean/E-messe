@extends('user.layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">Paramètre</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <!-- Compte Section -->
                <div class="card shadow-sm mb-4 border-3"
                    style="border-radius: 15px !important; border: 1px solid #e2dcdcff !important;">
                    <div class="card-body">
                        <h5 class="card-title mb-4 font-weight-bold">Compte</h5>

                        <a href="{{ route('user.settings.profile') }}"
                            class="d-flex align-items-center text-dark text-decoration-none py-3 border-bottom">
                            <div class="mr-3">
                                <i class="fas fa-user fa-lg"></i>
                            </div>
                            <div>
                                <span class="h6 mb-0">Modifier le profil</span>
                            </div>
                        </a>

                        <a href="{{ route('user.settings.password') }}"
                            class="d-flex align-items-center text-dark text-decoration-none py-3">
                            <div class="mr-3">
                                <i class="fas fa-lock fa-lg"></i>
                            </div>
                            <div>
                                <span class="h6 mb-0">Changer le mot de passe</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Notifications Section -->
                <div class="card shadow-sm mb-4"
                    style="border-radius: 15px !important; border: 1px solid #e2dcdcff !important;">
                    <div class="card-body">
                        <h5 class="card-title mb-4 font-weight-bold">Notifications</h5>

                        <!-- SMS (Hidden as per request) -->
                        {{-- 
                    <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="mr-3"><i class="fas fa-comment fa-lg"></i></div>
                            <span class="h6 mb-0">SMS</span>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="smsSwitch">
                            <label class="custom-control-label" for="smsSwitch"></label>
                        </div>
                    </div>
                    --}}

                        <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="mr-3"><i class="fas fa-envelope fa-lg"></i></div>
                                <span class="h6 mb-0">Email</span>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="emailSwitch" checked>
                                <label class="custom-control-label" for="emailSwitch"></label>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between py-3">
                            <div class="d-flex align-items-center">
                                <div class="mr-3"><i class="fas fa-bell fa-lg"></i></div>
                                <span class="h6 mb-0">Notifications Push</span>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="pushSwitch" checked>
                                <label class="custom-control-label" for="pushSwitch"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Préférences Générales Section -->
                <div class="card shadow-sm mb-4" id="tutorials"
                    style="border-radius: 15px !important; border: 1px solid #e2dcdcff !important;">
                    <div class="card-body">
                        <h5 class="card-title mb-4 font-weight-bold">Préférences Générales</h5>
                        <h5 class="card-title mb-4 font-weight-bold">Tutoriels</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="tutorial-card p-3 border rounded shadow-sm bg-light">
                                    <h6 class="font-weight-bold mb-2">Comment faire une demande de messe ?</h6>
                                    <div class="ratio ratio-16x9">
                                        <video controls class="rounded">
                                            <source src="{{ asset('assets/assets/video_tutoriel/demande_messe.mp4') }}"
                                                type="video/mp4">
                                            Votre navigateur ne supporte pas la lecture de vidéos.
                                        </video>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-6 mb-3">
                                <div class="tutorial-card p-3 border rounded shadow-sm bg-light">
                                    <h6 class="font-weight-bold mb-2">Gérer mon profil et mes notifications</h6>
                                    <div class="ratio ratio-16x9">
                                        <video controls class="rounded">
                                            <source src="{{ asset('assets/assets/video_tutoriel/demande_messe.mp4') }}"
                                                type="video/mp4">
                                            Votre navigateur ne supporte pas la lecture de vidéos.
                                        </video>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <!-- Déconnexion Section -->
                <div class="card shadow-sm mb-4 border-0" style="border-radius: 15px !important;">
                    <div class="card-body p-0">
                        <a href="{{ route('user.logout') }}"
                            class="d-flex align-items-center justify-content-center text-danger text-decoration-none py-3 font-weight-bold"
                            style="border-radius: 15px; background-color: #fff0f0;">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Se déconnecter
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Add specific styles for settings if needed, or put in public/assets/styles.css */
        .card-title {
            color: #1a1a1a;
        }

        .custom-control-input:checked~.custom-control-label::before {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        /* CSS pour espacer les icîones et le texte */
        .card-body .d-flex .mr-3 {
            margin-right: 15px !important;
        }

        .tutorial-card {
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0 !important;
            border-radius: 12px !important;
            overflow: hidden;
            background: white !important;
        }

        .tutorial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08) !important;
            border-color: #007bff !important;
        }

        .tutorial-card h6 {
            color: #333;
            font-size: 0.95rem;
        }

        .ratio-16x9 iframe,
        .ratio-16x9 video {
            border-radius: 8px;
            border: none;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endsection
