<header class="mdc-top-app-bar" style="background-color: #181824">
        <div class="mdc-top-app-bar__row">
          <div class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
            <button class="material-icons mdc-top-app-bar__navigation-icon mdc-icon-button sidebar-toggler text-white">menu</button>
          </div>
          <div class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end mdc-top-app-bar__section-right">
            <div class="menu-button-container menu-profile d-none d-md-block">
              <button class="mdc-button mdc-menu-button">
                <span class="d-flex align-items-center">
                  <span class="figure">
                    <img src="{{ optional(Auth::guard('paroisse')->user())->profile_picture 
                                                ? asset('storage/' . Auth::guard('paroisse')->user()->profile_picture) 
                                                : asset('assets/assets/images/sancta-blanc.jpg') }}" alt="user" class="user">
                  </span>
                  <span class="user-name text-white"> {{Auth::guard('paroisse')->user()->name}} </span>
                </span>
              </button>
              <div class="mdc-menu mdc-menu-surface" tabindex="-1">
                <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical">
                  <li>
                    <a href="{{route('paroisse.profile')}}" class="mdc-list-item" role="menuitem">
                      <div class="item-thumbnail item-thumbnail-icon-only">
                        <i class="mdi mdi-home text-primary"></i>
                      </div>
                      <div class="item-content d-flex align-items-start flex-column justify-content-center">
                        <h6 class="item-subject font-weight-normal">Mon compte</h6>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('paroisse.logout')}}" class="mdc-list-item" role="menuitem">
                      <div class="item-thumbnail item-thumbnail-icon-only">
                        <i class="mdi mdi-settings-outline text-primary"></i>                      
                      </div>
                      <div class="item-content d-flex align-items-start flex-column justify-content-center">
                        <h6 class="item-subject font-weight-normal">Déconnexion</h6>
                      </div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </header>