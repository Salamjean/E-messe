@extends('paroisse.layouts.template')

@section('content')
    <!-- JQuery (Indispensable pour ce code) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Liens CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #c49d54;
            --dark: #5ea7b5;
            --light: #ffffff;
            --gray: #f8f9fa;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            background-color: #f9fafb;
            color: var(--dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .retrait-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 20px;
        }

        .retrait-header {
            background: linear-gradient(135deg, var(--dark));
            color: var(--light);
            border-radius: var(--border-radius);
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: var(--box-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .retrait-header h1 {
            font-weight: 700;
            margin: 0;
            font-size: 28px;
        }

        .retrait-header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }

        .user-profile {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        .user-profile img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-modern {
            background: var(--light);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
            margin-bottom: 25px;
            overflow: hidden;
            transition: var(--transition);
        }

        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .card-header-modern {
            background: linear-gradient(135deg, var(--primary));
            color: white;
            padding: 18px 25px;
            font-weight: 600;
            font-size: 18px;
            border: none;
        }

        .card-body-modern {
            padding: 30px;
        }

        .solde-card {
            background: linear-gradient(var(--dark) 100%);
            color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .solde-icon {
            background: rgba(255, 255, 255, 0.15);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-size: 24px;
        }

        .solde-info h3 {
            font-size: 16px;
            margin: 0 0 5px;
            opacity: 0.9;
            font-weight: 500;
        }

        .solde-info .montant {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }

        .solde-info .texte {
            font-size: 13px;
            opacity: 0.8;
            margin: 5px 0 0;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-control-modern {
            border: 2px solid #eef2f6;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 16px;
            transition: var(--transition);
        }

        .form-control-modern:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(243, 85, 37, 0.25);
        }

        .info-additionnelle {
            background: #f8f9fa;
            border-left: 4px solid var(--primary);
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }

        .info-additionnelle h6 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .btn-retour {
            background: #f8f9fa;
            color: var(--dark);
            border: 2px solid #eef2f6;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-retour:hover {
            background: #e9ecef;
            color: var(--dark);
        }

        .btn-soumettre {
            background: linear-gradient(135deg, var(--primary));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-soumettre:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 85, 37, 0.3);
            color: white;
        }

        /* Spinner pour le bouton */
        .spinner-loading {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: text-bottom;
            border: .2em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border .75s linear infinite;
        }

        @keyframes spinner-border {
            100% {
                transform: rotate(360deg);
            }
        }

        .method-badge {
            display: inline-block;
            background: rgba(243, 85, 37, 0.1);
            color: var(--primary);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .retrait-header {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }

            .user-profile {
                margin-top: 15px;
            }

            .solde-card {
                flex-direction: column;
                text-align: center;
            }

            .solde-icon {
                margin-right: 0;
                margin-bottom: 15px;
            }
        }
    </style>

    <div class="retrait-container">
        <!-- En-tête -->
        <div class="retrait-header">
            <div>
                <h1><i class="fas fa-money-bill-wave me-2"></i>Demande de Retrait</h1>
                <p>Gérez vos retraits de fonds, {{ Auth::guard('paroisse')->user()->name }}!</p>
            </div>
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('paroisse')->user()->name) }}"
                    alt="Profile">
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <!-- Carte de formulaire -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <i class="fas fa-pen-to-square me-2"></i>Formulaire de Demande de Retrait
                    </div>
                    <div class="card-body-modern">
                        <!-- Carte d'information sur le solde -->
                        <div class="solde-card">
                            <div class="solde-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="solde-info">
                                <h3>Solde disponible</h3>
                                <p class="montant">{{ number_format($soldeDisponible ?? 0, 0, ',', ' ') }} FCFA</p>
                                <p class="texte">Montant maximum que vous pouvez retirer</p>
                            </div>
                        </div>

                        <!-- Formulaire de retrait -->
                        <form id="retraitForm">
                            @csrf

                            <!-- Montant à retirer (Commun) -->
                            <div class="mb-4">
                                <label for="montant" class="form-label">Montant à retirer (FCFA) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-modern" id="montant"
                                        name="montant" required min="1000" max="{{ $soldeDisponible ?? 0 }}"
                                        placeholder="Ex: 5000">
                                </div>
                                <div class="form-text text-muted mt-2">Le montant minimum de retrait est de 1 000 FCFA
                                </div>
                            </div>

                            <!-- Méthode de retrait -->
                            <div class="mb-4">
                                <label for="methode" class="form-label">Méthode de retrait <span
                                        class="text-danger">*</span></label>
                                <select class="form-select form-control-modern" id="methode" name="methode" required>
                                    <option value="">-- Sélectionnez une méthode --</option>
                                    <option value="wave">Wave</option>
                                    <option value="orange_money">Orange Money</option>
                                    <option value="mtn_money">MTN Money</option>
                                    <option value="moov_money">Moov Money</option>
                                    <option value="virement_bancaire">Virement Bancaire</option>
                                </select>
                            </div>

                            <!-- SECTION MOBILE MONEY -->
                            <div id="section-mobile-money" class="animate-section">
                                <div class="row mb-4">
                                    <label class="form-label">Numéro du destinataire <span
                                            class="text-danger">*</span></label>
                                    <div class="col-4">
                                        <select class="form-select form-control-modern mobile-input" name="prefix"
                                            id="prefix">
                                            <option value="225">🇨🇮 +225</option>
                                        </select>
                                    </div>
                                    <div class="col-8">
                                        <input type="tel" class="form-control form-control-modern mobile-input"
                                            name="telephone" id="telephone" placeholder="0707070707" pattern="[0-9]+">
                                    </div>
                                    <div class="col-12 mt-2">
                                        <small class="text-muted"><i class="fas fa-info-circle"></i> Saisissez le numéro
                                            sans l'indicatif pays.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION VIREMENT BANCAIRE -->
                            <div id="section-banque" class="d-none animate-section">
                                <!-- Nom de la banque -->
                                <div class="mb-4">
                                    <label for="nom_banque" class="form-label">Nom de la banque <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-modern banque-input"
                                        id="nom_banque" name="nom_banque" placeholder="Ex: Ecobank, NSIA, etc.">
                                </div>

                                <!-- Numéro de compte / IBAN -->
                                <div class="mb-4">
                                    <label for="numero_compte" class="form-label">Numéro de compte / RIB <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-modern banque-input"
                                        id="numero_compte" name="numero_compte"
                                        placeholder="Entrez le numéro de compte complet">
                                </div>

                                <!-- Nom du titulaire -->
                                <div class="mb-4">
                                    <label for="nom_titulaire" class="form-label">Nom du titulaire du compte <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-modern banque-input"
                                        id="nom_titulaire" name="nom_titulaire" placeholder="Nom et Prénoms du titulaire">
                                </div>
                            </div>

                            <!-- Informations dynamiques -->
                            <div id="additional-info" class="info-additionnelle d-none">
                                <h6><i class="fas fa-info-circle me-1"></i> Informations importantes</h6>
                                <p id="info-text" class="mb-0 small"></p>
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex gap-3 justify-content-end mt-4">
                                <a href="{{ url()->previous() }}" class="btn btn-retour">
                                    <i class="fas fa-arrow-left me-1"></i> Retour
                                </a>
                                <button type="submit" class="btn btn-soumettre" id="btn-submit">
                                    <span class="btn-text"><i class="fas fa-paper-plane me-1"></i> Demander le
                                        retrait</span>
                                    <span class="spinner-loading d-none"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <!-- Carte d'informations -->
                <div class="card-modern mb-4">
                    <div class="card-header-modern">
                        <i class="fas fa-lightbulb me-2"></i>Informations importantes
                    </div>
                    <div class="card-body-modern">
                        @php
                            $infos = [
                                [
                                    'icon' => 'fa-clock',
                                    'title' => 'Délais de traitement',
                                    'text' => 'Les retraits sont traités sous 24 à 48 heures ouvrées.',
                                ],
                                [
                                    'icon' => 'fa-exclamation-circle',
                                    'title' => 'Vérification des informations',
                                    'text' =>
                                        'Assurez-vous que vos coordonnées sont correctes pour éviter tout retard.',
                                ],
                                [
                                    'icon' => 'fa-shield-alt',
                                    'title' => 'Sécurité',
                                    'text' => 'Toutes vos transactions sont cryptées et sécurisées.',
                                ],
                            ];

                            $methods = ['Wave', 'Orange Money', 'MTN Money', 'Virement Bancaire'];
                        @endphp

                        @foreach ($infos as $info)
                            <div class="d-flex align-items-start mb-3">
                                <div class="me-3 text-primary">
                                    <i class="fas {{ $info['icon'] }} fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $info['title'] }}</h6>
                                    <p class="small mb-0">{{ $info['text'] }}</p>
                                </div>
                            </div>
                        @endforeach

                        <hr>

                        <h6 class="mb-3">Méthodes disponibles</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($methods as $method)
                                <span class="method-badge">{{ $method }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Carte de contact -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <i class="fas fa-headset me-2"></i>Besoin d'aide?
                    </div>
                    <div class="card-body-modern">
                        <p class="small">
                            Si vous rencontrez des difficultés avec votre demande de retrait, contactez notre support.
                        </p>
                        <div class="d-grid">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="fas fa-phone me-1"></i>+225
                                {{ Auth::guard('paroisse')->user()->contact ?? '00000000' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script JS / jQuery / AJAX -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Configuration CSRF pour AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });

            // ==========================================
            // DEFINITION DES ROUTES ET DONNEES          
            // ==========================================
            const soldeDisponible = {{ $soldeDisponible ?? 0 }};

            // Les deux routes demandées
            const routeVirement = "{{ route('paroisse.retrait.request') }}";
            const routeMobileMoney = "{{ route('reversement.store') }}";

            const mobileMethods = ['wave', 'orange_money', 'mtn_money', 'moov_money'];
            const methodInfo = {
                'wave': 'Retrait Wave : Vérifiez que votre compte est actif (plafond max).',
                'orange_money': 'Retrait Orange Money : Assurez-vous d\'avoir activé votre compte.',
                'mtn_money': 'Retrait MTN Money : Compte vérifié requis.',
                'moov_money': 'Retrait Moov Money : Compte vérifié requis.',
                'virement_bancaire': 'Virement : Le nom du titulaire doit correspondre à votre compte paroissial.'
            };

            // ==========================================
            // GESTION DE L'AFFICHAGE DU FORMULAIRE
            // ==========================================
            $('#methode').on('change', function() {
                const selectedMethod = $(this).val();

                // 1. Réinitialisation visuelle
                $('#section-mobile-money, #section-banque, #additional-info').addClass('d-none');

                // On réinitialise l'attribut required
                $('.mobile-input, .banque-input').prop('required', false);

                // 2. Logique d'affichage
                if (mobileMethods.includes(selectedMethod)) {
                    $('#section-mobile-money').removeClass('d-none');
                    $('.mobile-input').prop('required', true);
                } else if (selectedMethod === 'virement_bancaire') {
                    $('#section-banque').removeClass('d-none');
                    $('.banque-input').prop('required', true);
                }

                // 3. Texte d'information
                if (selectedMethod && methodInfo[selectedMethod]) {
                    $('#info-text').text(methodInfo[selectedMethod]);
                    $('#additional-info').removeClass('d-none');
                }
            });

            // ==========================================
            // SOUMISSION DU FORMULAIRE
            // ==========================================
            $('#retraitForm').on('submit', function(e) {
                e.preventDefault();

                const $form = $(this);
                const $btn = $('#btn-submit');
                const $spinner = $btn.find('.spinner-loading');
                const $btnText = $btn.find('.btn-text');

                // Récupération des valeurs
                const montant = parseFloat($('#montant').val());
                const methode = $('#methode').val();

                // LOGIQUE DE ROUTAGE
                let targetUrl = '';
                if (mobileMethods.includes(methode)) {
                    targetUrl = routeMobileMoney;
                } else if (methode === 'virement_bancaire') {
                    targetUrl = routeVirement;
                } else {
                    Swal.fire('Erreur', 'Veuillez sélectionner une méthode de paiement.', 'warning');
                    return;
                }

                // Validation Frontend Basique
                if (montant < 1000 || montant > soldeDisponible) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Montant incorrect',
                        text: 'Le montant doit être compris entre 1 000 et ' + soldeDisponible
                            .toLocaleString() + ' FCFA.',
                        confirmButtonColor: '#c49d54'
                    });
                    return;
                }

                // Construction du résumé pour la confirmation
                let detailsHtml = `<div class="text-start fs-6 mt-3">
                    <p class="mb-1"><strong>Montant :</strong> <span class="text-primary">${montant.toLocaleString()} FCFA</span></p>
                    <p class="mb-1"><strong>Méthode :</strong> ${methode.replace('_', ' ').toUpperCase()}</p>`;

                if (mobileMethods.includes(methode)) {
                    detailsHtml +=
                        `<p class="mb-1"><strong>Numéro :</strong> (+${$('#prefix').val()}) ${$('#telephone').val()}</p>`;
                } else if (methode === 'virement_bancaire') {
                    detailsHtml += `<p class="mb-1"><strong>Banque :</strong> ${$('#nom_banque').val()}</p>
                                    <p class="mb-1"><strong>Compte :</strong> ${$('#numero_compte').val()}</p>`;
                }
                detailsHtml += `</div>`;

                // Confirmation SweetAlert
                Swal.fire({
                    title: 'Confirmer la demande',
                    html: detailsHtml,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#c49d54',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, envoyer',
                    cancelButtonText: 'Annuler',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Début du processus d'envoi
                        $btn.prop('disabled', true);
                        $btnText.addClass('d-none');
                        $spinner.removeClass('d-none');

                        // --- CORRECTION CRITIQUE ICI ---
                        // On désactive les champs de l'autre méthode pour ne pas les envoyer
                        // Cela évite les erreurs de validation côté serveur sur des champs vides
                        if (methode === 'virement_bancaire') {
                            $('#section-mobile-money :input').prop('disabled', true);
                        } else {
                            $('#section-banque :input').prop('disabled', true);
                        }

                        // Sérialisation des données
                        const formData = $form.serialize();

                        // On réactive les champs immédiatement après la sérialisation
                        // au cas où l'utilisateur annulerait ou voudrait corriger
                        $('#section-mobile-money :input, #section-banque :input').prop('disabled',
                            false);

                        $.ajax({
                            url: targetUrl,
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Succès !',
                                    text: response.message ||
                                        'Opération effectuée avec succès.',
                                    confirmButtonColor: '#c49d54'
                                }).then(() => {
                                    window.location.reload();
                                });
                                $form[0].reset();
                            },
                            error: function(xhr) {
                                let errorMessage =
                                    "Une erreur est survenue lors du traitement.";

                                if (xhr.responseJSON) {
                                    if (xhr.responseJSON.errors) {
                                        // Affiche les erreurs de validation Laravel
                                        let errorsHtml = '<ul class="text-start">';
                                        $.each(xhr.responseJSON.errors, function(key,
                                            val) {
                                            errorsHtml += '<li>' + val[0] +
                                                '</li>';
                                        });
                                        errorsHtml += '</ul>';
                                        errorMessage = errorsHtml;
                                    } else if (xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                    }
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    html: errorMessage,
                                    confirmButtonColor: '#c49d54'
                                });
                            },
                            complete: function() {
                                $btn.prop('disabled', false);
                                $spinner.addClass('d-none');
                                $btnText.removeClass('d-none');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
