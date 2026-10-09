{{-- Moyens de don gérés depuis l'admin (Site > Moyens de don) --}}
@forelse ($site->paymentMethods() as $method)
<div class="don-info-block">
  <div class="don-info-label"><i class="bi {{ $method->icon ?: 'bi-wallet2' }}" aria-hidden="true"></i> {{ $method->label }}</div>
  <div class="don-info-value">
    <span>{{ $method->value }}</span>
    <button type="button" class="don-copy-btn" data-copy="{{ $method->copy_value }}" aria-label="Copier : {{ $method->label }}">
      <i class="bi bi-clipboard" aria-hidden="true"></i>
    </button>
  </div>
  @if ($method->note)
  <div class="don-info-extra">{{ $method->note }}</div>
  @endif
</div>
@empty
<p class="don-info-note mb-0">Pour faire un don, contactez-nous : nous vous indiquerons la marche à suivre.</p>
@endforelse
