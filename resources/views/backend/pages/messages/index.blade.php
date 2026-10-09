@extends('backend.layouts.master')

@section('title')
Messages
@endsection

@section('content')

@component('backend.components.breadcrumb')
@slot('li_1') Communication @endslot
@slot('title') Messages reçus @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">

        <div class="card">

            <div class="card-header">
                <h5 class="card-title mb-0">Liste des messages</h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered align-middle">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Objet</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($messages as $key => $msg)
                            <tr>

                                <td>{{ $key + 1 }}</td>

                                <td>{{ $msg->name }}</td>

                                <td><a href="mailto:{{ $msg->email }}">{{ $msg->email }}</a></td>

                                <td>{{ $msg->subject }}</td>

                                <td style="max-width:250px;">
                                    {{ \Illuminate\Support\Str::limit($msg->message, 80) }}
                                </td>

                                <td>{{ $msg->created_at->format('d/m/Y H:i') }}</td>

                                <td class="text-nowrap">

                                    <!-- VOIR le message en entier -->
                                    <button type="button" class="btn btn-soft-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#messageModal{{ $msg->id }}" aria-label="Lire le message">
                                        <i class="ri-eye-line"></i>
                                    </button>

                                    <!-- DELETE (confirmation gérée par adm/js/admin.js) -->
                                    <form action="{{ route('messages.destroy', $msg->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-soft-danger btn-sm" aria-label="Supprimer le message">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>

                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Aucun message pour le moment.</td>
                            </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
</div>

@foreach($messages as $msg)
<div class="modal fade" id="messageModal{{ $msg->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $msg->subject }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">
                    De <strong>{{ $msg->name }}</strong> ({{ $msg->email }}) — {{ $msg->created_at->format('d/m/Y H:i') }}
                </p>
                <p class="mb-0">{!! nl2br(e($msg->message)) !!}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                <a href="mailto:{{ $msg->email }}?subject={{ rawurlencode('Re: ' . $msg->subject) }}" class="btn btn-primary">
                    <i class="ri-reply-line me-1"></i> Répondre par email
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
