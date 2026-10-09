@extends('backend.layouts.master')
@section('title')
    Témoignages
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
            Témoignages
        @endslot
    @endcomponent

    @include('backend.components.form-errors')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Liste des témoignages</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Créer un témoignage</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>Nom</th>
                                    <th>Qualité / lieu</th>
                                    <th>Témoignage</th>
                                    <th>Ordre</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($temoignages as $key => $item)
                                    <tr id="row_{{ $item->id }}">
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            @if ($item->photo)
                                                <img src="{{ asset('storage/' . $item->photo) }}" alt="" width="40" height="40" class="rounded-circle" style="object-fit:cover;">
                                            @else
                                                <span class="badge bg-secondary">{{ $item->initials }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->role ?? '-' }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($item->content, 80) }}</td>
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
        'items' => $temoignages,
        'form' => 'backend.pages.temoignages.form',
        'route' => 'temoignages',
        'createTitle' => 'Créer un témoignage',
        'editTitle' => 'Modification du témoignage',
        'files' => true,
    ])
@endsection
@section('script')
    @include('backend.components.datatable-scripts', ['routeName' => 'temoignages'])
@endsection
