<nav class="navbar fixed-top " style="z-index: 999">
    <div
        class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center navbar-brand-custom d-none d-lg-flex">
        <a class="navbar-brand brand-logo" href="{{ route('user.dashboard') }}" style="width: 150px;">
            <img src="{{ asset('assets/assets/images/logo_principal.svg') }}" alt="Logo E-Messe"
                class="logo-full navbar-logo" style="height: 80px; width: 300px;" />
        </a>
    </div>
    <div class="text-center d-flex align-items-center justify-content-center navbar-brand-custom d-lg-none">
        <a class="navbar-brand brand-logo" href="{{ route('user.dashboard') }}">
            <img src="{{ asset('assets/assets/images/logo_principal.svg') }}" alt="Logo E-Messe"
                class="logo-full navbar-logo" style="height: 60px; width: 200px;" />
        </a>
    </div>

    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <!-- Bouton Toggle Sidebar (Desktop) -->
        {{-- <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button> --}}

        <!-- Welcome Message -->
        <div class="welcome-message d-flex flex-column ms-3 mt-2">
            <h4 class="mb-1 font-weight-bold text-dark">Bienvenue, {{ Auth::user()->name ?? 'Utilisateur' }} !</h4>
            <p class="mb-0 text-muted small">Que la paix soit avec vous.</p>
        </div>

        <!-- Right Side -->
        <ul class="navbar-nav navbar-nav-right ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator" href="#">
                    <i class="icon-bell text-dark icon-bell-custom"></i>
                    <!-- <span class="count-symbol bg-danger"></span> -->
                </a>
            </li>

            <!-- Profil Dropdown (Optionnel, sinon juste l'image) -->
            {{-- <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                    <div class="nav-profile-img">
                        @php
                            $navbarProfilePic = Auth::user()->profile_picture;
                             if ($navbarProfilePic && !str_starts_with($navbarProfilePic, 'http')) {
                                $navbarProfilePic = asset('storage/' . $navbarProfilePic);
                            } elseif (!$navbarProfilePic) {
                                $navbarProfilePic = 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'User') . '&background=random&color=fff&size=40';
                            }
                        @endphp
                        <img src="{{ $navbarProfilePic }}"
                            alt="image">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                    aria-labelledby="profileDropdown">
                    <h6 class="dropdown-header">
                        {{ Auth::user()->name ?? 'Utilisateur' }}
                    </h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
                        <i class="dropdown-item-icon icon-user text-primary"></i>
                        Mon Profil
                    </a>
                    <form method="POST" action="#">
                        @csrf
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="dropdown-item-icon icon-power text-primary"></i> Déconnexion
                        </a>
                    </form>
                </div>
            </li> --}}
        </ul>

        <!-- Bouton Toggle Mobile -->
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>
<hr>
