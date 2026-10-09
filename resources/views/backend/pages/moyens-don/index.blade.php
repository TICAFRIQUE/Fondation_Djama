@extends('backend.layouts.master')
@section('title')
    Moyens de don
@endsection
@section('css')
    @include('backend.components.datatable-css')
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            Site
        @endslot
        @slot('title')
            Moyens de don
        @endslot
    @endcomponent

    @include('backend.components.form-errors')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Numéros affichés dans « Faire un don »</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Créer un moyen de don</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Libellé</th>
                                    <th>Numéro / coordonnées</th>
                                    <th>Précision</th>
                                    <th>Ordre</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($moyens as $key => $item)
                                    <tr id="row_{{ $item->id }}">
                                        <td>{{ ++$key }}</td>
                                        <td><i class="bi {{ $item->icon }} me-1"></i> {{ $item->label }}</td>
                                        <td><strong>{{ $item->value }}</strong></td>
                                        <td>{{ $item->note ?? '-' }}</td>
                                        <td>{{ $item->order }}</td>
                                        <td>
                                            @if ($item->is_active)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-danger">Inactif</span>
                                            @endif
                                        </td>
                                        <td>
                                            @include('backend.components.row-actions', ['modal' => 'myModalEdit' . $item->id, 'id' => $item->id])
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

    @include('backend.components.crud-modals', [
        'items' => $moyens,
        'form' => 'backend.pages.moyens-don.form',
        'route' => 'moyens-don',
        'createTitle' => 'Créer un moyen de don',
        'editTitle' => 'Modification du moyen de don',
    ])
@endsection
@section('script')
    @include('backend.components.datatable-scripts', ['routeName' => 'moyens-don'])
@endsection
