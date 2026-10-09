{{-- Champs d'un média de la galerie. $item = média à modifier, ou null pour une création --}}
@php $suffix = $item->id ?? 'New'; @endphp

<div class="col-md-12">
    <label for="title{{ $suffix }}" class="form-label">Titre / légende</label>
    <input type="text" name="title" value="{{ $item->title ?? '' }}" class="form-control" id="title{{ $suffix }}"
        maxlength="255" placeholder="Remise de fournitures 2024">
    <small class="text-muted">La légende décrit aussi l'image pour Google et les lecteurs d'écran.</small>
</div>

<div class="col-md-12">
    <label for="media{{ $suffix }}" class="form-label">Photo ou vidéo</label>
    <input type="file" name="media" class="form-control" id="media{{ $suffix }}"
        accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.webm" {{ $item ? '' : 'required' }}>
    <small class="text-muted">
        JPG, PNG, WEBP, MP4, MOV, AVI ou WEBM — 20 Mo maximum. Les photos sont allégées automatiquement.
        @if ($item) Laisser vide pour garder le fichier actuel. @endif
    </small>
</div>

<div class="col-md-6">
    <label for="position{{ $suffix }}" class="form-label">Ordre</label>
    <input type="number" name="position" value="{{ $item->position ?? 0 }}" class="form-control" id="position{{ $suffix }}" min="0">
</div>

<div class="col-md-6">
    <label for="is_featured{{ $suffix }}" class="form-label">À la une sur l'accueil</label>
    <select name="is_featured" class="form-control" id="is_featured{{ $suffix }}">
        <option value="0" {{ !($item->is_featured ?? false) ? 'selected' : '' }}>Non</option>
        <option value="1" {{ ($item->is_featured ?? false) ? 'selected' : '' }}>Oui</option>
    </select>
    <small class="text-muted">L'accueil montre 4 photos : celles « à la une » d'abord.</small>
</div>
