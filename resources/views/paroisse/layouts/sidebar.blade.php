<aside class="mdc-drawer mdc-drawer--dismissible mdc-drawer--open" style="background-color: red">
      <div class="mdc-drawer__header" >
        <a href="{{route('paroisse.dashboard')}}" class="brand-logo">
          <img src="{{ optional(Auth::guard('paroisse')->user())->profile_picture 
                                                ? asset('storage/' . Auth::guard('paroisse')->user()->profile_picture) 
                                                : asset('assets/assets/images/sancta.jpg') }}" style="width: 50%" alt="logo">
        </a>
      </div>
      <div class="mdc-drawer__content">
        <div class="user-info">
          <p class="name text-center"> {{Auth::guard('paroisse')->user()->name}} </p>
          <p class="email text-center">{{Auth::guard('paroisse')->user()->email}}</p>
        </div>
        <div class="mdc-list-group">
          <nav class="mdc-list mdc-drawer-menu">
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('paroisse.dashboard')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">home</i>
                Tableau de bord
              </a>
            </div>
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('demandes.messes.validate')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">grid_on</i>
                Valider Demandes
              </a>
            </div>
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('demandes.messes.index')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">grid_on</i>
                Demandes de messes
              </a>
            </div>
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('paroisse.offrande')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">save</i>
                Ajout de l'offrande
              </a>
            </div>
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('demandes.messes.history')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">dashboard</i>
                Historiques 
              </a>
            </div>
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="ui-sub-menu">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">dashboard</i>
                Retrait
                <i class="mdc-drawer-arrow material-icons">chevron_right</i>
              </a>
              <div class="mdc-expansion-panel" id="ui-sub-menu">
                <nav class="mdc-list mdc-drawer-submenu">
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('paroisse.retrait.create')}}">
                      Demander 
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('paroisse.retraits')}}">
                     En attente
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('paroisse.history')}}">
                      Historiques
                    </a>
                  </div>
                </nav>
              </div>
            </div>
            {{-- <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="sample-page-submenu">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">pages</i>
                Sample Pages
                <i class="mdc-drawer-arrow material-icons">chevron_right</i>
              </a>
              <div class="mdc-expansion-panel" id="sample-page-submenu">
                <nav class="mdc-list mdc-drawer-submenu">
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="pages/samples/blank-page.html">
                      Blank Page
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="pages/samples/403.html">
                      403
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="pages/samples/404.html">
                      404
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="pages/samples/500.html">
                      500
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="pages/samples/505.html">
                      505
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="pages/samples/login.html">
                      Login
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="pages/samples/register.html">
                      Register
                    </a>
                  </div>
                </nav>
              </div> --}}
            </div>
    </aside>