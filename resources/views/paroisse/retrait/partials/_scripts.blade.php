<script>
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function showRetraitDetails(retrait) {
        let detailsHtml = `
            <div class="detail-row">
                <div class="detail-label">Date de demande:</div>
                <div class="detail-value">${formatDate(retrait.created_at)}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Statut:</div>
                <div class="detail-value">${getStatusBadge(retrait.statut)}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Montant:</div>
                <div class="detail-value">${formatCurrency(retrait.montant)} FCFA</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Méthode:</div>
                <div class="detail-value">${formatMethod(retrait.methode)}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Numéro de compte/téléphone:</div>
                <div class="detail-value">${retrait.numero_compte}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Nom du titulaire:</div>
                <div class="detail-value">${retrait.nom_titulaire}</div>
            </div>
        `;

        if (retrait.nom_banque) {
            detailsHtml += `
                <div class="detail-row">
                    <div class="detail-label">Nom de la banque:</div>
                    <div class="detail-value">${retrait.nom_banque}</div>
                </div>
            `;
        }

        if (retrait.traite_le) {
            detailsHtml += `
                <div class="detail-row">
                    <div class="detail-label">Traîté le:</div>
                    <div class="detail-value">${formatDate(retrait.traite_le)}</div>
                </div>
            `;
        }

        Swal.fire({
            title: `Détails du retrait #${retrait.reference}`,
            html: detailsHtml,
            icon: 'info',
            width: '600px',
            confirmButtonColor: '#c49d54',
            confirmButtonText: 'Fermer'
        });
    }

    function getStatusBadge(status) {
        const statusText = {
            'en_attente': 'En attente',
            'traite': 'Traité',
            'complete': 'Complété',
            'rejete': 'Rejeté'
        };

        const statusClass = {
            'en_attente': 'badge-en-attente',
            'traite': 'badge-traite',
            'complete': 'badge-complete',
            'rejete': 'badge-rejete'
        };

        const statusIcon = {
            'en_attente': 'clock',
            'traite': 'cog',
            'complete': 'check-circle',
            'rejete': 'times-circle'
        };

        return `<span class="badge-statut ${statusClass[status]}">
            <i class="fas fa-${statusIcon[status]} me-1"></i>${statusText[status] || status}
        </span>`;
    }

    function formatMethod(method) {
        const methods = {
            'virement_bancaire': 'Virement Bancaire',
            'orange_money': 'Orange Money',
            'mtn_money': 'MTN Money',
            'wave': 'Wave'
        };

        return methods[method] || method.charAt(0).toUpperCase() + method.slice(1);
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR').format(amount);
    }

    function confirmAnnulation(retraitId) {
        Swal.fire({
            title: 'Confirmer l\'annulation',
            text: "Êtes-vous sûr de vouloir annuler cette demande de retrait ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, annuler',
            cancelButtonText: 'Non, garder'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${retraitId}`).submit();
            }
        });
    }

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#c49d54'
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erreur!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#c49d54'
        });
    @endif
</script>
