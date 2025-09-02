@extends('admin.layouts.template')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
  :root {
    --primary: #f35525;
    --dark: #181824;
    --light: #ffffff;
    --gray: #f8f9fa;
    --gray-dark: #eaeaea;
    --success: #28a745;
    --info: #17a2b8;
    --warning: #ffc107;
    --danger: #dc3545;
  }
  
  .dashboard-container {
    padding: 20px;
    background-color: #f5f7fb;
    min-height: 100vh;
  }
  
  .dashboard-header {
    margin-bottom: 30px;
  }
  
  .dashboard-header h1 {
    color: var(--dark);
    font-weight: 700;
    display: flex;
    align-items: center;
    margin-bottom: 10px;
  }
  
  .dashboard-header h1 i {
    color: var(--primary);
    margin-right: 15px;
    font-size: 32px;
  }
  
  .dashboard-header p {
    color: #6c757d;
    font-size: 16px;
    margin: 0;
  }
  
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }
  
  .stat-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    display: flex;
    align-items: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  }
  
  .stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    font-size: 28px;
  }
  
  .stat-icon.users {
    background: rgba(243, 85, 37, 0.1);
    color: var(--primary);
  }
  
  .stat-icon.paroisses {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
  }
  
  .stat-icon.offrandes {
    background: rgba(23, 162, 184, 0.1);
    color: var(--info);
  }
  
  .stat-icon.evenements {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning);
  }
  
  .stat-info h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: var(--dark);
  }
  
  .stat-info p {
    margin: 5px 0 0;
    color: #6c757d;
    font-size: 14px;
  }
  
  .charts-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-bottom: 30px;
  }
  
  @media (max-width: 992px) {
    .charts-section {
      grid-template-columns: 1fr;
    }
  }
  
  .chart-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  }
  
  .chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .chart-header h3 {
    margin: 0;
    color: var(--dark);
    font-size: 18px;
    font-weight: 600;
  }
  
  .chart-container {
    position: relative;
    height: 300px;
  }
  
  .recent-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }
  
  .recent-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  }
  
  .recent-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--gray-dark);
  }
  
  .recent-header h3 {
    margin: 0;
    color: var(--dark);
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
  }
  
  .recent-header h3 i {
    margin-right: 10px;
    color: var(--primary);
  }
  
  .recent-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
  }
  
  .recent-item {
    display: flex;
    align-items: center;
    padding: 12px;
    border-radius: 10px;
    background: var(--gray);
    transition: background 0.3s ease;
  }
  
  .recent-item:hover {
    background: #e9ecef;
  }
  
  .recent-item img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 15px;
  }
  
  .recent-item-info {
    flex: 1;
  }
  
  .recent-item-info h4 {
    margin: 0;
    font-size: 15px;
    color: var(--dark);
  }
  
  .recent-item-info p {
    margin: 3px 0 0;
    font-size: 13px;
    color: #6c757d;
  }
  
  .recent-item-date {
    font-size: 12px;
    color: #6c757d;
  }
  
  .quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
  }
  
  .action-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
  }
  
  .action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  }
  
  .action-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 24px;
    color: white;
  }
  
  .action-icon.user {
    background: var(--primary);
  }
  
  .action-icon.paroisse {
    background: var(--success);
  }
  
  .action-icon.offrande {
    background: var(--info);
  }
  
  .action-icon.event {
    background: var(--warning);
  }
  
  .action-card h3 {
    margin: 0 0 10px;
    font-size: 16px;
    color: var(--dark);
  }
  
  .action-card p {
    margin: 0;
    font-size: 13px;
    color: #6c757d;
  }
  
  .system-status {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  }
  
  .status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .status-header h3 {
    margin: 0;
    color: var(--dark);
    font-size: 18px;
    font-weight: 600;
  }
  
  .status-indicator {
    display: flex;
    align-items: center;
    font-size: 14px;
    color: var(--success);
    font-weight: 500;
  }
  
  .status-indicator::before {
    content: "";
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--success);
    margin-right: 8px;
  }
  
  .status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
  }
  
  .status-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px;
    border-radius: 10px;
    background: var(--gray);
  }
  
  .status-item h4 {
    margin: 0 0 10px;
    font-size: 14px;
    color: var(--dark);
  }
  
  .status-item p {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: var(--primary);
  }
  
  @media (max-width: 768px) {
    .stats-grid {
      grid-template-columns: 1fr;
    }
    
    .recent-section {
      grid-template-columns: 1fr;
    }
    
    .quick-actions {
      grid-template-columns: repeat(2, 1fr);
    }
  }
</style>

