@extends('ownersite.layout')
@section('title', 'Détails du locataire')
@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Fiche du locataire</h1>
        <div>
            <a href="{{ route('tenants.edit', $tenant->id) }}" class="btn btn-warning shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Modifier
            </a>
            <a href="{{ route('tenants.all') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour
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

    <!-- Fiche du locataire -->
    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user mr-2"></i>Informations personnelles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name={{ urlencode($tenant->name) }}&size=150" alt="Photo profil">
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Nom complet</th>
                            <td>{{ $tenant->name }}</td>
                        </tr>
                        <tr>
                            <th>Téléphone</th>
                            <td>{{ $tenant->phone }}</td>
                        </tr>
                        <tr>
                            <th>Numéro CNI</th>
                            <td>{{ $tenant->cni }}</td>
                        </tr>
                        <tr>
                            <th>Date d'enregistrement</th>
                            <td>{{ $tenant->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Historique des locations -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-home mr-2"></i>Historique des locations
                    </h6>
                </div>
                <div class="card-body">
                    @if($tenant->rooms->count() > 0)
                        @foreach($tenant->rooms as $room)
                            <div class="card mb-3 border-left-primary">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 font-weight-bold text-primary mb-1">
                                                {{ $room->name }} - {{ $room->property->name ?? 'Propriété inconnue' }}
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="text-xs font-weight-bold text-primary mb-1">
                                                        <i class="fas fa-calendar-alt mr-1"></i> Période
                                                    </div>
                                                    <div class="mb-2">
                                                        Du <strong>
                                                            @if(is_object($room->pivot->start_date))
                                                                {{ $room->pivot->start_date->format('d/m/Y') }}
                                                            @else
                                                                {{ \Carbon\Carbon::parse($room->pivot->start_date)->format('d/m/Y') }}
                                                            @endif
                                                        </strong>
                                                        @if($room->pivot->end_date)
                                                            au <strong>
                                                                @if(is_object($room->pivot->end_date))
                                                                    {{ $room->pivot->end_date->format('d/m/Y') }}
                                                                @else
                                                                    {{ \Carbon\Carbon::parse($room->pivot->end_date)->format('d/m/Y') }}
                                                                @endif
                                                            </strong>
                                                        @else
                                                            <em>(en cours)</em>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="text-xs font-weight-bold text-primary mb-1">
                                                        <i class="fas fa-info-circle mr-1"></i> Statut
                                                    </div>
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
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <a href="{{ route('rooms.show', ['property' => $room->property_id, 'room' => $room->id]) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-info-circle"></i> Voir la chambre
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($room->price, 0, ',', ' ') }} FCFA
                                            </div>
                                            <small class="text-muted">/mois</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-home fa-3x text-gray-300 mb-3"></i>
                            <p class="h5 text-muted">Ce locataire n'a aucune location enregistrée</p>
                            <a href="{{ route('tenants.edit', $tenant->id) }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus mr-2"></i>Ajouter une location
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Demandes de maintenance -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-tools mr-2"></i>Demandes de maintenance
            </h6>
        </div>
        <div class="card-body">
            @if($tenant->maintenanceRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Chambre</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tenant->maintenanceRequests as $request)
                                <tr>
                                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ Str::limit($request->description, 50) }}</td>
                                    <td>
                                        @if($request->room)
                                            {{ $request->room->name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'in_progress' => 'info',
                                                'completed' => 'success',
                                                'rejected' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $statusColors[$request->status] ?? 'secondary' }}">
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('maintenance.show', $request->id) }}" 
                                           class="btn btn-sm btn-info" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-tools fa-3x text-gray-300 mb-3"></i>
                    <p class="h5 text-muted">Aucune demande de maintenance enregistrée</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection