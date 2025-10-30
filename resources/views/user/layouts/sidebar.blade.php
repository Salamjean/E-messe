<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <!-- Logo / Mini Brand -->
    <li class="nav-item navbar-brand-mini-wrapper">
      <div class="icon-container">
        <i class="fas fa-bubbles menu-icon"></i>
        <div class="dot-indicator bg-danger"></div>
      </div>
    </li>

    <!-- Profil utilisateur -->
    <li class="nav-item nav-profile">
      <a href="{{ route('user.dashboard') }}" class="nav-link">
        <div class="profile-image">
          <img class="img-xs rounded-circle" 
               src="{{ optional(Auth::user())->profile_picture 
                        ? asset('storage/' . Auth::user()->profile_picture) 
                        : asset('assets/assets/images/avatarAn.jpg') }}" 
               alt="Profile Picture">
          <div class="dot-indicator bg-success"></div>
        </div>
        <div class="text-wrapper">
          <p class="profile-name">{{ Auth::user()->name }}</p>
          <p class="designation">{{ Auth::user()->user_name }}</p>
        </div>
      </a>
    </li>

    <!-- Catégorie Tableau de bord -->
    <li class="nav-item nav-category">
      <span class="nav-link">Tableau de bord</span>
    </li>

    <!-- Tableau de bord -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('user.dashboard') }}">
        <span class="menu-title">Tableau de bord</span>
        <i class="fas fa-tachometer-alt menu-icon"></i>
      </a>
    </li>

    <!-- Evènement -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('user_event.index') }}">
        <span class="menu-title">Evènement</span>
        <i class="fas fa-calendar-alt menu-icon"></i>
      </a>
    </li>

    <!-- Catégorie Actions -->
    <li class="nav-item nav-category">
      <span class="nav-link">Actions</span>
    </li>

    <!-- Demande Messe -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('user.messe.create') }}">
        <span class="menu-title">Demande Messe</span>
        <i class="fas fa-plus-square menu-icon"></i>
      </a>
    </li>

    <!-- Messe Demandée -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('user.messe.index') }}">
        <span class="menu-title">Messe Demandée</span>
        <i class="fas fa-folder-open menu-icon"></i>
      </a>
    </li>

    <!-- Historique Demandes -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('user.messe.history') }}">
        <span class="menu-title">Historique Demandes</span>
        <i class="fas fa-history menu-icon"></i>
      </a>
    </li>
  </ul>
</nav>
