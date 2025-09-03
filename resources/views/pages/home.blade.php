@extends('pages.layouts.template')
@section('content')

<!-- Features Section -->
<section class="features-section section-padding">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="section-title">Pourquoi choisir notre service?</h2>
                <p class="section-subtitle">Une expérience simplifiée pour vos intentions de messe</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 feature-item">
                <div class="feature-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Rapide et Simple</h3>
                <p>Effectuez votre demande en quelques minutes, sans déplacement.</p>
            </div>
            <div class="col-md-4 feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Sécurisé</h3>
                <p>Paiement sécurisé et confidentialité de vos intentions respectée.</p>
            </div>
            <div class="col-md-4 feature-item">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Suivi en Temps Réel</h3>
                <p>Suivez l'état de votre demande et recevez des confirmations.</p>
            </div>
        </div>
    </div>
</section>

<!-- How it Works Section -->
<section id="how-it-works" class="how-it-works section-padding">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="section-title">Comment faire une demande?</h2>
                <p class="section-subtitle">Quatre étapes simples pour votre intention de messe</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 step-item">
                <div class="step-number">1</div>
                <h4>Choisissez l'intention</h4>
                <p>Sélectionnez le type de messe que vous souhaitez demander.</p>
            </div>
            <div class="col-lg-3 col-md-6 step-item">
                <div class="step-number">2</div>
                <h4>Renseignez les détails</h4>
                <p>Précisez la date, l'heure et les noms des défunts ou intentions.</p>
            </div>
            <div class="col-lg-3 col-md-6 step-item">
                <div class="step-number">3</div>
                <h4>Effectuez le paiement</h4>
                <p>Procédez au règlement sécurisé de votre offrande.</p>
            </div>
            <div class="col-lg-3 col-md-6 step-item">
                <div class="step-number">4</div>
                <h4>Confirmation</h4>
                <p>Recevez une confirmation et suivez votre demande.</p>
            </div>
        </div>
    </div>
</section>

