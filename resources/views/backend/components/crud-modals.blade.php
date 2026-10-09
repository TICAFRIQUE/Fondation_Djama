{{--
    Fenêtres de création et de modification d'une liste d'admin.
    $items       : éléments affichés dans le tableau
    $form        : vue des champs, qui reçoit $item (null à la création)
    $route       : préfixe des routes (ex. « flash-infos » pour flash-infos.store / flash-infos.update)
    $createTitle : titre de la fenêtre de création
    $editTitle   : titre de la fenêtre de modification
    $files       : true si le formulaire envoie un fichier
--}}
@php $enctype = ($files ?? false) ? 'enctype=multipart/form-data' : ''; @endphp

<div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $createTitle }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" method="post" action="{{ route($route . '.store') }}" {{ $enctype }} novalidate>
                    @csrf
                    @include($form, ['item' => null])
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@foreach ($items as $item)
    <div id="myModalEdit{{ $item->id }}" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editTitle }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" method="post"
                        action="{{ route($route . '.update', $item->id) }}" {{ $enctype }} novalidate>
                        @csrf
                        @method('PUT')
                        @include($form, ['item' => $item])
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
