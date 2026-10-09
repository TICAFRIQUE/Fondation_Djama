{{--
  Carte « façon d'aider ». Le don ouvre la fenêtre des moyens de don ;
  les autres engagements mènent au formulaire de la page « Faire un don ».
--}}
<div class="agir-card">
  <div class="agir-icon" style="background:{{ $agir->color ?: '#FFF3E0' }}" aria-hidden="true">
    {!! $agir->icon !!}
  </div>
  <h3>{{ $agir->title }}</h3>
  <p>{{ $agir->description }}</p>
  @if ($agir->type === 'donation')
  <button type="button" class="btn-agir open-agir-modal" data-title="{{ $agir->title }}">{{ $agir->title }}</button>
  @else
  <a class="btn-agir" href="{{ route('don', ['type' => $agir->type]) }}#engagement-form">{{ $agir->title }}</a>
  @endif
</div>