<!-- Types of Masses -->
<section class="masses-types section-padding">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="section-title">Types de messes disponibles</h2>
                <p class="section-subtitle">Différentes intentions pour répondre à vos besoins spirituels</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card mass-card">
                    <div class="card-body text-center">
                        <div class="mass-icon">
                            <i class="fas fa-cross"></i>
                        </div>
                        <h4>Messe pour les défunts</h4>
                        <p>Pour le repos de l'âme de vos proches disparus</p>
                        <a href="#" class="btn btn-outline">En savoir plus</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card mass-card">
                    <div class="card-body text-center">
                        <div class="mass-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4>Messe d'action de grâce</h4>
                        <p>Pour remercier Dieu pour ses bienfaits</p>
                        <a href="#" class="btn btn-outline">En savoir plus</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card mass-card">
                    <div class="card-body text-center">
                        <div class="mass-icon">
                            <i class="fas fa-pray"></i>
                        </div>
                        <h4>Demande de messe</h4>
                        <p>Pour présenter une intention particulière à Dieu</p>
                        <a href="#" class="btn btn-outline">En savoir plus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="statistics-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6 text-center">
                <div class="stat-item">
                    <h3 class="stat-number" data-count="1250">0</h3>
                    <p>Messes célébrées</p>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="stat-item">
                    <h3 class="stat-number" data-count="350">0</h3>
                    <p>Familles satisfaites</p>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="stat-item">
                    <h3 class="stat-number" data-count="24">0</h3>
                    <p>Heures de traitement</p>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="stat-item">
                    <h3 class="stat-number" data-count="98">0</h3>
                    <p>% de satisfaction</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials section-padding">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="section-title">Témoignages</h2>
                <p class="section-subtitle">Ce que nos fidèles disent de notre service</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        <p>"Service très simple d'utilisation. J'ai pu faire célébrer une messe pour mon père depuis l'étranger sans difficulté."</p>
                    </div>
                    <div class="testimonial-author">
                        <h5>Kadio L.</h5>
                        <span>Abidjan</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        <p>"J'apprécie particulièrement le suivi et la confirmation par email. Cela me rassure que ma demande a bien été prise en compte."</p>
                    </div>
                    <div class="testimonial-author">
                        <h5>Salam O.</h5>
                        <span>Aboisso</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        <p>"En tant que personne à mobilité réduite, ce service est une bénédiction. Je peux maintenant faire dire des messes facilement."</p>
                    </div>
                    <div class="testimonial-author">
                        <h5>David D.</h5>
                        <span>Korogho</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2>Prêt à faire votre demande de messe?</h2>
                <p>Rejoignez les milliers de fidèles qui utilisent notre plateforme chaque mois.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="#" class="btn btn-light">Commencer maintenant</a>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section section-padding">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="section-title">Questions fréquentes</h2>
                <p class="section-subtitle">Trouvez des réponses aux questions les plus courantes</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Combien de temps à l'avance faut-il réserver?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Nous recommandons de faire votre demande au moins 7 jours à l'avance pour garantir la disponibilité à la date souhaitée. Cependant, selon les créneaux disponibles, il est parfois possible d'obtenir une messe plus rapidement.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Quels modes de paiement acceptez-vous?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Nous acceptons les cartes bancaires (Visa, Mastercard), les virements ainsi que les portefeuilles électroniques. Tous les paiements sont sécurisés et cryptés.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Recevrai-je une confirmation?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Oui, immédiatement après votre demande, vous recevrez un email de confirmation avec un numéro de suivi. Vous recevrez également un rappel avant la date de la messe.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Global Styles */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #181824;
        background-color: #fff;
    }
    
    /* Hero Section */
    .hero-section {
        background-color: #e04a1b;
        color: white;
        padding: 100px 0;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='50' cy='50' r='1' fill='%23f35525' fill-opacity='0.1'/%3E%3C/svg%3E");
        background-size: 100px 100px;
    }
    
    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.9;
        font-weight: 300;
    }
    
    .hero-buttons .btn {
        margin-right: 15px;
        margin-bottom: 15px;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background-color: #f35525;
        border-color: #f35525;
    }
    
    .btn-primary:hover {
        background-color: #e04a1b;
        border-color: #e04a1b;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(243, 85, 37, 0.3);
    }
    
    .btn-outline {
        background-color: transparent;
        border: 2px solid #f35525;
        color: #f35525;
    }
    
    .btn-outline:hover {
        background-color: #f35525;
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-light {
        background-color: white;
        color: #181824;
        border: none;
    }
    
    .btn-light:hover {
        background-color: #f35525;
        color: white;
    }
    
    .church-illustration {
        position: relative;
        height: 300px;
        display: flex;
        justify-content: center;
        align-items: flex-end;
    }
    
    .church-building {
        position: relative;
        z-index: 2;
    }
    
    .church-tower {
        width: 60px;
        height: 120px;
        background: white;
        margin: 0 auto;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        position: relative;
    }
    
    .church-tower::after {
        content: '';
        position: absolute;
        top: -20px;
        left: -10px;
        width: 80px;
        height: 20px;
        background: #f35525;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }
    
    .church-body {
        width: 180px;
        height: 150px;
        background: white;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        margin-top: -10px;
        position: relative;
    }
    
    .church-door {
        width: 40px;
        height: 70px;
        background: #f35525;
        position: absolute;
        bottom: 0;
        left: 70px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }
    
    .church-windows {
        display: flex;
        justify-content: space-around;
        padding-top: 30px;
    }
    
    .window {
        width: 30px;
        height: 30px;
        background: #f35525;
        opacity: 0.7;
        border-radius: 50%;
    }
    
    .sun {
        position: absolute;
        top: 50px;
        right: 50px;
        width: 60px;
        height: 60px;
        background: #f35525;
        border-radius: 50%;
        box-shadow: 0 0 30px #f35525;
    }
    
    /* Section Padding */
    .section-padding {
        padding: 80px 0;
    }
    
    /* Section Titles */
    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #181824;
        position: relative;
    }
    
    .section-title::after {
        content: '';
        display: block;
        width: 60px;
        height: 4px;
        background: #f35525;
        margin: 15px auto 0;
        border-radius: 2px;
    }
    
    .section-subtitle {
        font-size: 1.1rem;
        color: #718096;
        margin-bottom: 3rem;
    }
    
    /* Features */
    .features-section {
        background: #f8f9fa;
    }
    
    .feature-item {
        text-align: center;
        padding: 30px 20px;
        transition: all 0.3s ease;
    }
    
    .feature-item:hover {
        transform: translateY(-10px);
    }
    
    .feature-icon {
        font-size: 3rem;
        color: #f35525;
        margin-bottom: 1.5rem;
    }
    
    .feature-item h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: #181824;
    }
    
    .feature-item p {
        color: #718096;
    }
    
    /* How it Works */
    .how-it-works {
        background: #181824;
        color: white;
    }
    
    .how-it-works .section-title,
    .how-it-works .section-subtitle {
        color: white;
    }
    
    .step-item {
        text-align: center;
        padding: 20px;
    }
    
    .step-number {
        width: 60px;
        height: 60px;
        line-height: 60px;
        border-radius: 50%;
        background: #f35525;
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 auto 1.5rem;
    }
    
    .step-item h4 {
        margin-bottom: 1rem;
        color: white;
    }
    
    .step-item p {
        color: #a0aec0;
    }
    
    /* Mass Cards */
    .mass-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        background: white;
    }
    
    .mass-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(243, 85, 37, 0.15);
    }
    
    .mass-icon {
        font-size: 2.5rem;
        color: #f35525;
        margin-bottom: 1.5rem;
    }
    
    /* Statistics */
    .statistics-section {
        background: #f8f9fa;
    }
    
    .stat-item {
        padding: 20px;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #f35525;
        margin-bottom: 0.5rem;
    }
    
    /* Testimonials */
    .testimonial-card {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        height: 100%;
        position: relative;
    }
    
    .testimonial-card::before {
        content: '"';
        font-size: 5rem;
        color: #f35525;
        opacity: 0.1;
        position: absolute;
        top: 10px;
        left: 20px;
        line-height: 1;
    }
    
    .testimonial-text {
        margin-bottom: 1.5rem;
        color: #4a5568;
        font-style: italic;
        position: relative;
        z-index: 1;
    }
    
    .testimonial-author h5 {
        margin-bottom: 0.25rem;
        color: #181824;
    }
    
    .testimonial-author span {
        color: #718096;
        font-size: 0.9rem;
    }
    
    /* CTA Section */
    .cta-section {
        background: #f35525;
        color: white;
    }
    
    .cta-section h2 {
        margin-bottom: 1rem;
    }
    
    /* FAQ */
    .faq-section {
        background: #f8f9fa;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: #f35525;
        color: white;
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.2rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .hero-section, .section-padding {
            padding: 60px 0;
        }
        
        .church-illustration {
            height: 200px;
            margin-top: 40px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation for elements when they come into view
        const animatedElements = document.querySelectorAll('.feature-item, .step-item, .mass-card, .testimonial-card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        animatedElements.forEach(element => {
            element.style.opacity = 0;
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(element);
        });
        
        // Counter animation for statistics
        const counters = document.querySelectorAll('.stat-number');
        const speed = 200;
        
        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-count');
                const count = +counter.innerText;
                
                const inc = target / speed;
                
                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target;
                }
            };
            
            const startCounter = (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateCount();
                        observer.unobserve(entry.target);
                    }
                });
            };
            
            const counterObserver = new IntersectionObserver(startCounter, { threshold: 0.5 });
            counterObserver.observe(counter);
        });
    });
</script>

@endsection