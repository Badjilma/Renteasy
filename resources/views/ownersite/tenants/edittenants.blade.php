@extends('ownersite.layout')
@section('title', 'Modification du locataire')
@section('content')
    <div class="container-fluid">
        <!-- En-tête de page -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Modifier le locataire</h1>
            <div>
                <a href="{{ route('tenants.show', $tenant->id) }}" class="btn btn-info shadow-sm">
                    <i class="fas fa-eye fa-sm text-white-50"></i> Voir fiche
                </a>
                <a href="{{ route('tenants.all') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour
                </a>
            </div>
        </div>

        <!-- Messages d'erreur -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i> Veuillez corriger les erreurs suivantes :
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <!-- Informations personnelles -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user mr-2"></i>Informations personnelles
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tenants.update', $tenant->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Nom complet <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $tenant->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Numéro de téléphone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ old('phone', $tenant->phone) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="cni">Numéro CNI <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cni" name="cni"
                                    value="{{ old('cni', $tenant->cni) }}" required>
                                <small class="form-text text-muted">Numéro de la Carte Nationale d'Identité</small>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i> Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Gestion des locations -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-home mr-2"></i>Locations actuelles
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addRentalModal">
                            <i class="fas fa-plus"></i> Ajouter
                        </button>
                    </div>
                    <div class="card-body">
                        @if($tenant->rooms->where('pivot.status', 'active')->count() > 0)
                            @foreach($tenant->rooms->where('pivot.status', 'active') as $room)
                                <div class="card mb-3 border-left-success">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="font-weight-bold text-primary mb-1">
                                                    {{ $room->name }} - {{ $room->property->name ?? 'Propriété' }}
                                                </h6>
                                                <p class="mb-1">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar"></i>
                                                        Depuis le 
                                                        @if(is_object($room->pivot->start_date))
                                                            {{ $room->pivot->start_date->format('d/m/Y') }}
                                                        @else
                                                            {{ \Carbon\Carbon::parse($room->pivot->start_date)->format('d/m/Y') }}
                                                        @endif
                                                    </small>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="badge badge-success">{{ ucfirst($room->pivot->status) }}</span>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <div class="h6 mb-1 text-primary">{{ number_format($room->price, 0, ',', ' ') }} FCFA</div>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="endRental({{ $room->pivot->id }})">
                                                    <i class="fas fa-times"></i> Terminer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-home fa-2x text-gray-300 mb-2"></i>
                                <p class="text-muted">Aucune location active</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Historique des locations terminées -->
                @if($tenant->rooms->where('pivot.status', 'ended')->count() > 0)
                <div class="card shadow mt-3">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-secondary">
                            <i class="fas fa-history mr-2"></i>Historique des locations
                        </h6>
                    </div>
                    <div class="card-body">
                        @foreach($tenant->rooms->where('pivot.status', 'ended') as $room)
                            <div class="card mb-2 border-left-secondary">
                                <div class="card-body py-2">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <small class="font-weight-bold">{{ $room->name }} - {{ $room->property->name ?? 'Propriété' }}</small>
                                            <br>
                                            <small class="text-muted">
                                                Du 
                                                @if(is_object($room->pivot->start_date))
                                                    {{ $room->pivot->start_date->format('d/m/Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($room->pivot->start_date)->format('d/m/Y') }}
                                                @endif
                                                au 
                                                @if($room->pivot->end_date)
                                                    @if(is_object($room->pivot->end_date))
                                                        {{ $room->pivot->end_date->format('d/m/Y') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($room->pivot->end_date)->format('d/m/Y') }}
                                                    @endif
                                                @endif
                                            </small>
                                        </div>
                                        <span class="badge badge-secondary">Terminé</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal pour ajouter une location -->
    <div class="modal fade" id="addRentalModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-home mr-2"></i>Affecter une nouvelle chambre
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('tenants.assign-room', $tenant->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="room_id">Chambre disponible <span class="text-danger">*</span></label>
                            <select class="form-control" id="room_id" name="room_id" required>
                                <option value="">Sélectionnez une chambre</option>
                                @foreach ($availableRooms as $room)
                                    <option value="{{ $room->id }}">
                                        {{ $room->property->name ?? 'Propriété' }} - 
                                        Chambre {{ $room->name }} - 
                                        {{ number_format($room->price, 0, ',', ' ') }} FCFA
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="start_date">Date de début <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="end_date">Date de fin (optionnel)</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                            <small class="form-text text-muted">Laisser vide pour une durée indéterminée</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-2"></i>Affecter la chambre
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation pour terminer une location -->
    <div class="modal fade" id="endRentalModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Terminer la location
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="endRentalForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir terminer cette location ?</p>
                        <div class="form-group">
                            <label for="end_date_confirm">Date de fin de location</label>
                            <input type="date" class="form-control" id="end_date_confirm" name="end_date" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-check mr-2"></i>Confirmer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function endRental(pivotId) {
            const form = document.getElementById('endRentalForm');
            form.action = `{{ route('tenants.end-rental', '') }}/${pivotId}`;
            $('#endRentalModal').modal('show');
        }
    </script>
@endsection