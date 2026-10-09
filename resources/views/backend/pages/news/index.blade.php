@extends('backend.layouts.master')

@section('title')
Actualités
@endsection

@section('css')
    @include('backend.components.datatable-css')
@endsection

@section('content')

@component('backend.components.breadcrumb')
@slot('li_1') Contenu @endslot
@slot('title') Actualités @endslot
@endcomponent

@include('backend.components.form-errors')

<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h5 class="card-title mb-0">Liste des actualités</h5>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
            Ajouter une actualité
        </button>
    </div>

    <div class="card-body">
        <div class="table-responsive">

            <table id="buttons-datatables" class="display table table-bordered align-middle" style="width:100%">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Date</th>
                        <th>Lecture</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($news as $key => $item)
                    <tr id="row_{{ $item->id }}">

                        <td>{{ $key+1 }}</td>

                        <td>
                            @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" width="60" height="40" alt="" loading="lazy">
                            @else
                            -
                            @endif
                        </td>

                        <td>
                            {{ $item->title }}
                            <div><a href="{{ route('news.show', $item->slug) }}" target="_blank" class="fs-12">Voir sur le site <i class="ri-external-link-line"></i></a></div>
                        </td>

                        <td>
                            <span class="badge bg-info">{{ $item->category }}</span>
                        </td>

                        <td data-order="{{ optional($item->published_at)->format('Y-m-d') }}">
                            {{ $item->published_at ? $item->published_at->format('d/m/Y') : '-' }}
                        </td>

                        <td>{{ $item->reading_time }} min</td>

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

@include('backend.components.crud-modals', [
    'items' => $news,
    'form' => 'backend.pages.news.form',
    'route' => 'news',
    'createTitle' => 'Nouvel article',
    'editTitle' => "Modification de l'article",
    'files' => true,
])

@endsection

@section('script')
    @include('backend.components.datatable-scripts', ['routeName' => 'news'])
@endsection
