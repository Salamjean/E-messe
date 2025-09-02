@extends('admin.layouts.template')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="paroisses-container">
    <div class="header-section">
        <h1><i class="fas fa-archive"></i> Utilisateurs archivés</h1>
        <p>Gérez les utilisateurs archivés de la plateforme</p>
    </div>

    <div class="action-bar">
        <a href="{{ route('admin.user.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Retour aux utilisateurs actifs
        </a>
    </div>

    @if($archivedUsers->count() > 0)
        <div class="paroisses-grid">
            @foreach($archivedUsers as $user)
                <div class="paroisse-card">
                    <div class="card-header">
                        <div class="profile-img">
                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=200&background=6c757d&color=fff' }}" 
                                 alt="{{ $user->name }}">
                        </div>
                        <div class="actions">
                            <form action="{{ route('users.restore', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-icon edit-btn" title="Restaurer">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </form>
                            <form action="{{ route('users.force-delete', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon delete-btn" 
                                        onclick="return confirm('Supprimer définitivement ?')" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="paroisse-name">{{ $user->user_name }}</h3>
                        <div class="paroisse-info">
                            <p><i class="fas fa-user"></i> {{ $user->name }}</p>
                            <p><i class="fas fa-phone"></i> {{ $user->contact }}</p>
                            <p><i class="fas fa-envelope"></i> {{ $user->email }}</p>
                            <p><i class="fas fa-calendar-times"></i> Archivé le : {{ $user->archived_at }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-archive"></i>
            <h3>Aucun utilisateur archivé</h3>
            <p>Aucun utilisateur n'a été archivé pour le moment</p>
        </div>
    @endif
</div>
<style>
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }
    :root {
        --primary: #f35525;
        --dark: #181824;
        --light: #ffffff;
        --gray: #f8f9fa;
        --gray-dark: #eaeaea;
        --success: #28a745;
        --danger: #dc3545;
    }
    
    .paroisses-container {
        padding: 20px;
        background-color: #f5f7fb;
        min-height: 100vh;
    }
    
    .header-section {
        margin-bottom: 30px;
    }
    
    .header-section h1 {
        color: var(--dark);
        font-weight: 700;
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .header-section h1 i {
        color: var(--primary);
        margin-right: 15px;
        font-size: 32px;
    }
    
    .header-section p {
        color: #6c757d;
        font-size: 16px;
        margin: 0;
    }
    
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .search-box {
        position: relative;
        flex: 1;
        max-width: 400px;
    }
    
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .search-box input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 2px solid var(--gray-dark);
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    
    .search-box input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(243, 85, 37, 0.2);
    }
    
    .btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    
    .btn-primary:hover {
        background: #e04a1f;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(243, 85, 37, 0.3);
    }
    
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: rgba(243, 85, 37, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    
    .stat-icon i {
        font-size: 24px;
        color: var(--primary);
    }
    
    .stat-info h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
    }
    
    .stat-info p {
        margin: 5px 0 0;
        color: #6c757d;
        font-size: 14px;
    }
    
    .filters {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .filter-group label {
        font-weight: 500;
        color: var(--dark);
        font-size: 14px;
    }
    
    .filter-group select {
        padding: 10px 15px;
        border: 2px solid var(--gray-dark);
        border-radius: 8px;
        background: white;
        font-size: 14px;
    }
    
    .paroisses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }
    
    .paroisse-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .paroisse-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        position: relative;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .profile-img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--gray-dark);
    }
    
    .profile-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .edit-btn {
        background: rgba(40, 167, 69, 0.1);
        color: var(--success);
    }
    
    .edit-btn:hover {
        background: var(--success);
        color: white;
    }
    
    .delete-btn {
        background: rgba(220, 53, 69, 0.1);
        color: var(--danger);
    }
    
    .delete-btn:hover {
        background: var(--danger);
        color: white;
    }
    
    .card-body {
        padding: 0 20px 20px;
    }
    
    .paroisse-name {
        margin: 0 0 15px;
        color: var(--dark);
        font-size: 18px;
        font-weight: 600;
    }
    
    .paroisse-info {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .paroisse-info p {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #6c757d;
        font-size: 14px;
    }
    
    .paroisse-info i {
        width: 16px;
        color: var(--primary);
    }
    
    .card-footer {
        padding: 15px 20px;
        background: var(--gray);
        border-top: 1px solid var(--gray-dark);
    }
    
    .badge {
        background: rgba(243, 85, 37, 0.1);
        color: var(--primary);
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .empty-state i {
        font-size: 60px;
        color: var(--primary);
        margin-bottom: 20px;
    }
    
    .empty-state h3 {
        color: var(--dark);
        margin-bottom: 10px;
    }
    
    .empty-state p {
        color: #6c757d;
        margin-bottom: 25px;
    }
    
    .alert {
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        position: relative;
    }
    
    .alert-success {
        background: #e6f7ee;
        color: #0a7b4c;
        border: 1px solid #a3e6c7;
    }
    
    .alert-danger {
        background: #fde8e8;
        color: #e53e3e;
        border: 1px solid #f8b6b6;
    }
    
    .alert i {
        margin-right: 10px;
        font-size: 20px;
    }
    
    .btn-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        color: inherit;
    }
    
    @media (max-width: 768px) {
        .action-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box {
            max-width: 100%;
        }
        
        .filters {
            flex-direction: column;
        }
        
        .paroisses-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-cards {
            grid-template-columns: 1fr;
        }
    }
</style>

@endsection