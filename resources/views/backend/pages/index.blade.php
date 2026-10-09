@extends('backend.layouts.master')
@section('title')
    Tableau de bord
@endsection
@section('content')
    <div class="adm-welcome">
        <div>
            @auth
                <h1 class="adm-welcome-title">Bonjour, {{ Auth::user()->username }} !</h1>
            @endauth
            <p class="mb-0">Voici l'activité du site de la fondation.</p>
        </div>
        <div class="adm-clock">
            <i class="ri-time-line"></i>
            <span id="horloge"></span>
            <span class="adm-clock-date" id="date"></span>
        </div>
    </div>

    <div class="row g-3 mb-3">
        @foreach ($stats as $stat)
            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route($stat['route']) }}" class="adm-stat">
                    <span class="adm-stat-icon"><i class="{{ $stat['icon'] }}"></i></span>
                    <span class="adm-stat-value">{{ $stat['value'] }}</span>
                    <span class="adm-stat-label">{{ $stat['label'] }}</span>
                </a>
            </div>
        @endforeach
    </div>
    <!--end row-->

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Derniers messages reçus</h5>
                    <a href="{{ route('messages.index') }}" class="btn btn-soft-primary btn-sm">Tout voir</a>
                </div>
                <div class="card-body">
                    @forelse ($lastMessages as $message)
                        <div class="d-flex justify-content-between gap-3 py-2 {{ $loop->last ? '' : 'border-bottom' }}">
                            <div>
                                <strong>{{ $message->name }}</strong> — {{ $message->subject }}
                                <div class="text-muted">{{ \Illuminate\Support\Str::limit($message->message, 120) }}</div>
                            </div>
                            <small class="text-muted text-nowrap">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aucun message pour le moment.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Raccourcis</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('news.index') }}" class="adm-shortcut"><i class="ri-newspaper-line"></i> Publier une actualité</a>
                    <a href="{{ route('flash-infos.index') }}" class="adm-shortcut"><i class="ri-megaphone-line"></i> Diffuser une info flash</a>
                    <a href="{{ route('galerie.index') }}" class="adm-shortcut"><i class="ri-image-add-line"></i> Ajouter une photo</a>
                    <a href="{{ route('sections.index') }}" class="adm-shortcut"><i class="ri-layout-row-line"></i> Organiser la page d'accueil</a>
                    <a href="{{ route('parametre.index') }}" class="adm-shortcut"><i class="ri-settings-3-line"></i> Coordonnées et référencement</a>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
@endsection
@section('script')
    <script>
        function mettreAJourHorloge() {
            var maintenant = new Date();
            document.getElementById('horloge').textContent = maintenant.toLocaleTimeString('fr-FR');
            document.getElementById('date').textContent = maintenant.toLocaleDateString('fr-FR', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        setInterval(mettreAJourHorloge, 1000);
        mettreAJourHorloge(); // Appel initial pour afficher l'heure et la date immédiatement
    </script>
@endsection
