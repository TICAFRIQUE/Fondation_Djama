{{-- Champs d'une info flash. $item = info à modifier, ou null pour une création --}}
@php $suffix = $item->id ?? 'New'; @endphp

<div class="col-md-12">
    <label for="message{{ $suffix }}" class="form-label">Message</label>
    <textarea name="message" class="form-control" id="message{{ $suffix }}" rows="2" maxlength="500" required>{{ $item->message ?? '' }}</textarea>
    <small class="text-muted">Une phrase courte : elle s'affiche dans le bandeau en haut de toutes les pages du site.</small>
</div>

<div class="col-md-6">
    <label for="type{{ $suffix }}" class="form-label">Type</label>
    <select name="type" class="form-control" id="type{{ $suffix }}" required>
        @foreach (\App\Models\FlashInfo::TYPES as $value => $label)
            <option value="{{ $value }}" {{ ($item->type ?? 'info') == $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    <small class="text-muted">Information = bleu, Important = orange, Urgent = rouge</small>
</div>

<div class="col-md-6">
    <label for="is_active{{ $suffix }}" class="form-label">Statut</label>
    <select name="is_active" class="form-control" id="is_active{{ $suffix }}">
        <option value="1" {{ ($item->is_active ?? true) ? 'selected' : '' }}>Actif</option>
        <option value="0" {{ !($item->is_active ?? true) ? 'selected' : '' }}>Inactif</option>
    </select>
</div>

<div class="col-md-6">
    <label for="link_text{{ $suffix }}" class="form-label">Texte du lien (facultatif)</label>
    <input type="text" name="link_text" value="{{ $item->link_text ?? '' }}" class="form-control"
        id="link_text{{ $suffix }}" maxlength="255" placeholder="En savoir plus">
</div>

<div class="col-md-6">
    <label for="link_url{{ $suffix }}" class="form-label">Lien (facultatif)</label>
    <input type="text" name="link_url" value="{{ $item->link_url ?? '' }}" class="form-control"
        id="link_url{{ $suffix }}" maxlength="500" placeholder="/faire-un-don">
    <small class="text-muted">Adresse complète (https://...), page du site (/actualites) ou section de l'accueil (agir)</small>
</div>

<div class="col-md-4">
    <label for="starts_at{{ $suffix }}" class="form-label">Début de diffusion</label>
    <input type="datetime-local" name="starts_at" value="{{ optional($item->starts_at ?? null)->format('Y-m-d\TH:i') }}"
        class="form-control" id="starts_at{{ $suffix }}">
    <small class="text-muted">Vide = tout de suite</small>
</div>

<div class="col-md-4">
    <label for="ends_at{{ $suffix }}" class="form-label">Fin de diffusion</label>
    <input type="datetime-local" name="ends_at" value="{{ optional($item->ends_at ?? null)->format('Y-m-d\TH:i') }}"
        class="form-control" id="ends_at{{ $suffix }}">
    <small class="text-muted">Vide = sans limite</small>
</div>

<div class="col-md-4">
    <label for="order{{ $suffix }}" class="form-label">Ordre</label>
    <input type="number" name="order" value="{{ $item->order ?? 0 }}" class="form-control" id="order{{ $suffix }}" min="0">
</div>
