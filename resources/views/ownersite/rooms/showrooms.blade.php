@extends('ownersite.layout')
@section('title', 'Voir la chambre')
@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $room->name }}</h1>
        <div>
            <a href="{{ route('rooms.all', $property->id) }}" class="btn btn-secondary shadow-sm mr-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour aux chambres
            </a>
        </div>
    </div>

    <!-- Messages d'alerte -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Carte principale de la chambre -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Informations de base -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informations générales</h6>
                    <span class="badge {{ $room->is_rented ? 'badge-danger' : 'badge-success' }} availability-badge">
                        {{ $room->is_rented ? 'Occupée' : 'Disponible' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="room-main-image mb-4">
                        <img src="{{ asset('storage/'.$room->principal_photo) }}" alt="Photo principale" class="img-fluid rounded">
                    </div>

                    <div class="room-details">
                        <h5 class="mb-3">Description</h5>
                        <p class="text-gray-700">{{ $room->description }}</p>

                        <h5 class="mb-2 mt-4">Prix</h5>
                        <p class="text-gray-700">
                            <i class="fas fa-money-bill-wave mr-2 text-primary"></i> {{ $room->price }} Fcfa/mois
                        </p>
                    </div>
                </div>
            </div>

            <!-- Photos secondaires -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Galerie photos</h6>
                </div>
                <div class="card-body">
                    @if($room->roomSecondaryPhoto->isEmpty())
                    <p class="text-muted text-center">Aucune photo secondaire disponible</p>
                    @else
                    <div class="row">
                        @foreach($room->roomSecondaryPhoto as $photo)
                        <div class="col-md-4 mb-3">
                            <a href="{{ asset('storage/'.$photo->room_photo) }}" target="_blank" class="secondary-photo-link">
                                <img src="{{ asset('storage/'.$photo->room_photo) }}" alt="Photo secondaire" class="img-fluid rounded secondary-photo">
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Caractéristiques -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list-ul mr-1"></i> Caractéristiques de la chambre
                    </h6>
                </div>
                <div class="card-body">
                    @if($room->roomCaracteristic->isEmpty())
                    <p class="text-muted text-center">Aucune caractéristique définie</p>
                    @else
                    <ul class="caracteristics-list">
                        @foreach($room->roomCaracteristic as $caracteristic)
                        <li class="mb-2">{{ $caracteristic->title }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>

            <!-- Propriété -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-home mr-1"></i> Propriété
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 mr-3">
                            <img src="{{ asset('storage/'.$property->principal_photo) }}" alt="Photo propriété" class="img-fluid rounded" style="width: 70px; height: 70px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $property->name }}</h6>
                            <p class="text-muted mb-0 small">{{ $property->address }}</p>
                        </div>
                    </div>
                    <a href="{{ route('properties.show', $property->id) }}" class="btn btn-outline-primary btn-block">
                        <i class="fas fa-eye mr-1"></i> Voir la propriété
                    </a>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('rooms.edit', ['property' => $property->id, 'room' => $room->id]) }}" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-edit mr-1"></i> Modifier la chambre
                        </a>
                        <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteRoomModal">
                            <i class="fas fa-trash mr-1"></i> Supprimer la chambre
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteRoomModal" tabindex="-1" role="dialog" aria-labelledby="deleteRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteRoomModalLabel">Confirmation de suppression</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette chambre ? Cette action est irréversible.</p>
                <p class="font-weight-bold">{{ $room->name }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form action="{{ route('rooms.delete', ['property' => $property->id, 'room' => $room->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .room-main-image {
        height: 400px;
        overflow: hidden;
        border-radius: 8px;
        text-align: center;
    }

    .room-main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .secondary-photo {
        height: 150px;
        width: 100%;
        object-fit: cover;
        transition: transform 0.2s ease;
    }

    .secondary-photo:hover {
        transform: scale(1.05);
    }

    .availability-badge {
        font-size: 0.9rem;
        padding: 5px 15px;
        border-radius: 20px;
    }

    .caracteristics-list {
        padding-left: 20px;
    }

    .list-group-item {
        border-left: 3px solid #4e73df;
        margin-bottom: 5px;
    }
</style>
@endsection
