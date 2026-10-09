{{-- Coordonnées saisies dans Admin > Paramètres > Informations --}}
@php
  $phones = $site->phones();
  $emails = $site->emails();
@endphp
<ul class="contact-list">
  @if ($site->address())
  <li>
    <span class="contact-icon"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i></span>
    <div>
      <strong>Adresse</strong>
      <span>{{ $site->address() }}</span>
      @if ($site->setting('google_maps'))
      <a href="{{ $site->setting('google_maps') }}" target="_blank" rel="noopener">Voir sur la carte</a>
      @endif
    </div>
  </li>
  @endif
  @if ($phones)
  <li>
    <span class="contact-icon"><i class="bi bi-telephone-fill" aria-hidden="true"></i></span>
    <div>
      <strong>Téléphone</strong>
      @foreach ($phones as $phone)
      <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">{{ $phone }}</a>
      @endforeach
    </div>
  </li>
  @endif
  @if ($emails)
  <li>
    <span class="contact-icon"><i class="bi bi-envelope-fill" aria-hidden="true"></i></span>
    <div>
      <strong>Email</strong>
      @foreach ($emails as $email)
      <a href="mailto:{{ $email }}">{{ $email }}</a>
      @endforeach
    </div>
  </li>
  @endif
  @if ($site->whatsappUrl())
  <li>
    <span class="contact-icon"><i class="bi bi-whatsapp" aria-hidden="true"></i></span>
    <div>
      <strong>WhatsApp</strong>
      <a href="{{ $site->whatsappUrl() }}" target="_blank" rel="noopener">Écrire sur WhatsApp</a>
    </div>
  </li>
  @endif
</ul>
