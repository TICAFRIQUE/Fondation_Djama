@extends('backend.layouts.master-without-nav')
@section('title')
   Connexion
@endsection
@section('content')
    @php
        $site = app(\App\Support\SiteContext::class);
        // image de couverture choisie dans Paramètres > Informations, sinon simple dégradé aux couleurs de la fondation
        $cover = $data_parametre?->getFirstMediaUrl('cover');
    @endphp

    <div class="adm-login">
        <section class="adm-login-brand" @if ($cover) style="--adm-login-cover: url('{{ $cover }}')" @endif>
            <div class="adm-login-brand-inner">
                <img src="{{ $site->logo() }}" alt="" width="72" height="72" class="adm-login-logo">
                <h1>{{ $site->name() }}</h1>
                <p class="adm-login-slogan">« {{ $site->slogan() }} »</p>
                <p class="adm-login-text">{{ \Illuminate\Support\Str::limit($site->description(), 220) }}</p>
            </div>
            <a href="{{ route('index') }}" class="adm-login-site"><i class="ri-arrow-left-line"></i> Retour au site</a>
        </section>

        <main class="adm-login-panel">
            <div class="adm-login-card">
                <div class="adm-login-card-head">
                    <span class="adm-login-badge"><i class="ri-shield-keyhole-line"></i> Espace d'administration</span>
                    <h2>Bienvenue !</h2>
                    <p class="text-muted mb-0">Connectez-vous pour gérer le site.</p>
                </div>

                @include('backend.components.alertMessage')

                <form action="{{ route('admin.login') }}" method="post" class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">Email</label>
                        <div class="adm-input-icon">
                            <i class="ri-mail-line"></i>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="username"
                                value="{{ old('email') }}" placeholder="Entrer votre email" autocomplete="username" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="password-input">Mot de passe</label>
                        <div class="adm-input-icon">
                            <i class="ri-lock-2-line"></i>
                            <input type="password" name="password" class="form-control pe-5 password-input @error('password') is-invalid @enderror"
                                placeholder="Entrer votre mot de passe" id="password-input" autocomplete="current-password" required>
                            <button class="adm-password-toggle password-addon" type="button" id="password-addon"
                                aria-label="Afficher ou masquer le mot de passe"><i class="ri-eye-line"></i></button>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100 adm-login-submit" type="submit">
                        Connexion <i class="ri-arrow-right-line"></i>
                    </button>
                </form>
            </div>

            <p class="adm-login-foot">© {{ date('Y') }} {{ $site->name() }}</p>
        </main>
    </div>
@endsection
