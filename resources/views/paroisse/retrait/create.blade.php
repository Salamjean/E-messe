@extends('paroisse.layouts.template')

@section('content')
    @include('paroisse.retrait.partials._styles')

    <style>
        /* Styles spécifiques à la page de création */
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
            box-shadow: 0 0 0 0.25rem rgba(196, 157, 84, 0.25);
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
            background: linear-gradient(135deg, var(--primary) 0%, #cca45e 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-soumettre:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(196, 157, 84, 0.3);
            color: white;
        }

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
    </style>

    <div class="container-fluid mt-4" style="max-width: 1600px; margin: 0 auto; padding: 20px;">
        @include('paroisse.retrait.partials._header', [
            'title' => 'Demande de Retrait',
            'subtitle' => 'Gérez vos retraits de fonds, ' . Auth::guard('paroisse')->user()->name . '!',
            'icon' => 'money-bill-wave',
        ])

        <div class="row">
            <div class="col-lg-7">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <i class="fas fa-pen-to-square me-2"></i>Formulaire de Demande de Retrait
                    </div>
                    <div class="card-body-modern">
                        @include('paroisse.retrait.partials._stats')

                        <form id="retraitForm">
                            @csrf
                            <div class="mb-4">
                                <label for="montant" class="form-label">Montant à retirer (FCFA) <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-modern" id="montant" name="montant"
                                    required min="100" max="{{ $soldeDisponible ?? 0 }}" placeholder="Ex: 5000">
                                <div class="form-text text-muted mt-2">Le montant minimum de retrait est de 100 FCFA</div>
                            </div>

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

                            <div id="section-mobile-money" class="d-none">
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

                            <div id="section-banque" class="d-none animate__animated animate__fadeIn">
                                <div class="row g-3">
                                    <div class="col-12 mb-3">
                                        <label for="nom_banque" class="form-label">Détails Bancaires</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"
                                                style="border-radius: 10px 0 0 10px; border: 2px solid #eef2f6;">
                                                <i class="fas fa-university text-primary"></i>
                                            </span>
                                            <input type="text"
                                                class="form-control form-control-modern banque-input border-start-0"
                                                id="nom_banque" name="nom_banque"
                                                placeholder="Nom de la banque (ex: Ecobank, NSIA)"
                                                style="border-radius: 0 10px 10px 0;">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="numero_compte" class="form-label small">Numéro de compte / RIB <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"
                                                style="border-radius: 10px 0 0 10px; border: 2px solid #eef2f6;">
                                                <i class="fas fa-credit-card text-primary"></i>
                                            </span>
                                            <input type="text"
                                                class="form-control form-control-modern banque-input border-start-0"
                                                id="numero_compte" name="numero_compte" placeholder="RIB / IBAN"
                                                style="border-radius: 0 10px 10px 0;">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nom_titulaire" class="form-label small">Titulaire du compte <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"
                                                style="border-radius: 10px 0 0 10px; border: 2px solid #eef2f6;">
                                                <i class="fas fa-user-circle text-primary"></i>
                                            </span>
                                            <input type="text"
                                                class="form-control form-control-modern banque-input border-start-0"
                                                id="nom_titulaire" name="nom_titulaire" placeholder="Nom complet"
                                                style="border-radius: 0 10px 10px 0;">
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info py-2 px-3 mt-2"
                                    style="border-radius: 10px; border: none; background: rgba(196, 157, 84, 0.1);">
                                    <small><i class="fas fa-info-circle me-1 text-primary"></i> Le virement bancaire peut
                                        prendre jusqu'à 72h ouvrées selon votre établissement.</small>
                                </div>
                            </div>

                            <div id="additional-info" class="info-additionnelle d-none">
                                <h6><i class="fas fa-info-circle me-1"></i> Informations importantes</h6>
                                <p id="info-text" class="mb-0 small"></p>
                            </div>

                            <div class="d-flex gap-3 justify-content-end mt-4">
                                <a href="{{ route('paroisse.retraits') }}" class="btn btn-retour">
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
                                    'title' => 'Vérification',
                                    'text' =>
                                        'Assurez-vous que vos coordonnées sont correctes pour éviter tout retard.',
                                ],
                                [
                                    'icon' => 'fa-shield-alt',
                                    'title' => 'Sécurité',
                                    'text' => 'Toutes vos transactions sont cryptées et sécurisées.',
                                ],
                            ];
                        @endphp

                        @foreach ($infos as $info)
                            <div class="d-flex align-items-start mb-3">
                                <div class="me-3 text-primary"><i class="fas {{ $info['icon'] }} fa-lg"></i></div>
                                <div>
                                    <h6 class="mb-1">{{ $info['title'] }}</h6>
                                    <p class="small mb-0">{{ $info['text'] }}</p>
                                </div>
                            </div>
                        @endforeach
                        <hr>
                        <h6 class="mb-3">Méthodes disponibles</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach (['Wave', 'Orange Money', 'MTN Money', 'Virement Bancaire'] as $method)
                                <span class="method-badge">{{ $method }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card-modern">
                    <div class="card-header-modern">
                        <i class="fas fa-headset me-2"></i>Besoin d'aide?
                    </div>
                    <div class="card-body-modern">
                        <p class="small">Si vous rencontrez des difficultés avec votre demande de retrait, contactez notre
                            support.</p>
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

    @push('js')
        @include('paroisse.retrait.partials._scripts')

        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    }
                });

                const soldeDisponible = {{ $soldeDisponible ?? 0 }};
                const routeVirement = "{{ route('paroisse.retrait.request') }}";
                const routeMobileMoney = "{{ route('reversement.store') }}";
                const mobileMethods = ['wave', 'orange_money', 'mtn_money', 'moov_money'];
                const methodInfo = {
                    'wave': 'Retrait Wave : Vérifiez que votre compte est actif.',
                    'orange_money': 'Retrait Orange Money : Assurez-vous d\'avoir activé votre compte.',
                    'mtn_money': 'Retrait MTN Money : Compte vérifié requis.',
                    'moov_money': 'Retrait Moov Money : Compte vérifié requis.',
                    'virement_bancaire': 'Virement : Le nom du titulaire doit correspondre à votre compte paroissial.'
                };

                $('#methode').on('change', function() {
                    const selectedMethod = $(this).val();
                    $('#section-mobile-money, #section-banque, #additional-info').addClass('d-none');
                    $('.mobile-input, .banque-input').prop('required', false);

                    if (mobileMethods.includes(selectedMethod)) {
                        $('#section-mobile-money').removeClass('d-none');
                        $('.mobile-input').prop('required', true);
                    } else if (selectedMethod === 'virement_bancaire') {
                        $('#section-banque').removeClass('d-none');
                        $('.banque-input').prop('required', true);
                    }

                    if (selectedMethod && methodInfo[selectedMethod]) {
                        $('#info-text').text(methodInfo[selectedMethod]);
                        $('#additional-info').removeClass('d-none');
                    }
                });

                $('#retraitForm').on('submit', function(e) {
                    e.preventDefault();
                    const $form = $(this);
                    const $btn = $('#btn-submit');
                    const $spinner = $btn.find('.spinner-loading');
                    const $btnText = $btn.find('.btn-text');
                    const montant = parseFloat($('#montant').val());
                    const methode = $('#methode').val();

                    let targetUrl = mobileMethods.includes(methode) ? routeMobileMoney : (methode ===
                        'virement_bancaire' ? routeVirement : '');
                    if (!targetUrl) return;

                    if (montant < 1000 || montant > soldeDisponible) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Montant incorrect',
                            text: 'Intervalle: 1 000 - ' + soldeDisponible.toLocaleString() + ' FCFA.'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Confirmer la demande',
                        text: "Souhaitez-vous envoyer cette demande de retrait ?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#c49d54',
                        confirmButtonText: 'Oui, envoyer'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $btn.prop('disabled', true);
                            $btnText.addClass('d-none');
                            $spinner.removeClass('d-none');

                            if (methode === 'virement_bancaire') {
                                $('#section-mobile-money :input').prop('disabled', true);
                            } else {
                                $('#section-banque :input').prop('disabled', true);
                            }

                            const formData = $form.serialize();
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
                                        text: response.message
                                    }).then(() => {
                                        if (mobileMethods.includes(methode)) {
                                            window.location.href =
                                                "{{ route('paroisse.history') }}";
                                        } else {
                                            window.location.href =
                                                "{{ route('paroisse.retraits') }}";
                                        }
                                    });
                                },
                                error: function(xhr) {
                                    let msg = "Erreur lors du traitement.";
                                    if (xhr.responseJSON && xhr.responseJSON.message) msg =
                                        xhr.responseJSON.message;
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur',
                                        text: msg
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
    @endpush
@endsection
