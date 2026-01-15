<style>
    :root {
        --primary-color: #c49d54;
        --secondary-color: #5ea7b5;
        --dark-color: #2c3e50;
        --light-color: #f8f9fa;
        --success-color: #27ae60;
        --danger-color: #e74c3c;
        --warning-color: #f39c12;
        --info-color: #3498db;
        --border-radius: 12px;
        --box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    /* Card Modern Styling */
    .card-modern {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        background: #fff;
        transition: var(--transition);
        margin-bottom: 2rem;
    }

    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    }

    .card-header-modern {
        background: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .card-title-modern {
        color: var(--secondary-color);
        font-weight: 700;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Filter Section */
    .filter-section {
        background: #fff;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--box-shadow);
    }

    .filter-label {
        font-weight: 600;
        color: var(--dark-color);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .form-select-modern,
    .form-control-modern {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0.6rem 1rem;
        transition: var(--transition);
    }

    .form-select-modern:focus,
    .form-control-modern:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(196, 157, 84, 0.15);
    }

    /* Table Styling */
    .table-responsive {
        border-radius: 0 0 var(--border-radius) var(--border-radius);
    }

    #paroissienTable {
        border-collapse: separate;
        border-spacing: 0;
        width: 100% !important;
    }

    #paroissienTable thead th {
        background-color: var(--secondary-color) !important;
        color: #fff !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 1.2rem 1rem;
        border: none;
        text-align: center;
        /* Centering headers */
        vertical-align: middle;
    }

    #paroissienTable tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        color: #555;
        text-align: center;
        /* Centering content */
    }

    #paroissienTable tbody tr:hover {
        background-color: rgba(94, 167, 181, 0.03);
    }

    /* Action Buttons */
    .btn-action-group {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-action {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        border: none;
        color: #fff;
        cursor: pointer;
    }

    .btn-view {
        background-color: var(--info-color);
    }

    .btn-view:hover {
        background-color: #2980b9;
        transform: scale(1.1);
        color: #fff;
    }

    .btn-edit {
        background-color: var(--primary-color);
    }

    .btn-edit:hover {
        background-color: #b38d45;
        transform: scale(1.1);
        color: #fff;
    }

    .btn-delete {
        background-color: var(--danger-color);
    }

    .btn-delete:hover {
        background-color: #c0392b;
        transform: scale(1.1);
        color: #fff;
    }

    /* Header Icons */
    .header-icon {
        width: 45px;
        height: 45px;
        background: rgba(94, 167, 181, 0.1);
        color: var(--secondary-color);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    /* Export Buttons */
    .btn-export {
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
        border: none;
    }

    .btn-export-pdf {
        background-color: #e74c3c;
        color: #fff;
    }

    .btn-export-excel {
        background-color: #27ae60;
        color: #fff;
    }

    .btn-add {
        background-color: var(--primary-color);
        color: #fff;
    }

    .btn-export:hover,
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        color: #fff;
    }

    /* DataTables Pagination Premium */
    .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .page-link {
        color: var(--primary-color);
        border-radius: 5px;
        margin: 0 3px;
    }
</style>
