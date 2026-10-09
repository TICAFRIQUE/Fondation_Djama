{{-- FAIRE UN DON : moyens de don (Admin > Moyens de don) et formulaire d'engagement (Admin > Engagements) --}}
@extends('frontend.layouts.app')

@php
  $lead = 'Chaque geste compte : un don, un parrainage, du temps ou un partenariat changent concrètement des vies.';
  $errorsBag = $errors->getBag('engagement');
  $otherAgirs = $agirs->where('type', '!=', 'donation');
@endphp

@section('title', 'Faire un don et soutenir la fondation')
@section('meta_description', $lead)

@section('content')
  @include('frontend.partials.page-hero', ['title' => 'Faire un don', 'lead' => $lead])

  <section class="section-pad">
    <div class="container">
      <div class="row g-4 g-lg-5">

        <div class="col-lg-5">
          <div class="section-eyebrow">Moyens de don</div>
          <h2 class="section-title">Votre don, <span>en toute simplicité</span></h2>
          <p class="contact-lead">Effectuez votre don par l'un des moyens ci-dessous, puis prévenez-nous avec le formulaire pour que nous puissions vous remercier.</p>

          @include('frontend.partials.payment-methods')

          @if ($otherAgirs->isNotEmpty())
          <h2 class="aside-heading">Autres façons d'aider</h2>
          <ul class="agir-list">
            @foreach ($otherAgirs as $agir)
            <li>
              <span class="agir-list-icon" style="background:{{ $agir->color ?: '#FFF3E0' }}" aria-hidden="true">{!! $agir->icon !!}</span>
              <div>
                <a href="{{ route('don', ['type' => $agir->type]) }}#engagement-form">{{ $agir->title }}</a>
                <p>{{ $agir->description }}</p>
              </div>
            </li>
            @endforeach
          </ul>
          @endif
        </div>

        <div class="col-lg-7">
          <div class="form-card" id="engagement-form">
            <h2 class="form-card-title">Je m'engage</h2>

            @if (session('success'))
            <div class="site-alert site-alert-success" role="status"><i class="bi bi-check-circle-fill" aria-hidden="true"></i> {{ session('success') }}</div>
            @endif
            @if ($errorsBag->any())
            <div class="site-alert site-alert-error" role="alert"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i> Merci de vérifier les champs signalés.</div>
            @endif

            <form action="{{ route('engagement.store') }}" method="POST">
              @csrf
              {{-- champ piège anti-robots, invisible pour les visiteurs --}}
              <div class="honeypot" aria-hidden="true">
                <label for="engagement-website">Ne pas remplir</label>
                <input type="text" name="website" id="engagement-website" tabindex="-1" autocomplete="off">
              </div>

              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label-djama" for="engagement-type">Je souhaite</label>
                  <select name="type" id="engagement-type" class="form-select" required>
                    @foreach ($types as $value => $label)
                    <option value="{{ $value }}" @selected(old('type', $selectedType) === $value)>{{ $label }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label-djama" for="engagement-name">Nom complet</label>
                  <input type="text" name="name" id="engagement-name" class="form-control @if ($errorsBag->has('name')) is-invalid @endif"
                    value="{{ old('name') }}" autocomplete="name" required>
                  @if ($errorsBag->has('name'))<div class="invalid-feedback">{{ $errorsBag->first('name') }}</div>@endif
                </div>

                <div class="col-md-6">
                  <label class="form-label-djama" for="engagement-email">Email</label>
                  <input type="email" name="email" id="engagement-email" class="form-control @if ($errorsBag->has('email')) is-invalid @endif"
                    value="{{ old('email') }}" autocomplete="email" required>
                  @if ($errorsBag->has('email'))<div class="invalid-feedback">{{ $errorsBag->first('email') }}</div>@endif
                </div>

                <div class="col-md-6">
                  <label class="form-label-djama" for="engagement-phone">Téléphone <span class="optional">(facultatif)</span></label>
                  <input type="tel" name="phone" id="engagement-phone" class="form-control @if ($errorsBag->has('phone')) is-invalid @endif"
                    value="{{ old('phone') }}" autocomplete="tel" maxlength="20">
                  @if ($errorsBag->has('phone'))<div class="invalid-feedback">{{ $errorsBag->first('phone') }}</div>@endif
                </div>

                <div class="col-md-6" id="engagement-amount-field">
                  <label class="form-label-djama" for="engagement-amount">Montant du don en FCFA <span class="optional">(facultatif)</span></label>
                  <input type="number" name="amount" id="engagement-amount" class="form-control @if ($errorsBag->has('amount')) is-invalid @endif"
                    value="{{ old('amount') }}" min="0" step="1" inputmode="numeric">
                  @if ($errorsBag->has('amount'))<div class="invalid-feedback">{{ $errorsBag->first('amount') }}</div>@endif
                </div>

                <div class="col-12">
                  <label class="form-label-djama" for="engagement-message">Message <span class="optional">(facultatif)</span></label>
                  <textarea name="message" id="engagement-message" class="form-control" rows="4" maxlength="5000">{{ old('message') }}</textarea>
                </div>

                <div class="col-12">
                  <button type="submit" class="btn btn-don btn-submit w-100">
                    <i class="bi bi-heart-fill me-2" aria-hidden="true"></i>Envoyer mon engagement
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </section>
@endsection
