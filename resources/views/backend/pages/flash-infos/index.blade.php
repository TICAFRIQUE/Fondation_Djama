@extends('backend.layouts.master')
@section('title')
    Infos flash
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
            Infos flash
        @endslot
    @endcomponent

    @include('backend.components.form-errors')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Bandeau d'annonces du site</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Créer une info flash</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Message</th>
                                    <th>Type</th>
                                    <th>Diffusion</th>
                                    <th>Ordre</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($flashInfos as $key => $item)
                                    <tr id="row_{{ $item->id }}">
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            {{ $item->message }}
                                            @if ($item->link_url)
                                                <div class="text-muted fs-12"><i class="ri-links-line"></i> {{ $item->link_text ?: 'En savoir plus' }} → {{ $item->link_url }}</div>
                                            @endif
                                        </td>
                                        <td>{{ \App\Models\FlashInfo::TYPES[$item->type] ?? $item->type }}</td>
                                        <td>
                                            {{ $item->starts_at ? 'du ' . $item->starts_at->format('d/m/Y H:i') : 'dès maintenant' }}<br>
                                            {{ $item->ends_at ? 'au ' . $item->ends_at->format('d/m/Y H:i') : 'sans limite' }}
                                        </td>
                                        <td>{{ $item->order }}</td>
                                        <td>
                                            @php
                                                $badge = ['en ligne' => 'bg-success', 'programmée' => 'bg-info', 'expirée' => 'bg-warning', 'désactivée' => 'bg-danger'][$item->status];
                                            @endphp
                                            <span class="badge {{ $badge }}">{{ ucfirst($item->status) }}</span>
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
        'items' => $flashInfos,
        'form' => 'backend.pages.flash-infos.form',
        'route' => 'flash-infos',
        'createTitle' => 'Créer une info flash',
        'editTitle' => "Modification de l'info flash",
    ])
@endsection
@section('script')
    @include('backend.components.datatable-scripts', ['routeName' => 'flash-infos'])
@endsection
