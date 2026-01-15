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

    .btn-outline-primary {
        background-color: #f8f9fa;
        color: var(--dark);
        border: 2px solid #eef2f6;
        border-radius: var(--border-radius);
        padding: 12px 25px;
        font-weight: 600;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;


    }

    .retraits-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 20px;
    }

    .retraits-header {
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

    .retraits-header h1 {
        font-weight: 700;
        margin: 0;
        font-size: 28px;
    }

    .retraits-header p {
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
        box-shadow: var(--box-shadow);
    }

    .card-header-modern {
        background: linear-gradient(135deg, var(--primary) 0%, #cca45e 100%);
        color: white;
        padding: 18px 25px;
        font-weight: 600;
        font-size: 18px;
        border: none;
    }

    .card-body-modern {
        padding: 30px;
    }

    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern th {
        background-color: #f8f9fa;
        padding: 15px;
        font-weight: 600;
        text-align: left;
        border-bottom: 2px solid #eef2f6;
    }

    .table-modern td {
        padding: 15px;
        border-bottom: 1px solid #eef2f6;
        vertical-align: middle;
    }

    .table-modern tbody tr {
        transition: var(--transition);
    }

    .table-modern tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge-statut {
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-en-attente {
        background-color: #fff3cd;
        color: #856404;
    }

    .badge-traite {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .badge-complete {
        background-color: #d4edda;
        color: #155724;
    }

    .badge-rejete {
        background-color: #f8d7da;
        color: #721c24;
    }

    .solde-card {
        background: linear-gradient(135deg, var(--dark) 0%, #4a8a96 100%);
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

    .btn-nouveau {
        background: linear-gradient(135deg, var(--primary) 0%, #cca45e 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 25px;
        font-weight: 600;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-nouveau:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px #cca45e;
        color: white;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
        margin-right: 5px;
    }

    .method-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.1);
        color: var(--primary);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 40px 0;
    }

    .empty-state i {
        font-size: 64px;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    .empty-state p {
        color: #6c757d;
        font-size: 18px;
    }

    @media (max-width: 768px) {
        .retraits-header {
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

        .table-modern {
            display: block;
            overflow-x: auto;
        }
    }

    .swal2-html-container {
        text-align: left !important;
    }

    .detail-row {
        display: flex;
        margin-bottom: 10px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .detail-label {
        flex: 1;
        font-weight: 600;
        color: #555;
    }

    .detail-value {
        flex: 2;
    }
</style>
