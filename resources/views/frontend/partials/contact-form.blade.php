{{-- Formulaire de contact (accueil et page Contact). Les messages arrivent dans Admin > Messages. --}}
@php $errorsBag = $errors->getBag('contact'); @endphp
<div class="form-card" id="contact-form">
  @if (session('success'))
  <div class="site-alert site-alert-success" role="status"><i class="bi bi-check-circle-fill" aria-hidden="true"></i> {{ session('success') }}</div>
  @endif
  @if ($errorsBag->any())
  <div class="site-alert site-alert-error" role="alert"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i> Merci de vérifier les champs signalés.</div>
  @endif

  <form action="{{ route('contact.store') }}" method="POST">
    @csrf
    {{-- champ piège anti-robots, invisible pour les visiteurs --}}
    <div class="honeypot" aria-hidden="true">
      <label for="contact-website">Ne pas remplir</label>
      <input type="text" name="website" id="contact-website" tabindex="-1" autocomplete="off">
    </div>

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label-djama" for="contact-name">Nom complet</label>
        <input type="text" name="name" id="contact-name" class="form-control @if ($errorsBag->has('name')) is-invalid @endif"
          value="{{ old('name') }}" autocomplete="name" required />
        @if ($errorsBag->has('name'))<div class="invalid-feedback">{{ $errorsBag->first('name') }}</div>@endif
      </div>

      <div class="col-md-6">
        <label class="form-label-djama" for="contact-email">Email</label>
        <input type="email" name="email" id="contact-email" class="form-control @if ($errorsBag->has('email')) is-invalid @endif"
          value="{{ old('email') }}" autocomplete="email" required />
        @if ($errorsBag->has('email'))<div class="invalid-feedback">{{ $errorsBag->first('email') }}</div>@endif
      </div>

      <div class="col-12">
        <label class="form-label-djama" for="contact-subject">Objet</label>
        <select name="subject" id="contact-subject" class="form-select" required>
          @foreach (config('site.contact_subjects') as $subject)
          <option value="{{ $subject }}" @selected(old('subject') === $subject)>{{ $subject }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-12">
        <label class="form-label-djama" for="contact-message">Message</label>
        <textarea name="message" id="contact-message" class="form-control @if ($errorsBag->has('message')) is-invalid @endif"
          rows="4" maxlength="5000" required>{{ old('message') }}</textarea>
        @if ($errorsBag->has('message'))<div class="invalid-feedback">{{ $errorsBag->first('message') }}</div>@endif
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-don btn-submit w-100">
          <i class="bi bi-send-fill me-2" aria-hidden="true"></i>Envoyer le message
        </button>
      </div>
    </div>
  </form>
</div>
