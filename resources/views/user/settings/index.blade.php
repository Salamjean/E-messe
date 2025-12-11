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

                <!-- Préférences Générales Section (Placeholder) -->
                <div class="card shadow-sm mb-4"
                    style="border-radius: 15px !important; border: 1px solid #e2dcdcff !important;">
                    <div class="card-body">
                        <h5 class="card-title mb-4 font-weight-bold">Préférences Générales</h5>
                        <!-- Add content here if needed in future -->
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

        /* CSS pour espacer les icônes et le texte */
        .card-body .d-flex .mr-3 {
            margin-right: 15px !important;
        }
    </style>
@endsection
