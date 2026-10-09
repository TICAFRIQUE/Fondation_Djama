{{-- Champs d'une actualité. $item = article à modifier, ou null pour une création --}}
@php
    $suffix = $item->id ?? 'New';
    $categories = ['Éducation', 'Santé', 'Économie', 'Social'];
    // une catégorie déjà enregistrée hors liste reste proposée
    if ($item && ! in_array($item->category, $categories)) {
        $categories[] = $item->category;
    }
@endphp

<div class="col-md-12">
    <label for="title{{ $suffix }}" class="form-label">Titre</label>
    <input type="text" name="title" value="{{ $item->title ?? '' }}" class="form-control" id="title{{ $suffix }}" maxlength="255" required>
    @if ($item)
        <small class="text-muted">Adresse : {{ route('news.show', $item->slug) }}</small>
    @endif
</div>

<div class="col-md-12">
    <label for="content{{ $suffix }}" class="form-label">Contenu</label>
    <textarea name="content" class="form-control" id="content{{ $suffix }}" rows="8">{{ $item->content ?? '' }}</textarea>
    <small class="text-muted">Les retours à la ligne sont conservés sur le site.</small>
</div>

<div class="col-md-12">
    <label for="image{{ $suffix }}" class="form-label">Image</label>
    @if ($item->image ?? null)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $item->image) }}" alt="" width="120" height="75" style="object-fit:cover;">
        </div>
    @endif
    <input type="file" name="image" class="form-control" id="image{{ $suffix }}" accept="image/*">
    <small class="text-muted">
        Format paysage conseillé. L'image est allégée automatiquement.
        @if ($item) Laisser vide pour garder l'image actuelle. @endif
    </small>
</div>

<div class="col-md-4">
    <label for="category{{ $suffix }}" class="form-label">Rubrique</label>
    <select name="category" class="form-control" id="category{{ $suffix }}" required>
        @foreach ($categories as $category)
            <option value="{{ $category }}" {{ ($item->category ?? '') == $category ? 'selected' : '' }}>{{ $category }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label for="published_at{{ $suffix }}" class="form-label">Date de publication</label>
    <input type="date" name="published_at" value="{{ optional($item->published_at ?? now())->format('Y-m-d') }}" class="form-control"
        id="published_at{{ $suffix }}">
</div>

<div class="col-md-4">
    <label for="reading_time{{ $suffix }}" class="form-label">Temps de lecture (min)</label>
    <input type="number" name="reading_time" value="{{ $item->reading_time ?? 3 }}" class="form-control" id="reading_time{{ $suffix }}" min="1">
</div>
