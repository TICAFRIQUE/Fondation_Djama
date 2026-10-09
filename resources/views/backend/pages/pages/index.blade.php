@extends('backend.layouts.master')
@section('title')
    Pages
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
            Pages
        @endslot
    @endcomponent

    @include('backend.components.form-errors')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Pages libres (mentions légales, confidentialité...)</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Créer une page</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Titre</th>
                                    <th>Adresse</th>
                                    <th>Pied de page</th>
                                    <th>Ordre</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pages as $key => $item)
                                    <tr id="row_{{ $item->id }}">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            @if ($item->is_active)
                                                <a href="{{ route('page.show', $item->slug) }}" target="_blank">/page/{{ $item->slug }} <i class="ri-external-link-line"></i></a>
                                            @else
                                                <span class="text-muted">/page/{{ $item->slug }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->show_in_footer ? 'Oui' : 'Non' }}</td>
                                        <td>{{ $item->order }}</td>
                                        <td>
                                            @if ($item->is_active)
                                                <span class="badge bg-success">Publiée</span>
                                            @elseif (blank($item->content))
                                                <span class="badge bg-warning">Brouillon à rédiger</span>
                                            @else
                                                <span class="badge bg-secondary">Brouillon</span>
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
        'items' => $pages,
        'form' => 'backend.pages.pages.form',
        'route' => 'pages',
        'createTitle' => 'Créer une page',
        'editTitle' => 'Modification de la page',
    ])
@endsection
@section('script')
    @include('backend.components.datatable-scripts', ['routeName' => 'pages'])
@endsection
