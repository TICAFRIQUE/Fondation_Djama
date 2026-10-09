@extends('backend.layouts.master')
@section('title')
    Sections de l'accueil
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            Site
        @endslot
        @slot('title')
            Sections de l'accueil
        @endslot
    @endcomponent

    @include('backend.components.form-errors')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Page d'accueil : ordre, textes et affichage des sections</h5>
                    <a href="{{ route('index') }}" target="_blank" class="btn btn-soft-primary">
                        <i class="ri-external-link-line me-1"></i>Voir le site
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <i class="ri-information-line me-1"></i>
                        Les sections s'affichent sur l'accueil dans l'ordre ci-dessous (du plus petit numéro au plus grand).
                        Une section désactivée, ou sans contenu, n'apparaît pas sur le site.
                        Dans un titre, entourez un mot avec des étoiles pour le mettre en orange : <code>Des vies *transformées*</code>.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Ordre</th>
                                    <th>Section</th>
                                    <th>Sur-titre</th>
                                    <th>Titre</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sections as $item)
                                    <tr>
                                        <td>{{ $item->order }}</td>
                                        <td>
                                            <strong>{{ $item->label }}</strong>
                                            @if ($item->hint)
                                                <div class="text-muted fs-12">{{ $item->hint }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $item->eyebrow ?? '-' }}</td>
                                        <td>{{ $item->title ?? '-' }}</td>
                                        <td>
                                            @if ($item->is_active)
                                                <span class="badge bg-success">Affichée</span>
                                            @else
                                                <span class="badge bg-danger">Masquée</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-soft-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#sectionModal{{ $item->id }}">
                                                <i class="ri-pencil-fill align-bottom me-1"></i> Modifier
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->

    @foreach ($sections as $item)
        <div id="sectionModal{{ $item->id }}" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Section : {{ $item->label }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3 needs-validation" method="post"
                            action="{{ route('sections.update', $item->id) }}" novalidate>
                            @csrf
                            @method('PUT')

                            @if ($item->hint)
                                <div class="col-12">
                                    <div class="alert alert-light border mb-0">{{ $item->hint }}</div>
                                </div>
                            @endif

                            <div class="col-md-12">
                                <label for="eyebrow{{ $item->id }}" class="form-label">Sur-titre</label>
                                <input type="text" name="eyebrow" value="{{ $item->eyebrow }}" class="form-control"
                                    id="eyebrow{{ $item->id }}" maxlength="255">
                            </div>

                            <div class="col-md-12">
                                <label for="title{{ $item->id }}" class="form-label">Titre</label>
                                <input type="text" name="title" value="{{ $item->title }}" class="form-control"
                                    id="title{{ $item->id }}" maxlength="255">
                                <small class="text-muted">*mot* = mot mis en couleur</small>
                            </div>

                            <div class="col-md-12">
                                <label for="subtitle{{ $item->id }}" class="form-label">Texte d'introduction</label>
                                <textarea name="subtitle" class="form-control" id="subtitle{{ $item->id }}" rows="3" maxlength="1000">{{ $item->subtitle }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="order{{ $item->id }}" class="form-label">Ordre d'affichage</label>
                                <input type="number" name="order" value="{{ $item->order }}" class="form-control"
                                    id="order{{ $item->id }}" min="0" required>
                            </div>

                            <div class="col-md-6">
                                <label for="is_active{{ $item->id }}" class="form-label">Statut</label>
                                <select name="is_active" class="form-control" id="is_active{{ $item->id }}">
                                    <option value="1" {{ $item->is_active ? 'selected' : '' }}>Affichée</option>
                                    <option value="0" {{ !$item->is_active ? 'selected' : '' }}>Masquée</option>
                                </select>
                            </div>

                            <div class="modal-footer mt-3">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary">Modifier</button>
                            </div>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endforeach
@endsection
@section('script')
@endsection
