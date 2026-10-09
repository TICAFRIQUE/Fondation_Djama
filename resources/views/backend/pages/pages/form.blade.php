{{-- Champs d'une page libre. $item = page à modifier, ou null pour une création --}}
@php $suffix = $item->id ?? 'New'; @endphp

<div class="col-md-12">
    <label for="title{{ $suffix }}" class="form-label">Titre de la page</label>
    <input type="text" name="title" value="{{ $item->title ?? '' }}" class="form-control" id="title{{ $suffix }}"
        maxlength="255" placeholder="Mentions légales" required>
    @if ($item)
        <small class="text-muted">Adresse : {{ route('page.show', $item->slug) }}</small>
    @endif
</div>

<div class="col-md-12">
    <label for="content{{ $suffix }}" class="form-label">Contenu</label>
    <textarea name="content" class="form-control" id="content{{ $suffix }}" rows="12">{{ $item->content ?? '' }}</textarea>
    <small class="text-muted">Texte simple (les retours à la ligne sont conservés) ou HTML : &lt;h2&gt;Titre&lt;/h2&gt;, &lt;p&gt;, &lt;ul&gt;&lt;li&gt;, &lt;a href=""&gt;...</small>
</div>

<div class="col-md-12">
    <label for="meta_description{{ $suffix }}" class="form-label">Description pour Google (facultatif)</label>
    <input type="text" name="meta_description" value="{{ $item->meta_description ?? '' }}" class="form-control"
        id="meta_description{{ $suffix }}" maxlength="300">
    <small class="text-muted">150 à 160 caractères conseillés. Vide = début du contenu</small>
</div>

<div class="col-md-4">
    <label for="is_active{{ $suffix }}" class="form-label">Statut</label>
    <select name="is_active" class="form-control" id="is_active{{ $suffix }}">
        <option value="1" {{ ($item->is_active ?? false) ? 'selected' : '' }}>Publiée</option>
        <option value="0" {{ !($item->is_active ?? false) ? 'selected' : '' }}>Brouillon</option>
    </select>
</div>

<div class="col-md-4">
    <label for="show_in_footer{{ $suffix }}" class="form-label">Lien dans le pied de page</label>
    <select name="show_in_footer" class="form-control" id="show_in_footer{{ $suffix }}">
        <option value="1" {{ ($item->show_in_footer ?? true) ? 'selected' : '' }}>Oui</option>
        <option value="0" {{ !($item->show_in_footer ?? true) ? 'selected' : '' }}>Non</option>
    </select>
</div>

<div class="col-md-4">
    <label for="order{{ $suffix }}" class="form-label">Ordre</label>
    <input type="number" name="order" value="{{ $item->order ?? 0 }}" class="form-control" id="order{{ $suffix }}" min="0">
</div>
