<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    :root {
        --primary: #f35525;
        --dark: #181824;
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

    .retraits-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 20px;
    }

    .retraits-header {
        background: linear-gradient(135deg, var(--dark) 0%, #2d2d42 100%);
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

    /* Stats Cards */
    .stat-card-modern {
        background: var(--light);
        border-radius: var(--border-radius);
        padding: 20px;
        box-shadow: var(--box-shadow);
        border: none;
        height: 100%;
        transition: var(--transition);
        display: flex;
        align-items: center;
    }

    .stat-card-modern:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-right: 15px;
    }

    .icon-pending {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }

    .icon-success {
        background: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }

    .icon-danger {
        background: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }

    .icon-primary {
        background: rgba(243, 85, 37, 0.15);
        color: #f35525;
    }

    .stat-details h4 {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    .stat-details p {
        font-size: 13px;
        color: #6c757d;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
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
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .card-header-modern {
        background: linear-gradient(135deg, var(--primary) 0%, #ff774c 100%);
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
        display: inline-flex;
        align-items: center;
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

    .btn-nouveau {
        background: linear-gradient(135deg, var(--primary) 0%, #ff774c 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-size: 14px;
    }

    .btn-nouveau:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(243, 85, 37, 0.3);
        color: white;
    }

    .btn-outline-custom {
        border: 2px solid var(--primary);
        color: var(--primary);
        background: transparent;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-size: 14px;
    }

    .btn-outline-custom:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    .btn-action {
        padding: 8px;
        border-radius: 8px;
        font-size: 14px;
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 2px;
        border: none;
        transition: var(--transition);
    }

    .btn-confirmer {
        background-color: #28a745;
        color: white;
    }

    .btn-confirmer:hover {
        background-color: #218838;
        color: white;
        transform: scale(1.1);
    }

    .btn-rejeter {
        background-color: #dc3545;
        color: white;
    }

    .btn-rejeter:hover {
        background-color: #c82333;
        color: white;
        transform: scale(1.1);
    }

    .btn-voir {
        background-color: #007bff;
        color: white;
    }

    .btn-voir:hover {
        background-color: #0069d9;
        color: white;
        transform: scale(1.1);
    }

    .method-badge {
        display: inline-block;
        background: rgba(243, 85, 37, 0.1);
        color: var(--primary);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 60px 0;
    }

    .empty-state i {
        font-size: 64px;
        color: #dee2e6;
        margin-bottom: 20px;
        display: block;
    }

    .empty-state p {
        color: #6c757d;
        font-size: 18px;
        margin: 0;
    }

    .detail-row {
        display: flex;
        margin-bottom: 12px;
        border-bottom: 1px solid #f1f1f1;
        padding-bottom: 12px;
    }

    .detail-label {
        flex: 1;
        font-weight: 700;
        color: #555;
        font-size: 14px;
    }

    .detail-value {
        flex: 1.5;
        font-size: 14px;
        color: #333;
    }

    .preuve-upload {
        border: 2px dashed #ddd;
        padding: 30px;
        text-align: center;
        border-radius: 15px;
        margin: 20px 0;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fdfdfd;
    }

    .preuve-upload:hover {
        border-color: var(--primary);
        background-color: #fff9f7;
    }

    .preview-image {
        max-width: 100%;
        max-height: 250px;
        margin-top: 15px;
        display: none;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .retraits-header {
            flex-direction: column;
            text-align: center;
        }

        .user-profile {
            margin-top: 15px;
        }

        .table-modern {
            display: block;
            overflow-x: auto;
        }
    }
</style>
