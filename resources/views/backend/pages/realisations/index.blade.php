@extends('backend.layouts.master')

@section('title')
Réalisations
@endsection

@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')

@component('backend.components.breadcrumb')
@slot('li_1') Gestion @endslot
@slot('title') Réalisations @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Liste des réalisations</h5>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                    Ajouter
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Titre</th>
                                <th>Période</th>
                                <th>Ordre</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody id="sortable">
                            @foreach($realisations as $key => $item)
                            <tr class="sortable-item" data-id="{{ $item->id }}">
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <img src="{{ asset('storage/'.$item->image) }}" width="60" style="border-radius:6px">
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->date_start)->format('Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($item->date_end)->format('Y') }}
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $item->order }}</span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" data-bs-toggle="dropdown">
                                            <i class="ri-more-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                                    <i class="ri-pencil-fill me-2"></i> Modifier
                                                </a>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('realisations.destroy',$item->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item text-danger" type="submit">
                                                        <i class="ri-delete-bin-fill me-2"></i> Supprimer
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
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

{{-- MODALS SECTION --}}

{{-- MODAL ADD --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('realisations.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une réalisation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="title" class="form-control mb-2" placeholder="Titre" required>
                    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
                    <label>Date de début</label>
                    <input type="date" name="date_start" class="form-control mb-2" required>
                    <label>Date de fin</label>
                    <input type="date" name="date_end" class="form-control mb-2" required>
                    <label>Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- BOUCLE POUR LES MODALS EDIT --}}
@foreach($realisations as $item)
<div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('realisations.update', $item->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Modifier : {{ $item->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label>Titre</label>
                    <input type="text" name="title" value="{{ $item->title }}" class="form-control mb-2" required>

                    <label>Description</label>
                    <textarea name="description" class="form-control mb-2">{{ $item->description }}</textarea>

                    <label>Date de début</label>
                    <input type="date" name="date_start" value="{{ $item->date_start }}" class="form-control mb-2" required>

                    <label>Date de fin</label>
                    <input type="date" name="date_end" value="{{ $item->date_end }}" class="form-control mb-2" required>

                    <label>Image (laisser vide pour conserver l'actuelle)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">

                    <div class="mt-2">
                        <small>Image actuelle :</small><br>
                        <img src="{{ asset('storage/'.$item->image) }}" width="100" class="mt-1" style="border-radius:6px">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs"></script>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json' // Optionnel: pour avoir DataTables en Français
            }
        });
    });

    new Sortable(document.getElementById('sortable'), {
        animation: 150,
        handle: '.sortable-item', // Permet de glisser toute la ligne
        onEnd: function() {
            let order = [];
            document.querySelectorAll('.sortable-item').forEach(item => {
                order.push(item.dataset.id);
            });

            fetch("{{ route('realisations.reorder') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        order
                    })
                })
                .then(response => {
                    if (response.ok) {
                        // Optionnel : afficher un message de succès
                        console.log('Ordre mis à jour');
                    }
                });
        }
    });
</script>
@endsection