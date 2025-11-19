<aside class="mdc-drawer mdc-drawer--dismissible mdc-drawer--open" style="background-color: red">
      <div class="mdc-drawer__header" >
        <a href="{{route('paroisse.dashboard')}}" class="brand-logo">
          <img src="{{ optional(Auth::guard('paroisse')->user())->profile_picture 
                                                ? asset('storage/' . Auth::guard('paroisse')->user()->profile_picture) 
                                                : asset('assets/assets/images/sancta.jpg') }}" style="width: 50%; margin-left:50px" alt="logo">
        </a>
      </div>
      <div class="mdc-drawer__content">
        <div class="user-info">
          <p class="name text-center">{{ Auth::guard('paroisse')->user()->name }}</p>
          <p class="email text-center">{{ Auth::guard('paroisse')->user()->email }}</p>
        </div>

        <div class="mdc-list-group">
          <nav class="mdc-list mdc-drawer-menu">

            <!-- Tableau de bord -->
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{ route('paroisse.dashboard') }}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon">dashboard</i>
                Tableau de bord
              </a>
            </div>

            <!-- Ajouter un évènement -->
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{ route('event.index') }}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon">event</i>
                Ajouter un évènement
              </a>
            </div>

            <!-- Valider les demandes -->
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{ route('demandes.messes.validate') }}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon">check_circle</i>
                Valider les demandes
              </a>
            </div>

            <!-- Demandes de messes -->
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('demandes.messes.index')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">grid_on</i>
                Demandes de messes
              </a>
            </div>

            <!-- Montant de demande -->
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{ route('paroisse.offrande') }}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon">attach_money</i>
                Montant de demande
              </a>
            </div>

            <!-- Historique -->
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{ route('demandes.messes.history') }}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon">history</i>
                Historique
              </a>
            </div>

            <!-- Retraits -->
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="ui-sub-menu">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon">account_balance_wallet</i>
                Retrait
                <i class="mdc-drawer-arrow material-icons">chevron_right</i>
              </a>
              <div class="mdc-expansion-panel" id="ui-sub-menu">
                <nav class="mdc-list mdc-drawer-submenu">
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{ route('paroisse.retrait.create') }}">Demander</a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{ route('paroisse.retraits') }}">En attente</a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{ route('paroisse.history') }}">Historiques</a>
                  </div>
                </nav>
              </div>
            </div>

          </nav>
        </div>
      </div>
</aside>
