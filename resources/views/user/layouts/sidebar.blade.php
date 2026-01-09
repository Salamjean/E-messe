<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <!-- Menu de Navigation -->
    <ul class="nav"><br><br><br>
        <!-- Accueil -->
        <li class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.dashboard') }}">
                <i class="fas fa-home menu-icon"></i>
                <span class="menu-title">Accueil</span>
            </a>
        </li>

        <!-- Evénements -->
        <li class="nav-item {{ request()->routeIs('user_event.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user_event.index') }}">
                <i class="fas fa-calendar-alt menu-icon"></i>
                <span class="menu-title">Événements</span>
            </a>
        </li>

        <!-- Demande de Messe -->
        <li class="nav-item {{ request()->routeIs('user.messe.create') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.messe.create') }}">
                <i class="fas fa-plus-circle menu-icon"></i>
                <span class="menu-title">Faire demande</span>
            </a>
        </li>

        <!-- Mes demandes -->
        <li
            class="nav-item {{ request()->routeIs('user.messe.index') || request()->routeIs('user.messe.show') || request()->routeIs('user.messe.history') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.messe.index') }}">
                <i class="fas fa-list-alt menu-icon"></i>
                <span class="menu-title">Mes demandes</span>
            </a>
        </li>

        <!-- Paroisse -->
        <li class="nav-item {{ request()->routeIs('user.paroisse.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.paroisse.index') }}">
                <i class="fas fa-church menu-icon"></i>
                <span class="menu-title">Paroisse</span>
            </a>
        </li>
        <!-- S'identifier / Ma Fiche -->
        <li class="nav-item {{ request()->routeIs('user.fiche.create') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.fiche.create') }}">
                <i class="fas fa-id-card menu-icon"></i>
                <span class="menu-title">S'identifier</span>
            </a>
        </li>

        <!-- Parametres -->
        <li
            class="nav-item {{ request()->routeIs('user.settings.index') || request()->routeIs('user.settings.password') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.settings.index') }}">
                <i class="fas fa-cog menu-icon"></i>
                <span class="menu-title">Paramètres</span>
            </a>
        </li>
    </ul>

    <!-- Profil Utilisateur (Footer du Sidebar) -->

    <div class="sidebar-footer">
        <div class="user-profile d-flex align-items-center mb-3">
            <div class="profile-image-container me-3">
                @php
                    $profilePicture = Auth::user()->profile_picture;
                    if ($profilePicture) {
                        if (!str_starts_with($profilePicture, 'http')) {
                            $profilePicture = asset('storage/' . $profilePicture);
                        }
                    } else {
                        $profilePicture =
                            'https://ui-avatars.com/api/?name=' .
                            urlencode(Auth::user()->name ?: 'User') .
                            '&background=random&color=fff';
                    }
                @endphp
                <img class="img-xs rounded-circle" src="{{ $profilePicture }}" alt="Photo de profil">
            </div>
            <div class="profile-info">
                <p class="profile-name mb-0 text-dark font-weight-bold text-truncate">
                    {{ Auth::user()->name }}
                </p>
                <small class="profile-status text-success">
                    <span class="status-indicator bg-success rounded-circle d-inline-block"
                        style="width: 8px; height: 8px; margin-right: 5px;"></span>
                    En ligne
                </small>
            </div>
        </div>
    </div>
</nav>
