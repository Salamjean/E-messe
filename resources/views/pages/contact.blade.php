@extends('pages.layouts.template')
@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-8 mx-auto">
                    <h1 class="hero-title">Nous <span class="text-gold">Contacter</span></h1>
                    <p class="hero-subtitle">Une question ? Nous sommes là pour vous aider</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Cards Section -->
    <section class="contact-cards-section section-padding">
        <div class="container">
            <div class="row">
                @forelse($contactInfos as $info)
                    <div class="col-md-4 mb-4">
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="{{ $info->icon }}"></i>
                            </div>
                            <h4>{{ $info->title }}</h4>
                            <p class="contact-value">{{ $info->value }}</p>
                            @if ($info->subtitle)
                                <p class="contact-subtitle">{{ $info->subtitle }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Informations de contact non disponibles.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="form-wrapper">
                        <h2 class="form-title text-center mb-4">Envoyez-nous un Message</h2>
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="name" placeholder="Votre nom"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="votre@email.com"
                                        required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Sujet</label>
                                <input type="text" class="form-control" id="subject"
                                    placeholder="Sujet de votre message" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="5" placeholder="Votre message" required></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-submit">
                                    <i class="far fa-paper-plane me-2"></i> Envoyer le message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Support Hours Section -->
    <section class="support-hours-section section-padding bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="support-hours-card">
                        <h3 class="mb-4"><i class="far fa-clock me-2 support-icon"></i>Horaires de Support</h3>
                        <div class="row">
                            @forelse($supportHours as $hour)
                                <div class="col-md-6">
                                    <div class="hours-item">
                                        <h5>{{ $hour->title }}</h5>
                                        <p class="mb-1">{{ $hour->schedule }}</p>
                                        @if ($hour->note)
                                            <p class="text-muted">{{ $hour->note }}</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center">
                                    <p class="text-muted">Aucune information sur les horaires de support.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Questions Fréquentes</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        @forelse($faqs as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-controls="collapse{{ $index }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center">
                                <p class="text-muted">Aucune FAQ disponible pour le moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            background-color: #fff;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #f5f0e8 0%, #e8dfc8 100%);
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c9a85c' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.4;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }

        .text-gold {
            color: #C9A961;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: #666;
            font-weight: 400;
        }

        /* Section Padding */
        .section-padding {
            padding: 60px 0;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        /* Contact Cards */
        .contact-card {
            background: white;
            border-radius: 15px;
            border: 2px solid #e8e8e8;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(201, 169, 97, 0.15);
        }

        .contact-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #C9A961 0%, #B89551 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .contact-card h4 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .contact-value {
            font-size: 1.1rem;
            color: #C9A961;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .contact-subtitle {
            font-size: 0.9rem;
            color: #999;
        }

        /* Contact Form */
        .form-wrapper {
            background: white;
            border-radius: 15px;
            padding: 50px 40px;
            border: 2px solid #e8e8e8;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .form-title {
            font-size: 2rem;
            color: #333;
            font-weight: 700;
        }

        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e8e8e8;
            border-radius: 8px;
            padding: 12px 18px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #C9A961;
            box-shadow: 0 0 0 0.2rem rgba(201, 169, 97, 0.15);
        }

        .btn-submit {
            background: linear-gradient(135deg, #C9A961 0%, #B89551 100%);
            color: white;
            border: none;
            padding: 14px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(201, 169, 97, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(201, 169, 97, 0.4);
            background: linear-gradient(135deg, #B89551 0%, #A88441 100%);
        }

        /* Support Hours */
        .support-hours-card {
            background: white;
            border-radius: 15px;
            padding: 50px 40px;
            border: 2px solid #e8e8e8;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            /* text-align: center; */
        }

        .support-icon {
            /* width: 40px;
                                    height: 40px;
                                    margin: 0 auto 25px; */
            background: linear-gradient(135deg, #C9A961 0%, #B89551 100%);
            border-radius: 50%;
            /* display: flex; */
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }

        .support-hours-card h3 {
            font-size: 1.8rem;
            color: #333;
            font-weight: 700;
        }

        .hours-item {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .hours-item h5 {
            color: #C9A961;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .hours-item p {
            margin: 0;
            font-size: 0.95rem;
            color: #555;
        }

        /* Section Title */
        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }

        /* FAQ */
        .accordion-item {
            border: 1px solid #e8e8e8;
            margin-bottom: 15px;
            border-radius: 10px !important;
            overflow: hidden;
        }

        .accordion-button {
            font-weight: 600;
            color: #333;
            background-color: white;
            padding: 18px 25px;
            font-size: 1.05rem;
        }

        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #C9A961 0%, #B89551 100%);
            color: white;
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: #e8e8e8;
        }

        .accordion-button::after {
            filter: brightness(0) saturate(100%);
        }

        .accordion-button:not(.collapsed)::after {
            filter: brightness(0) saturate(100%) invert(100%);
        }

        .accordion-body {
            padding: 25px;
            color: #555;
            line-height: 1.7;
            background-color: #fafafa;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .form-wrapper,
            .support-hours-card {
                padding: 30px 25px;
            }

            .section-padding {
                padding: 40px 0;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form submission
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();

                // Show loading state
                const submitBtn = $(this).find('button[type="submit"]');
                const originalBtnText = submitBtn.html();
                submitBtn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-2"></i> Envoi en cours...');

                // Get form values
                const formData = {
                    _token: '{{ csrf_token() }}',
                    name: $('#name').val(),
                    email: $('#email').val(),
                    subject: $('#subject').val(),
                    message: $('#message').val()
                };

                // Send data to backend
                $.ajax({
                    url: "{{ route('contact.send') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès!',
                            text: response.message,
                            confirmButtonColor: '#C9A961'
                        });

                        // Reset form
                        $('#contactForm')[0].reset();
                    },
                    error: function(xhr) {
                        // Handle errors
                        let errorMessage = 'Une erreur est survenue. Veuillez réessayer.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                '\n');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: errorMessage,
                            confirmButtonColor: '#C9A961'
                        });
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.prop('disabled', false).html(originalBtnText);
                    }
                });
            });

            // Smooth scroll animation for contact cards
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe contact cards and form
            document.querySelectorAll('.contact-card, .form-wrapper, .support-hours-card').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });
        });
    </script>
@endsection