<div class="dashboard-container">
  <div class="dashboard-header">
    <h1><i class="fas fa-tachometer-alt"></i> Tableau de Bord Administrateur</h1>
    <p>Vue d'ensemble de l'activité de la plateforme</p>
  </div>
  
  <!-- Cartes de statistiques -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon users">
        <i class="fas fa-users"></i>
      </div>
      <div class="stat-info">
        <h3>{{ $usersCount }}</h3>
        <p>Utilisateurs inscrits</p>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon paroisses">
        <i class="fas fa-church"></i>
      </div>
      <div class="stat-info">
        <h3>{{ $paroissesCount }}</h3>
        <p>Paroisses enregistrées</p>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon offrandes">
        <i class="fas fa-money-bill-wave"></i>
      </div>
      <div class="stat-info">
        <h3>{{ number_format($totalOffrandes, 0, ',', ' ') }} FCFA</h3>
        <p>Total des offrandes</p>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon evenements">
          <i class="fas fa-user-check"></i>
      </div>
      <div class="stat-info">
        <div class="status-header">
          <div class="status-indicator">En ligne</div>
        </div>
          <h3>{{ $connectedUsersCount }}</h3>
          <p>Utilisateurs connectés</p>
      </div>
  </div>
  </div>

  
  
  <!-- Actions rapides -->
  <div class="quick-actions">
    <div class="action-card" onclick="window.location.href='#'">
      <div class="action-icon user">
        <i class="fas fa-user-plus"></i>
      </div>
      <h3>Gérer les utilisateurs</h3>
      <p>Supprimer des utilisateurs</p>
    </div>
    
    <div class="action-card" onclick="window.location.href='{{ route('paroisse.index') }}'">
      <div class="action-icon paroisse">
        <i class="fas fa-church"></i>
      </div>
      <h3>Gérer les paroisses</h3>
      <p>Enregistrer, modifier et supprimer une paroisse</p>
    </div>
    
    <div class="action-card" onclick="window.location.href='#'">
      <div class="action-icon offrande">
        <i class="fas fa-donate"></i>
      </div>
      <h3>Voir les offrandes</h3>
      <p>Consulter les paroisses et leurs offrandes</p>
    </div>
  </div>
  
  <!-- Graphiques -->
  <div class="charts-section">
    <div class="chart-card">
      <div class="chart-header">
        <h3>Activité des offrandes (30 derniers jours)</h3>
      </div>
      <div class="chart-container">
        <canvas id="offrandesChart"></canvas>
      </div>
    </div>
    
    <div class="chart-card">
      <div class="chart-header">
        <h3>Répartition des paroisses</h3>
      </div>
      <div class="chart-container">
        <canvas id="paroissesChart"></canvas>
      </div>
    </div>
  </div>
  
  <!-- Activité récente -->
  <div class="recent-section">
    <div class="recent-card">
      <div class="recent-header">
        <h3><i class="fas fa-users"></i> Utilisateurs récents</h3>
        <a href="#" class="btn btn-sm btn-primary">Voir tout</a>
      </div>
      <div class="recent-list">
        @foreach($recentUsers as $user)
        <div class="recent-item">
          <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=200&background=f35525&color=fff' }}" 
               alt="{{ $user->name }}">
          <div class="recent-item-info">
            <h4>{{ $user->name }}</h4>
            <p>{{ $user->email }}</p>
          </div>
          <div class="recent-item-date">
            {{ $user->created_at->diffForHumans() }}
          </div>
        </div>
        @endforeach
      </div>
    </div>
    
    <div class="recent-card">
      <div class="recent-header">
        <h3><i class="fas fa-church"></i> Paroisses récentes</h3>
        <a href="{{ route('paroisse.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
      </div>
      <div class="recent-list">
        @foreach($recentParoisses as $paroisse)
        <div class="recent-item">
          <img src="{{ $paroisse->profile_picture ? asset('storage/' . $paroisse->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($paroisse->name) . '&size=200&background=f35525&color=fff' }}" 
               alt="{{ $paroisse->name }}">
          <div class="recent-item-info">
            <h4>{{ $paroisse->name }}</h4>
            <p>{{ $paroisse->localisation }}</p>
          </div>
          <div class="recent-item-date">
            {{ $paroisse->created_at->diffForHumans() }}
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Graphique des offrandes
    const offrandesCtx = document.getElementById('offrandesChart').getContext('2d');
    const offrandesChart = new Chart(offrandesCtx, {
      type: 'line',
      data: {
        labels: {!! json_encode($offrandesLabels) !!},
        datasets: [{
          label: 'Montant des offrandes (FCFA)',
          data: {!! json_encode($offrandesData) !!},
          borderColor: '#f35525',
          backgroundColor: 'rgba(243, 85, 37, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              drawBorder: false
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });
    
    // Graphique des paroisses - CORRECTION ICI
    const paroissesCtx = document.getElementById('paroissesChart').getContext('2d');
    const paroissesChart = new Chart(paroissesCtx, {
      type: 'doughnut',
      data: {
        labels: {!! json_encode($paroissesStatsLabels) !!},
        datasets: [{
          data: {!! json_encode($paroissesStatsData) !!},
          backgroundColor: [
            '#f35525',
            '#28a745',
            '#17a2b8',
            '#ffc107',
            '#6f42c1',
            '#e83e8c',
            '#20c997',
            '#fd7e14',
            '#6610f2',
            '#6c757d'
          ],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              boxWidth: 12,
              font: {
                size: 11
              }
            }
          }
        }
      }
    });
  });
</script>
@endsection