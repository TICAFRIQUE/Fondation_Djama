@extends('backend.layouts.master')

@section('title')
Galerie
@endsection

@section('css')
    @include('backend.components.datatable-css')
@endsection

@section('content')

@component('backend.components.breadcrumb')
@slot('li_1')
Liste
@endslot
@slot('title')
Galerie
@endslot
@endcomponent

@include('backend.components.form-errors')

<div class="row">
    <div class="col-lg-12">
        <div class="card">

            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title mb-0">Liste des photos et vidéos</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                    Ajouter un média
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Média</th>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Ordre</th>
                                <th>À la une</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($images as $key => $item)
                            <tr id="row_{{ $item->id }}">
                                <td>{{ ++$key }}</td>

                                <td>
                                    @if($item->type == 'video')
                                    <video width="60" style="border-radius:6px;" preload="metadata">
                                        <source src="{{ asset('storage/'.$item->path) }}#t=0.5">
                                    </video>
                                    @else
                                    <img src="{{ asset('storage/'.$item->path) }}" width="60" style="border-radius:6px;" alt="" loading="lazy">
                                    @endif
                                </td>

                                <td>{{ $item->title ?? '-' }}</td>
                                <td>{{ $item->type == 'video' ? 'Vidéo' : 'Photo' }}</td>
                                <td>{{ $item->position }}</td>
                                <td>
                                    @if($item->is_featured)
                                        <span class="badge bg-success">Oui</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>

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

@include('backend.components.crud-modals', [
    'items' => $images,
    'form' => 'backend.pages.galerie.form',
    'route' => 'galerie',
    'createTitle' => 'Ajouter un média',
    'editTitle' => 'Modification du média',
    'files' => true,
])
@endsection

@section('script')
    @include('backend.components.datatable-scripts', ['routeName' => 'galerie'])
@endsection
