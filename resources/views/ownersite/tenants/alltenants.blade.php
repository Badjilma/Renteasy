@extends('ownersite.layout')
@section('title', 'Liste des locataires')
@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des locataires</h1>
        <a href="{{ route('tenants.create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Ajouter un locataire
        </a>
    </div>


    <!-- Liste des locataires -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Liste des locataires</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Options :</div>
                    <a class="dropdown-item" href="#" id="exportPDF"><i class="fas fa-file-pdf mr-2 text-danger"></i>Exporter en PDF</a>
                    <a class="dropdown-item" href="#" id="exportExcel"><i class="fas fa-file-excel mr-2 text-success"></i>Exporter en Excel</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tenantsTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nom</th>
                            <th>Contact</th>
                            <th>Chambre(s)</th>
                            <th>Période de location</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenants as $tenant)
                        <tr>
                            <td>
                                <strong>{{ $tenant->name }}</strong><br>
                                <small class="text-muted">CNI: {{ $tenant->cni }}</small>
                            </td>
                            <td>{{ $tenant->phone }}</td>
                            <td>
                                @forelse($tenant->rooms as $room)
                                    <span class="badge badge-primary mb-1">
                                        {{ $room->name }} ({{ $room->property->name ?? 'N/A' }})
                                    </span><br>
                                @empty
                                    <span class="badge badge-warning">Aucune chambre</span>
                                @endforelse
                            </td>
                            <td>
                                @forelse($tenant->rooms as $room)
                                    @php
                                        $startDate = $room->pivot->start_date 
                                            ? \Carbon\Carbon::parse($room->pivot->start_date)->format('d/m/Y') 
                                            : 'N/A';
                                        
                                        $endDate = $room->pivot->end_date 
                                            ? \Carbon\Carbon::parse($room->pivot->end_date)->format('d/m/Y') 
                                            : 'Indéterminée';
                                    @endphp
                                    <small>
                                        <i class="fas fa-calendar-alt text-primary"></i> {{ $startDate }}<br>
                                        <i class="fas fa-calendar-times text-danger"></i> {{ $endDate }}
                                    </small>
                                    @if(!$loop->last)<hr>@endif
                                @empty
                                    <span class="text-muted">N/A</span>
                                @endforelse
                            </td>
                            <td>
                                @forelse($tenant->rooms as $room)
                                    @php
                                        $statusClass = [
                                            'active' => 'success',
                                            'ended' => 'secondary',
                                            'pending' => 'warning'
                                        ][$room->pivot->status] ?? 'info';
                                    @endphp
                                    <span class="badge badge-{{ $statusClass }}">
                                        {{ ucfirst($room->pivot->status) }}
                                    </span>
                                    @if(!$loop->last)<br>@endif
                                @empty
                                    <span class="badge badge-light">Inactif</span>
                                @endforelse
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('tenants.show', $tenant->id) }}" class="btn btn-info btn-sm" title="Détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm delete-tenant" 
                                            data-id="{{ $tenant->id }}" 
                                            data-name="{{ $tenant->name }}"
                                            title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-users-slash fa-2x mb-3 text-muted"></i>
                                <p class="h5 text-muted">Aucun locataire enregistré</p>
                                <a href="{{ route('tenants.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus mr-2"></i>Ajouter un locataire
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
               
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteTenantModal" tabindex="-1" role="dialog" aria-labelledby="deleteTenantModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTenantModalLabel">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le locataire <strong id="tenantNameToDelete"></strong> ?</p>
                <p class="text-danger">Cette action est irréversible et supprimera également toutes les données associées.</p>
            </div>
            <div class="modal-footer">
                <form id="deleteTenantForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Gestion de la suppression
    $('.delete-tenant').click(function() {
        const tenantId = $(this).data('id');
        const tenantName = $(this).data('name');
        
        $('#tenantNameToDelete').text(tenantName);
        $('#deleteTenantForm').attr('action', `/tenants/${tenantId}`);
        $('#deleteTenantModal').modal('show');
    });

    // Initialisation du DataTable
    $('#tenantsTable').DataTable({
        responsive: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
        },
        columnDefs: [
            { orderable: false, targets: [5] }
        ]
    });
});
</script>
@endsection