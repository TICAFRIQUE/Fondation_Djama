{{-- Les deux chiffres affichés sur la photo « À propos » (Admin > À propos) --}}
<div class="apropos-img-badge">
  <div class="d-flex gap-3">
    @if ($apropos->stat_1_value)
    <div>
      <div class="num">{{ $apropos->stat_1_value }}</div>
      <div class="lbl">{{ $apropos->stat_1_label }}</div>
    </div>
    @endif
    @if ($apropos->stat_1_value && $apropos->stat_2_value) <div class="apropos-img-badge-sep"></div> @endif
    @if ($apropos->stat_2_value)
    <div>
      <div class="num">{{ $apropos->stat_2_value }}</div>
      <div class="lbl">{{ $apropos->stat_2_label }}</div>
    </div>
    @endif
  </div>
</div>
