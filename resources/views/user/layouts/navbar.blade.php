<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row" style="background-color: #f35525; color:white">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="{{route('user.dashboard')}}">
            <img src="{{asset('assets/assets/images/logo_E-messeFORME.png')}}" alt="logo" class="logo-dark" style="width: 50%"  />
            <img src="{{asset('assets/assets/images/logo_E-messeFORME.png')}}" alt="logo-light" class="logo-light">
          </a>
          <a class="navbar-brand brand-logo-mini" href="{{route('user.dashboard')}}"><img src="{{asset('assets/assets/images/logo_E-messeFORME.png')}}" alt="logo" style="width: 10%" /></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center">
          {{-- <h5 class="mb-0 font-weight-medium d-none d-lg-flex" style="color: white">Bienvenue Mme/M./mlle {{Auth::user()->name}}</h5> --}}
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown d-none d-xl-inline-flex user-dropdown">
              <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <img class="img-xs rounded-circle ms-2" src="{{ optional(Auth::user())->profile_picture 
                                                ? asset('storage/' . Auth::user()->profile_picture) 
                                                : asset('assets/images/profiles/useriii.jpeg') }}" 
                                        alt="Profile Picture"> 
                <span class="font-weight-normal text-white">{{Auth::user()->name}}</span></a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                <div class="dropdown-header text-center">
                  <img class="img-xs rounded-circle ms-2" src="{{ optional(Auth::user())->profile_picture 
                                                ? asset('storage/' . Auth::user()->profile_picture) 
                                                : asset('assets/images/profiles/useriii.jpeg') }}" 
                                        alt="Profile Picture"> 
                  <p class="mb-1 mt-3">{{Auth::user()->name}}</p>
                  <p class="font-weight-light text-muted mb-0">{{Auth::user()->user_name}}</p>
                </div>
                <a href="{{route('user.profile')}}" class="dropdown-item"><i class="dropdown-item-icon icon-user" style="color: #f35525"></i>Mon profil</a>
                <a href="{{route('user.logout')}}" class="dropdown-item"><i class="dropdown-item-icon icon-power" style="color: #f35525"></i>Déconnexion</a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
          </button>
        </div>
      </nav>