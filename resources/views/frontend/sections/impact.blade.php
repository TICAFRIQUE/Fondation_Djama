{{-- ════ IMPACT ════ (Admin > Impacts) --}}
{{-- sans titre, la carte des chiffres chevauche le bas du slider (classe impact-compact) --}}
<section class="impact-section section-pad-sm {{ $section->eyebrow || $section->title ? '' : 'impact-compact' }}" aria-label="Notre impact en chiffres">
  <div class="container">
    @if ($section->eyebrow || $section->title)
    <div class="text-center mb-4">
      @include('frontend.partials.section-head', ['center' => true])
    </div>
    @endif

    <ul class="impact-grid">
      @foreach ($impacts as $impact)
      @php
        // « +500 » ou « 95 % » : le nombre est animé, le préfixe et le suffixe sont conservés
        preg_match('/^(\D*)(\d[\d\s.,]*)?(.*)$/us', trim($impact->value), $parts);
        $number = preg_replace('/\D/', '', $parts[2] ?? '');
      @endphp
      <li>
        <div class="impact-num" @if ($number !== '') data-counter="{{ $number }}" data-prefix="{{ $parts[1] }}" data-suffix="{{ $parts[3] }}" @endif>{{ $impact->value }}</div>
        <div class="impact-label">{!! nl2br(e($impact->label)) !!}</div>
      </li>
      @endforeach
    </ul>
  </div>
</section>
