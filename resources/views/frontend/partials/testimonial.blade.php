{{-- Carte témoignage. Paramètres : $temoignage, $loop du foreach appelant (couleur de l'avatar) --}}
@php $avatarColors = ['var(--djama-blue)', 'var(--djama-green)', 'var(--djama-orange)']; @endphp
<figure class="temoignage-card">
  <div class="temoignage-quote" aria-hidden="true">“</div>
  <blockquote class="temoignage-text">{{ $temoignage->content }}</blockquote>
  <figcaption class="d-flex align-items-center gap-3">
    @if ($temoignage->photo)
    <img class="temoignage-avatar" src="{{ asset('storage/' . $temoignage->photo) }}" alt="" width="44" height="44" loading="lazy">
    @else
    <div class="temoignage-avatar" style="background:{{ $avatarColors[$loop->index % 3] }};" aria-hidden="true">{{ $temoignage->initials }}</div>
    @endif
    <div>
      <div class="temoignage-name">{{ $temoignage->name }}</div>
      @if ($temoignage->role) <div class="temoignage-role">{{ $temoignage->role }}</div> @endif
    </div>
  </figcaption>
</figure>
