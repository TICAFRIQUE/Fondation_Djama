{{-- Champs d'un témoignage. $item = témoignage à modifier, ou null pour une création --}}
@php $suffix = $item->id ?? 'New'; @endphp

<div class="col-md-6">
    <label for="name{{ $suffix }}" class="form-label">Nom affiché</label>
    <input type="text" name="name" value="{{ $item->name ?? '' }}" class="form-control" id="name{{ $suffix }}"
        maxlength="255" placeholder="Aminata F." required>
</div>

<div class="col-md-6">
    <label for="role{{ $suffix }}" class="form-label">Qualité / lieu</label>
    <input type="text" name="role" value="{{ $item->role ?? '' }}" class="form-control" id="role{{ $suffix }}"
        maxlength="255" placeholder="Élève, 15 ans — Bondoukou">
</div>

<div class="col-md-12">
    <label for="content{{ $suffix }}" class="form-label">Témoignage</label>
    <textarea name="content" class="form-control" id="content{{ $suffix }}" rows="4" maxlength="1000" required>{{ $item->content ?? '' }}</textarea>
</div>

<div class="col-md-12">
    <label for="photo{{ $suffix }}" class="form-label">Photo (facultatif)</label>
    @if ($item->photo ?? null)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $item->photo) }}" alt="" width="56" height="56" class="rounded-circle" style="object-fit:cover;">
        </div>
    @endif
    <input type="file" name="photo" class="form-control" id="photo{{ $suffix }}" accept="image/*">
    <small class="text-muted">Sans photo, les initiales de la personne sont affichées.</small>
</div>

<div class="col-md-6">
    <label for="order{{ $suffix }}" class="form-label">Ordre</label>
    <input type="number" name="order" value="{{ $item->order ?? 0 }}" class="form-control" id="order{{ $suffix }}" min="0">
</div>

<div class="col-md-6">
    <label for="is_active{{ $suffix }}" class="form-label">Statut</label>
    <select name="is_active" class="form-control" id="is_active{{ $suffix }}">
        <option value="1" {{ ($item->is_active ?? true) ? 'selected' : '' }}>Actif</option>
        <option value="0" {{ !($item->is_active ?? true) ? 'selected' : '' }}>Inactif</option>
    </select>
</div>
