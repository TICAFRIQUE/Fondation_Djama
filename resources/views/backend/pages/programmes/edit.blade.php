<!-- Modal Éditer Programme -->
<div id="editModal{{ $prog->id }}" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel{{ $prog->id }}" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $prog->id }}">Modifier le programme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" action="{{ route('programmes.update', $prog) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="col-md-12">
                        <label for="title{{ $prog->id }}" class="form-label">Titre du programme</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                            id="title{{ $prog->id }}" value="{{ old('title', $prog->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="description{{ $prog->id }}" class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                            id="description{{ $prog->id }}" rows="3" required>{{ old('description', $prog->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="image{{ $prog->id }}" class="form-label">Image</label>
                        @if($prog->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$prog->image) }}" alt="{{ $prog->title }}" style="max-width:100px;border-radius:5px;">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                            id="image{{ $prog->id }}" accept="image/*">
                        <small class="text-muted">Laisser vide pour garder l'image actuelle</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="color_bg{{ $prog->id }}" class="form-label">Couleur fond</label>
                        <input type="color" name="color_bg" class="form-control form-control-color" 
                            id="color_bg{{ $prog->id }}" value="{{ old('color_bg', $prog->color_bg) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="color_text{{ $prog->id }}" class="form-label">Couleur texte</label>
                        <input type="color" name="color_text" class="form-control form-control-color" 
                            id="color_text{{ $prog->id }}" value="{{ old('color_text', $prog->color_text) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="order{{ $prog->id }}" class="form-label">Ordre</label>
                        <input type="number" name="order" class="form-control" id="order{{ $prog->id }}" min="0"
                            value="{{ $prog->order }}">
                    </div>

                    <div class="col-md-6">
                        <label for="is_active{{ $prog->id }}" class="form-label">Statut</label>
                        <select name="is_active" class="form-control" id="is_active{{ $prog->id }}">
                            <option value="1" {{ $prog->is_active ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ !$prog->is_active ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
