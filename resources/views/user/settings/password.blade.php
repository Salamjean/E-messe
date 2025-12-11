@extends('user.layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('user.settings.index') }}" class="text-decoration-none text-dark">
                    <h5 class="mb-0"><i class="fas fa-arrow-left mr-2"></i> Retour</h5>
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h3 class="text-center mb-5 font-weight-bold">Changer le mot de passe</h3>

                <div class="card shadow-sm border-0" style="border-radius: 15px !important;">
                    <div class="card-body p-5">

                        @if (session('success'))
                            <div class="alert alert-success fade show" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Lock Icon -->
                        <div class="text-center mb-4">
                            <div
                                style="width: 80px; height: 80px; background-color: #d4a762; border-radius: 20px; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="fas fa-lock fa-3x text-white"></i>
                            </div>
                        </div>

                        <p class="text-center text-muted mb-5">
                            Pour sécuriser votre compte, veuillez entrer votre mot de passe actuel ainsi que le nouveau.
                        </p>

                        <form action="{{ route('user.settings.updatePassword') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Mot de passe actuel -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-muted small">Mot de passe actuel</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i
                                                class="fas fa-lock text-muted"></i></span>
                                    </div>
                                    <input type="password" class="form-control border-left-0" name="current_password"
                                        placeholder="Votre mot de passe actuel">
                                </div>
                            </div>

                            <!-- Nouveau mot de passe -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-muted small">Nouveau mot de passe</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i
                                                class="fas fa-key text-muted"></i></span>
                                    </div>
                                    <input type="password" class="form-control border-left-0" name="password"
                                        placeholder="Nouveau mot de passe">
                                </div>
                            </div>

                            <!-- Confirmer le mot de passe -->
                            <div class="form-group mb-5">
                                <label class="font-weight-bold text-muted small">Confirmer le nouveau mot de passe</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i
                                                class="fas fa-check-circle text-muted"></i></span>
                                    </div>
                                    <input type="password" class="form-control border-left-0" name="password_confirmation"
                                        placeholder="Répétez le nouveau mot de passe">
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-warning btn-block text-white font-weight-bold py-3"
                                    style="background-color: #d4a762; border-color: #d4a762; border-radius: 10px;">
                                    Mettre à jour le mot de passe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus {
            box-shadow: none;
            border-color: #d4a762;
        }

        .input-group-text {
            background-color: transparent;
            border-right: none;
        }

        .form-control {
            border-left: none;
            height: 50px;
        }

        /* Fix for input group borders */
        .input-group>.form-control:not(:first-child) {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .input-group>.input-group-prepend>.input-group-text {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-right: 0;
        }
    </style>
@endsection
