<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Configuration globale de SweetAlert2
    const swalConfig = {
        confirmButtonColor: '#f35525',
        cancelButtonColor: '#6c757d',
    };

    // Fonction pour formater la date
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Fonction pour formater la monnaie
    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR').format(amount);
    }

    // Fonction pour l'icône de statut
    function getStatusIcon(status) {
        const icons = {
            'en_attente': 'clock',
            'traite': 'cog',
            'complete': 'check-circle',
            'rejete': 'times-circle'
        };
        return icons[status] || 'info-circle';
    }

    // Fonction pour le libellé de statut
    function getStatusLabel(status) {
        const labels = {
            'en_attente': 'En attente',
            'traite': 'Traité',
            'complete': 'Complété',
            'rejete': 'Rejeté'
        };
        return labels[status] || status;
    }

    // Fonction pour la classe CSS de statut
    function getStatusClass(status) {
        const classes = {
            'en_attente': 'badge-en-attente',
            'traite': 'badge-traite',
            'complete': 'badge-complete',
            'rejete': 'badge-rejete'
        };
        return classes[status] || '';
    }

    // Fonction pour formater la méthode de retrait
    function formatMethod(method) {
        const methods = {
            'virement_bancaire': 'Virement Bancaire',
            'orange_money': 'Orange Money',
            'mtn_money': 'MTN Money',
            'wave': 'Wave'
        };
        return methods[method] || method.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    }

    // Fonction pour afficher les détails d'un retrait
    function showRetraitDetails(retrait) {
        let detailsHtml = `
            <div class="mt-3">
                <div class="detail-row">
                    <div class="detail-label">Référence:</div>
                    <div class="detail-value">#${retrait.reference}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Paroisse:</div>
                    <div class="detail-value">${retrait.paroisse ? retrait.paroisse.name : 'Paroisse supprimée'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Montant:</div>
                    <div class="detail-value"><strong>${formatCurrency(retrait.montant)} FCFA</strong></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Méthode:</div>
                    <div class="detail-value">${formatMethod(retrait.methode)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Statut:</div>
                    <div class="detail-value">
                        <span class="badge-statut ${getStatusClass(retrait.statut)}">
                            <i class="fas fa-${getStatusIcon(retrait.statut)} me-1"></i>${getStatusLabel(retrait.statut)}
                        </span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Titulaire:</div>
                    <div class="detail-value">${retrait.nom_titulaire || 'N/A'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">N° Compte/Tél:</div>
                    <div class="detail-value">${retrait.numero_compte || 'N/A'}</div>
                </div>
                ${retrait.nom_banque ? `
                <div class="detail-row">
                    <div class="detail-label">Banque:</div>
                    <div class="detail-value">${retrait.nom_banque}</div>
                </div>` : ''}
                <div class="detail-row">
                    <div class="detail-label">Date Demande:</div>
                    <div class="detail-value">${formatDate(retrait.created_at)}</div>
                </div>
                ${retrait.traite_le ? `
                <div class="detail-row">
                    <div class="detail-label">Traité le:</div>
                    <div class="detail-value">${formatDate(retrait.traite_le)}</div>
                </div>` : ''}
                ${retrait.raison_rejet ? `
                <div class="detail-row text-danger">
                    <div class="detail-label">Raison rejet:</div>
                    <div class="detail-value">${retrait.raison_rejet}</div>
                </div>` : ''}
                ${retrait.preuve_virement ? `
                <div class="mt-3">
                    <p class="detail-label mb-2">Preuve de virement:</p>
                    <a href="/storage/${retrait.preuve_virement}" target="_blank">
                        <img src="/storage/${retrait.preuve_virement}" class="img-fluid rounded border" style="max-height: 200px;">
                    </a>
                </div>` : ''}
            </div>
        `;

        Swal.fire({
            title: `Détails Retrait`,
            html: detailsHtml,
            icon: 'info',
            width: '550px',
            confirmButtonText: 'Fermer',
            confirmButtonColor: '#f35525'
        });
    }

    // Fonction pour confirmer un retrait
    function confirmRetrait(retraitId, methode) {
        if (methode === 'virement_bancaire') {
            Swal.fire({
                title: 'Confirmer le virement',
                html: `
                    <div class="text-start">
                        <p class="mb-3">Veuillez uploader la preuve du virement bancaire pour finaliser la demande.</p>
                        <div class="preuve-upload" id="preuveUpload">
                            <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: #f35525"></i>
                            <h5 class="mb-1">Cliquez pour choisir un fichier</h5>
                            <p class="text-muted small">Format accepté: JPG, PNG, PDF</p>
                            <input type="file" id="preuveFile" accept="image/*,application/pdf" style="display: none;">
                            <img id="previewImage" class="preview-image" src="" alt="Aperçu">
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Confirmer le virement',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#28a745',
                preConfirm: () => {
                    const fileInput = document.getElementById('preuveFile');
                    if (!fileInput.files || fileInput.files.length === 0) {
                        Swal.showValidationMessage('Veuillez uploader une preuve');
                        return false;
                    }
                    return true;
                },
                didOpen: () => {
                    const uploadArea = document.getElementById('preuveUpload');
                    const fileInput = document.getElementById('preuveFile');
                    const preview = document.getElementById('previewImage');

                    uploadArea.onclick = () => fileInput.click();
                    fileInput.onchange = (e) => {
                        const file = e.target.files[0];
                        if (file && file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                preview.src = e.target.result;
                                preview.style.display = 'block';
                            };
                            reader.readAsDataURL(file);
                        }
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('preuve_virement', document.getElementById('preuveFile').files[0]);
                    formData.append('_token', '{{ csrf_token() }}');

                    submitAction(`/admin/withdrawal/${retraitId}/confirmer`, formData, true);
                }
            });
        } else {
            Swal.fire({
                title: 'Confirmer le retrait',
                text: `Voulez-vous confirmer ce retrait via ${formatMethod(methode)} ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, confirmer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#28a745'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitAction(`/admin/withdrawal/${retraitId}/confirmer`, {
                        _token: '{{ csrf_token() }}'
                    });
                }
            });
        }
    }

    // Fonction pour rejeter un retrait
    function rejectRetrait(retraitId) {
        Swal.fire({
            title: 'Rejeter la demande',
            input: 'textarea',
            inputLabel: 'Raison du rejet',
            inputPlaceholder: 'Entrez la raison du rejet...',
            inputAttributes: {
                'aria-label': 'Entrez la raison du rejet'
            },
            showCancelButton: true,
            confirmButtonText: 'Rejeter la demande',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#dc3545',
            inputValidator: (value) => {
                if (!value) return 'La raison est obligatoire !';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitAction(`/admin/withdrawal/${retraitId}/rejeter`, {
                    _token: '{{ csrf_token() }}',
                    raison: result.value
                });
            }
        });
    }

    // Fonction générique pour soumettre les actions
    function submitAction(url, data, isMultipart = false) {
        let options = {
            method: 'POST',
            body: isMultipart ? data : JSON.stringify(data),
            headers: isMultipart ? {} : {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        };

        Swal.fire({
            title: 'Traitement en cours...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(url, options)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Réussi !', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Erreur', data.message || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Erreur', 'Erreur de communication avec le serveur', 'error');
            });
    }

    // Messages de session
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès !',
            text: '{{ session('success') }}',
            confirmButtonColor: '#f35525'
        });
    @endif
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: '{{ session('error') }}',
            confirmButtonColor: '#f35525'
        });
    @endif
</script>
