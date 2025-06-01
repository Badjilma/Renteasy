@extends('ownersite.layout')
@section('title', 'Détails du locataire')
@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $tenant->name }}</h1>
        <div>
            <a href="{{ route('tenants.edit', $tenant->id) }}" class="d-none d-sm-inline-block btn btn-warning shadow-sm mr-2">
                <i class="fas fa-edit fa-sm text-white-50"></i> Modifier
            </a>
            <a href="{{ route('tenants.all') }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour à la liste
            </a>
        </div>
    </div>

    <!-- Messages -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <!-- Informations du locataire -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations personnelles</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nom complet :</strong> {{ $tenant->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Téléphone :</strong> {{ $tenant->phone }}
                    </div>
                    <div class="mb-3">
                        <strong>CNI :</strong> {{ $tenant->cni }}
                    </div>
                    @if($tenant->email)
                    <div class="mb-3">
                        <strong>Email :</strong> {{ $tenant->email }}
                    </div>
                    @endif
                    @if($tenant->address)
                    <div class="mb-3">
                        <strong>Adresse :</strong> {{ $tenant->address }}
                    </div>
                    @endif
                    @if($tenant->emergency_contact)
                    <div class="mb-3">
                        <strong>Contact d'urgence :</strong> {{ $tenant->emergency_contact }}
                        @if($tenant->emergency_phone)
                            ({{ $tenant->emergency_phone }})
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Chambres louées -->
        <div class="col-xl-8 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Chambres louées</h6>
                </div>
                <div class="card-body">
                    @if($tenant->rooms->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Propriété</th>
                                    <th>Chambre</th>
                                    <th>Date début</th>
                                    <th>Date fin</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tenant->rooms as $room)
                                <tr>
                                    <td>{{ $room->property->name }}</td>
                                    <td>{{ $room->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($room->pivot->start_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($room->pivot->end_date)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($room->pivot->status == 'active')
                                            <span class="badge badge-success">Actif</span>
                                        @elseif($room->pivot->status == 'terminated')
                                            <span class="badge badge-danger">Terminé</span>
                                        @else
                                            <span class="badge badge-warning">{{ $room->pivot->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p class="text-muted">Ce locataire n'a pas encore de chambre assignée</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Assignation de chambre -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Assigner une chambre</h6>
        </div>
        <div class="card-body">
            <form id="assignRoomForm" action="#" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="property_id">Sélectionner une propriété</label>
                            <select class="form-control" id="property_id" name="property_id" required>
                                <option value="">-- Choisir une propriété --</option>
                                <!-- Les propriétés seront chargées dynamiquement -->
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="room_id">Sélectionner une chambre</label>
                            <select class="form-control" id="room_id" name="room_id" required disabled>
                                <option value="">-- Choisir une chambre --</option>
                                <!-- Les chambres seront chargées dynamiquement -->
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Date de début</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">Date de fin</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary" disabled id="assignButton">
                        <i class="fas fa-link mr-2"></i> Assigner la chambre
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Contrats et maintenance (optionnel, à développer plus tard) -->
    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Contrat</h6>
                </div>
                <div class="card-body">
                    @if($tenant->contract)
                        <!-- Afficher les détails du contrat -->
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Aucun contrat associé à ce locataire</p>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus mr-1"></i> Créer un contrat
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Demandes de maintenance</h6>
                </div>
                <div class="card-body">
                    @if($tenant->maintenanceRequests && $tenant->maintenanceRequests->count() > 0)
                        <!-- Afficher les demandes de maintenance -->
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Aucune demande de maintenance</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Charger les propriétés
        $.ajax({
            url: '/api/properties',
            type: 'GET',
            success: function(data) {
                let options = '<option value="">-- Choisir une propriété --</option>';
                data.forEach(function(property) {
                    options += `<option value="${property.id}">${property.name}</option>`;
                });
                $('#property_id').html(options);
            },
            error: function(xhr) {
                console.error('Erreur lors du chargement des propriétés:', xhr);
                alert('Impossible de charger les propriétés. Veuillez réessayer.');
            }
        });

        // Quand une propriété est sélectionnée, charger ses chambres disponibles
        $('#property_id').change(function() {
            const propertyId = $(this).val();
            if (propertyId) {
                $.ajax({
                    url: `/api/properties/${propertyId}/available-rooms`,
                    type: 'GET',
                    success: function(data) {
                        let options = '<option value="">-- Choisir une chambre --</option>';
                        if (data.length === 0) {
                            options = '<option value="">Aucune chambre disponible</option>';
                            $('#room_id').html(options).prop('disabled', true);
                            $('#assignButton').prop('disabled', true);
                        } else {
                            data.forEach(function(room) {
                                options += `<option value="${room.id}">${room.name} - ${room.price} Fcfa/mois</option>`;
                            });
                            $('#room_id').html(options).prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        console.error('Erreur lors du chargement des chambres:', xhr);
                        alert('Impossible de charger les chambres. Veuillez réessayer.');
                    }
                });
            } else {
                $('#room_id').html('<option value="">-- Choisir une chambre --</option>').prop('disabled', true);
                $('#assignButton').prop('disabled', true);
            }
        });

        // Activer le bouton quand une chambre est sélectionnée
        $('#room_id').change(function() {
            const roomId = $(this).val();
            if (roomId) {
                $('#assignButton').prop('disabled', false);
                // Mettre à jour l'action du formulaire
                const formAction = `{{ route('tenants.assign.room', ['tenant' => $tenant->id, 'room' => '']) }}/${roomId}/assign`;
                $('#assignRoomForm').attr('action', formAction);
            } else {
                $('#assignButton').prop('disabled', true);
            }
        });

        // Validation des dates
        $('#start_date').change(function() {
            $('#end_date').val(''); // Réinitialiser la date de fin si la date de début change
        });

        $('#end_date').change(function() {
            const startDate = new Date($('#start_date').val());
            const endDate = new Date($(this).val());

            if (endDate <= startDate) {
                alert('La date de fin doit être postérieure à la date de début');
                $(this).val('');
            }
        });
    });
</script>
@endsection
