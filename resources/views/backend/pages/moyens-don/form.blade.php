{{-- Champs d'un moyen de don. $item = moyen à modifier, ou null pour une création --}}
@php $suffix = $item->id ?? 'New'; @endphp

<div class="col-md-6">
    <label for="label{{ $suffix }}" class="form-label">Libellé</label>
    <input type="text" name="label" value="{{ $item->label ?? '' }}" class="form-control" id="label{{ $suffix }}"
        maxlength="255" placeholder="Numéro Wave" required>
</div>

<div class="col-md-6">
    <label for="value{{ $suffix }}" class="form-label">Numéro / coordonnées</label>
    <input type="text" name="value" value="{{ $item->value ?? '' }}" class="form-control" id="value{{ $suffix }}"
        maxlength="255" placeholder="+225 07 00 00 00 00" required>
    <small class="text-muted">Le visiteur peut le copier d'un clic (sans les espaces).</small>
</div>

<div class="col-md-6">
    <label for="icon{{ $suffix }}" class="form-label">Icône</label>
    <select name="icon" class="form-control" id="icon{{ $suffix }}">
        @foreach (['bi-bank2' => 'Banque (RIB)', 'bi-phone' => 'Mobile money (Wave, Orange Money...)', 'bi-credit-card' => 'Carte bancaire', 'bi-cash-coin' => 'Espèces', 'bi-envelope-paper' => 'Chèque', 'bi-wallet2' => 'Autre'] as $value => $label)
            <option value="{{ $value }}" {{ ($item->icon ?? 'bi-phone') == $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-6">
    <label for="note{{ $suffix }}" class="form-label">Précision (facultatif)</label>
    <input type="text" name="note" value="{{ $item->note ?? '' }}" class="form-control" id="note{{ $suffix }}"
        maxlength="255" placeholder="Titulaire : Fondation Djama Éducation">
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
