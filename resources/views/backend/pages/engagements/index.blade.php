@extends('backend.layouts.master')

@section('title', 'Engagements')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    @component('backend.components.breadcrumb')
        @slot('li_1') Communication @endslot
        @slot('title') Engagements reçus @endslot
    @endcomponent

    <!-- CARD -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($engagements as $item)
                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <!-- TYPE -->
                            <td>
                                {{-- les types enregistrés par le site sont ceux des cartes « Agir » (donation, sponsorship...) --}}
                                @switch($item->type)
                                    @case('donation')
                                    @case('don')
                                        <span class="badge bg-warning">💰 Don</span>
                                        @break
                                    @case('sponsorship')
                                    @case('parrainage')
                                        <span class="badge bg-success">👧 Parrainage</span>
                                        @break
                                    @case('volunteer')
                                    @case('benevolat')
                                        <span class="badge bg-info">🤝 Bénévole</span>
                                        @break
                                    @case('partner')
                                    @case('partenariat')
                                        <span class="badge bg-secondary">🏢 Partenaire</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $item->type }}</span>
                                @endswitch
                            </td>

                            <td><strong>{{ $item->name }}</strong></td>

                            <td>{{ $item->email }}</td>

                            <td>{{ $item->phone ?? '-' }}</td>

                            <!-- MONTANT -->
                            <td>
                                @if($item->amount)
                                    <strong>{{ number_format($item->amount, 0, ',', ' ') }} FCFA</strong>
                                @else
                                    -
                                @endif
                            </td>

                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>

                            <!-- ACTIONS -->
                            <td class="text-end">

                                <!-- VOIR -->
                                <button class="btn btn-sm btn-primary view-btn"
                                    data-name="{{ $item->name }}"
                                    data-email="{{ $item->email }}"
                                    data-phone="{{ $item->phone }}"
                                    data-type="{{ $item->type }}"
                                    data-amount="{{ $item->amount }}"
                                    data-message="{{ $item->message }}">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <!-- DELETE -->
                                <form action="{{ route('engagements.destroy', $item->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Aucun engagement pour le moment
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

<!-- ===== MODAL ===== -->
<div class="modal fade" id="engagementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Détails de l'engagement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p><strong>Nom :</strong> <span id="eName"></span></p>
                <p><strong>Email :</strong> <span id="eEmail"></span></p>
                <p><strong>Téléphone :</strong> <span id="ePhone"></span></p>
                <p><strong>Type :</strong> <span id="eType"></span></p>
                <p><strong>Montant :</strong> <span id="eAmount"></span></p>

                <hr>

                <p id="eMessage" style="white-space: pre-line;"></p>

            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const modal = new bootstrap.Modal(document.getElementById('engagementModal'));

    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function () {

            document.getElementById('eName').textContent = this.dataset.name;
            document.getElementById('eEmail').textContent = this.dataset.email;
            document.getElementById('ePhone').textContent = this.dataset.phone ?? '-';
            document.getElementById('eType').textContent = this.dataset.type;
            document.getElementById('eAmount').textContent = this.dataset.amount 
                ? this.dataset.amount + ' FCFA'
                : '-';
            document.getElementById('eMessage').textContent = this.dataset.message ?? 'Aucun message';

            modal.show();
        });
    });
</script>
@endpush