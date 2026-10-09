{{-- Menu « Modifier / Supprimer » d'une ligne. $modal = id de la fenêtre de modification, $id = identifiant à supprimer --}}
<div class="dropdown d-inline-block">
    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ri-more-fill align-middle"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li><a type="button" class="dropdown-item edit-item-btn" data-bs-toggle="modal" data-bs-target="#{{ $modal }}"><i
                    class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                Modifier</a></li>
        <li>
            <a href="#" class="dropdown-item remove-item-btn delete" data-id="{{ $id }}">
                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                Supprimer
            </a>
        </li>
    </ul>
</div>
