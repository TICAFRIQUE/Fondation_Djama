<div class="row">
    <div class="col-xxl-6">
        <div class="card">
            <!-- Modal Créer Programme -->
            <div id="addModal" class="modal fade" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true"
                style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Créer un nouveau programme</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3" method="post" action="{{ route('programmes.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="col-md-12">
                                    <label for="title" class="form-label">Titre du programme</label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                        id="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                        id="description" rows="3" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                                        id="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="color_bg" class="form-label">Couleur fond</label>
                                    <input type="color" name="color_bg" class="form-control form-control-color" 
                                        id="color_bg" value="{{ old('color_bg', '#1F4E79') }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="color_text" class="form-label">Couleur texte</label>
                                    <input type="color" name="color_text" class="form-control form-control-color" 
                                        id="color_text" value="{{ old('color_text', '#ffffff') }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="order" class="form-label">Ordre</label>
                                    <input type="number" name="order" class="form-control" id="order" min="0"
                                        placeholder="Automatique">
                                </div>

                                <div class="col-md-6">
                                    <label for="is_active" class="form-label">Statut</label>
                                    <select name="is_active" class="form-control" id="is_active">
                                        <option value="1">Actif</option>
                                        <option value="0">Inactif</option>
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary">Créer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
