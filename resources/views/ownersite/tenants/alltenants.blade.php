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

    <!-- Messages de succès -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Messages d'erreur -->
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

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
                <table class="table table-bordered" id="tenantsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>CNI</th>
                            <th>Chambres louées</th>
                            <th>Contrat</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenants as $tenant)
                        <tr>
                            <td>{{ $tenant->name }}</td>
                            <td>{{ $tenant->phone }}</td>
                            <td>{{ $tenant->cni }}</td>
                            <td>
                                @if($tenant->rooms->count() > 0)
                                    <span class="badge badge-success">{{ $tenant->rooms->count() }}</span>
                                @else
                                    <span class="badge badge-warning">Aucune</span>
                                @endif
                            </td>
                            <td>
                                @if($tenant->contract)
                                    <span class="badge badge-info">Actif</span>
                                @else
                                    <span class="badge badge-secondary">Aucun</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('tenants.show', $tenant->id) }}" class="btn btn-info btn-sm" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tenants.edit', $tenant->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-success btn-sm" title="Assigner une chambre" 
                                        data-toggle="modal" data-target="#assignRoomModal" 
                                        data-tenant-id="{{ $tenant->id }}" 
                                        data-tenant-name="{{ $tenant->name }}">
                                    <i class="fas fa-home"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucun locataire enregistré</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'assignation de chambre -->
<div class="modal fade" id="assignRoomModal" tabindex="-1" role="dialog" aria-labelledby="assignRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignRoomModalLabel">Assigner une chambre</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="assignRoomForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Locataire : <strong id="tenantName"></strong></p>
                    
                    <div class="form-group">
                        <label for="room_id">Sélectionner une chambre</label>
                        <select class="form-control" id="room_id" name="room_id" required>
                            <option value="">-- Choisir une chambre --</option>
                            <!-- Les chambres disponibles seront chargées ici -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="start_date">Date de début</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>

                    <div class="form-group">
                        <label for="end_date">Date de fin</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Assigner la chambre</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tenantsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
            },
            "order": [[0, "asc"]]
        });

        // Charger les chambres disponibles quand le modal s'ouvre
        $('#assignRoomModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var tenantId = button.data('tenant-id');
            var tenantName = button.data('tenant-name');
            
            $('#tenantName').text(tenantName);
            $('#assignRoomForm').attr('action', '/tenants/' + tenantId + '/assign-room');
            
            // Charger les chambres disponibles via AJAX
            $.get('/api/available-rooms', function(rooms) {
                var select = $('#room_id');
                select.empty().append('<option value="">-- Choisir une chambre --</option>');
                rooms.forEach(function(room) {
                    select.append('<option value="' + room.id + '">' + room.name + ' - ' + room.property.name + '</option>');
                });
            }).fail(function() {
                alert('Erreur lors du chargement des chambres disponibles');
            });
        });
    });
</script>
@endsection