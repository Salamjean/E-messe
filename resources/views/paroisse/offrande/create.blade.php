@extends('paroisse.layouts.template')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen bg-gray-50 py-8" style="background-color: #f8fafc;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- En-tête -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold" style="color: #181824;">Gestion des Offrandes</h1>
                <p class="mt-2 text-lg" style="color: #6b7280;">Ajoutez le montant des offrandes collectées</p>
            </div>

            <!-- Affichage du montant actuel -->
            <div class="rounded-xl shadow-lg p-6 mb-8 text-white" style="background: linear-gradient(135deg, #181824 0%, #2d2b42 100%);">
                <h2 class="text-lg font-semibold mb-2">Montant actuel des offrandes</h2>
                <p class="text-3xl font-bold" id="currentAmount">{{ number_format($paroisse->montant_offrande ?? 0, 0, ',', ' ') }} Fcfa</p>
            </div>

            <!-- Carte du formulaire -->
            <div class="rounded-2xl overflow-hidden shadow-xl" style="background-color: #ffffff;">
                <div class="px-6 py-8 sm:p-10">
                    <form id="offrandeForm" method="POST" action="{{ route('paroisse.offrande.store') }}">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Champ Montant -->
                            <div>
                                <label for="montant" class="block text-sm font-medium" style="color: #181824;">Montant de l'offrande (Fcfa)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="sm:text-sm" style="color: #6b7280;">Fcfa</span>
                                    </div>
                                    <input type="number" step="0.01" name="montant" id="montant" 
                                           class="py-3 block w-full pl-16 pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-2 focus:ring-offset-2 transition-colors" 
                                           placeholder="0,00" required
                                           style="border-color: #e5e7eb; color: #181824; focus:border-color: #f35525; focus:ring-color: #f35525;">
                                </div>
                            </div>

                            <!-- Champ Date -->
                            <div>
                                <label for="date" class="block text-sm font-medium" style="color: #181824;">Date de l'offrande</label>
                                <div class="mt-1">
                                    <input type="date" name="date" id="date" 
                                           class="py-3 block w-full sm:text-sm border-gray-300 rounded-md focus:ring-2 focus:ring-offset-2 transition-colors" 
                                           value="{{ date('Y-m-d') }}" required readonly
                                           style="border-color: #e5e7eb; color: #181824; focus:border-color: #f35525; focus:ring-color: #f35525;">
                                </div>
                            </div>

                            <!-- Bouton de soumission -->
                            <div>
                                <button type="submit" 
                                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2"
                                        style="background-color: #f35525; hover:background-color: #e04e22; focus:ring-color: #f35525;">
                                    Ajouter l'offrande
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inclure SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('offrandeForm');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Récupérer les données du formulaire
            const formData = new FormData(form);
            
            // Afficher l'indicateur de chargement
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Traitement...';
            submitButton.disabled = true;
            
            // Envoyer la requête AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour le montant affiché
                    document.getElementById('currentAmount').textContent = 
                        parseFloat(data.new_amount).toLocaleString('fr-FR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }) + ' Fcfa';
                    
                    // Animation de mise à jour du montant
                    const amountElement = document.getElementById('currentAmount');
                    amountElement.classList.add('animate-pulse');
                    setTimeout(() => {
                        amountElement.classList.remove('animate-pulse');
                    }, 1000);
                    
                    // Afficher le message de succès avec SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès!',
                        text: data.message,
                        confirmButtonColor: '#f35525',
                        confirmButtonText: 'OK',
                        background: '#ffffff',
                        color: '#181824'
                    });
                    
                    // Réinitialiser le formulaire
                    form.reset();
                    document.getElementById('date').value = '{{ date('Y-m-d') }}';
                } else {
                    // Afficher une erreur
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message || 'Une erreur est survenue',
                        confirmButtonColor: '#f35525',
                        confirmButtonText: 'OK',
                        background: '#ffffff',
                        color: '#181824'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors de l\'envoi',
                    confirmButtonColor: '#f35525',
                    confirmButtonText: 'OK',
                    background: '#ffffff',
                    color: '#181824'
                });
            })
            .finally(() => {
                // Restaurer le bouton
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
        });

        // Ajouter des styles pour les éléments de formulaire
        const style = document.createElement('style');
        style.textContent = `
            input:focus, textarea:focus {
                border-color: #f35525 !important;
                box-shadow: 0 0 0 3px rgba(243, 85, 37, 0.1) !important;
            }
            .animate-pulse {
                animation: pulse 1s;
            }
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endsection