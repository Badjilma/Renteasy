@extends('ownersite.layout')
@section('title', 'Chambres de la propriété')
@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chambres de "{{ $property->name }}"</h1>
        <div>
            <a href="{{ route('properties.show', $property->id) }}" class="btn btn-secondary shadow-sm mr-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour à la propriété
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

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Affichage des chambres -->
    <div class="row" id="rooms-container">
        @if($rooms->isEmpty())
        <div class="col-12 text-center py-5" id="no-rooms">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <i class="fas fa-door-open fa-4x text-gray-300 mb-3"></i>
                    <h4 class="text-gray-800">Cette propriété n'a pas encore de chambres</h4>
                    <p class="text-gray-600">Commencez par ajouter votre première chambre dès maintenant.</p>
                    <a href="{{ route('rooms.create', $property->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter une chambre
                    </a>
                </div>
            </div>
        </div>
        @else
            @foreach($rooms as $room)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="room-image-container">
                        <img class="card-img-top room-image" src="{{ asset('storage/'.$room->principal_photo) }}" alt="Photo de la chambre">
                        <div class="availability-badge badge {{ $room->is_rented ? 'badge-danger' : 'badge-success' }}">
                            {{ $room->is_rented ? 'Occupée' : 'Disponible' }}
                        </div>
                        <div class="price-badge">
                            {{ $room->price }} Fcfa/mois
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title room-name font-weight-bold text-primary">{{ $room->name }}</h5>
                        <div class="room-description text-gray-700 mb-3">
                            {{ Str::limit($room->description, 100) }}
                        </div>

                        <div class="caracteristiques-container mb-3">
                            <h6 class="font-weight-bold text-gray-800">
                                <i class="fas fa-list-ul mr-1"></i> Caractéristiques
                            </h6>
                            <ul class="caracteristics-list">
                        @foreach($room->roomCaracteristic as $caracteristic)
                        <li class="mb-2">{{ $caracteristic->title }}</li>
                        @endforeach
                    </ul>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="btn-group w-100">
                            <a href="{{ route('rooms.show', [$property->id, $room->id]) }}" class="btn btn-info btn-sm view-room">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                            <a href="{{ route('rooms.edit', [$property->id, $room->id]) }}" class="btn btn-primary btn-sm edit-room">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <button class="btn btn-danger btn-sm delete-room" data-toggle="modal" data-target="#deleteRoomModal" data-id="{{ $room->id }}" data-name="{{ $room->name }}">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
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
                <p class="font-weight-bold" id="deleteRoomName"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form id="deleteRoomForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la modal de suppression
        $('#deleteRoomModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const name = button.data('name');

            const modal = $(this);
            modal.find('#deleteRoomName').text(name);
            const propertyId = {{ $property->id }};
            modal.find('#deleteRoomForm').attr('action', `/properties/${propertyId}/rooms/${id}`);
        });
    });
</script>

<style>
    .room-image-container {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .room-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .availability-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        border-radius: 20px;
        color: white;
        font-size: 0.8rem;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .price-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        padding: 5px 10px;
        background-color: #2c3e50;
        color: white;
        font-weight: bold;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .caracteristiques-list {
        padding-left: 20px;
        margin-bottom: 0;
    }

    .room-description {
        min-height: 60px;
    }
</style>
@endsection
