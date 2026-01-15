<div class="retraits-header">
    <div>
        <h1><i class="fas fa-{{ $icon ?? 'history' }} me-2"></i>{{ $title }}</h1>
        <p>{{ $subtitle }}</p>
    </div>
    <div class="user-profile">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('paroisse')->user()->name) }}" alt="Profile">
    </div>
</div>
