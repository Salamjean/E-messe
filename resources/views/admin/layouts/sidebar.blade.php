<aside class="mdc-drawer mdc-drawer--dismissible mdc-drawer--open" style="background-color: red">
    <div class="mdc-drawer__header">
        <a href="{{route('admin.dashboard')}}" class="brand-logo">
            <img src="{{asset('assets/assets/images/kks.jpeg')}}" style="width: 50%" alt="logo">
        </a>
    </div>
    <div class="mdc-drawer__content">
        <div class="user-info">
            <p class="name text-center"> {{Auth::guard('admin')->user()->name.' '.Auth::guard('admin')->user()->prenom}} </p>
            <p class="email text-center">{{Auth::guard('admin')->user()->email}}</p>
        </div>
        <div class="mdc-list-group">
            <nav class="mdc-list mdc-drawer-menu">
                <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('admin.dashboard')}}">
                        <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">home</i>
                        Tableau de bord
                    </a>
                </div>
                <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('paroisse.create')}}">
                        <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">save</i>
                        Ajout Paroisse
                    </a>
                </div>
                <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('paroisse.index')}}">
                        <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">grid_on</i>
                        Liste Paroisse
                    </a>
                </div>
                <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('admin.user.index')}}">
                        <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">dashboard</i>
                        Listes des utilisateurs 
                    </a>
                </div>
                <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="ui-sub-menu">
                        <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">dashboard</i>
                        Retrait
                        @if($pendingWithdrawalsCount > 0)
                        <span class="badge bg-danger badge-pill ms-2">{{ $pendingWithdrawalsCount }}</span>
                        @endif
                        <i class="mdc-drawer-arrow material-icons">chevron_right</i>
                    </a>
                    <div class="mdc-expansion-panel" id="ui-sub-menu">
                        <nav class="mdc-list mdc-drawer-submenu">
                            <div class="mdc-list-item mdc-drawer-item">
                                <a class="mdc-drawer-link" href="{{route('admin.paroisse.index')}}">
                                    Demande Retrait
                                </a>
                            </div>
                            <div class="mdc-list-item mdc-drawer-item">
                                <a class="mdc-drawer-link" href="{{route('admin.paroisse.history')}}">
                                    Historiques
                                </a>
                            </div>
                        </nav>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</aside>

<!-- Ajoutez ce style pour le badge -->
<style>
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 50%;
        min-width: 1.5rem;
        height: 1.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: white;
    }
    
    .mdc-drawer-item .badge {
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
    }
    
    .mdc-drawer-submenu .mdc-drawer-item .badge {
        position: static;
        transform: none;
        margin-left: auto;
    }
</style>