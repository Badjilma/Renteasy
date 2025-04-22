
@extends('ownersite.layout')
@section('title', 'Voir la propriété')
@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $property->name }}</h1>
        <div>
            <a href="{{ route('properties.all') }}" class="btn btn-secondary shadow-sm mr-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour
            </a>
            {{-- <a href="{{ route('properties.edit', $property->id) }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Modifier
            </a> --}}
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

    <!-- Carte principale de la propriété -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Informations de base -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informations générales</h6>
                    <span class="badge {{ $property->availability ? 'badge-success' : 'badge-danger' }} availability-badge">
                        {{ $property->availability ? 'Disponible' : 'Occupée' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="property-main-image mb-4">
                        <img src="{{ asset('storage/'.$property->principal_photo) }}" alt="Photo principale" class="img-fluid rounded">
                    </div>

                    <div class="property-details">
                        <h5 class="mb-3">Description</h5>
                        <p class="text-gray-700">{{ $property->description }}</p>

                        <h5 class="mb-2 mt-4">Adresse</h5>
                        <p class="text-gray-700">
                            <i class="fas fa-map-marker-alt mr-2 text-primary"></i> {{ $property->address }}
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
                    @if($property->secondaryPhotos->isEmpty())
                    <p class="text-muted text-center">Aucune photo secondaire disponible</p>
                    @else
                    <div class="row">
                        @foreach($property->secondaryPhotos as $photo)
                        <div class="col-md-4 mb-3">
                            <a href="{{ asset('storage/'.$photo->property_photo) }}" target="_blank" class="secondary-photo-link">
                                <img src="{{ asset('storage/'.$photo->property_photo) }}" alt="Photo secondaire" class="img-fluid rounded secondary-photo">
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Règles -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-scroll mr-1"></i> Règles de la propriété
                    </h6>
                </div>
                <div class="card-body">
                    @if($property->rules->isEmpty())
                    <p class="text-muted text-center">Aucune règle définie</p>
                    @else
                    <ul class="rules-list">
                        @foreach($property->rules as $rule)
                        <li class="mb-2">{{ $rule->title }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>

            <!-- Chambres -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-door-open mr-1"></i> Chambres ({{ $property->rooms->count() }})
                    </h6>
                    <a href="{{ route('rooms.create', $property->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Ajouter
                    </a>
                    <a href="{{ route('rooms.all', $property->id) }}" class="btn btn-sm btn-primary">
                         Voir tout
                    </a>
                </div>
                <div class="card-body">
                    @if($property->rooms->isEmpty())
                    <p class="text-muted text-center">Aucune chambre enregistrée</p>
                    @else
                    <div class="list-group">
                        @foreach($property->rooms as $room)
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $room->name }}</h6>
                                <p class="mb-1 small">
                                    <span class="text-primary">{{ $room->price }} €/mois</span> -
                                    <span class="text-muted">{{ $room->size }} m²</span>
                                </p>
                            </div>
                            <span class="badge {{ $room->occupied ? 'badge-danger' : 'badge-success' }}">
                                {{ $room->occupied ? 'Occupée' : 'Libre' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('properties.edit', $property->id) }}" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-edit mr-1"></i> Modifier la propriété
                        </a>
                        <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#deletePropertyModal">
                            <i class="fas fa-trash mr-1"></i> Supprimer la propriété
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deletePropertyModal" tabindex="-1" role="dialog" aria-labelledby="deletePropertyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deletePropertyModalLabel">Confirmation de suppression</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette propriété ? Cette action est irréversible.</p>
                <p class="font-weight-bold">{{ $property->name }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form action="{{ route('properties.delete', $property->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .property-main-image {
        height: 400px;
        overflow: hidden;
        border-radius: 8px;
        text-align: center;
    }

    .property-main-image img {
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

    .rules-list {
        padding-left: 20px;
    }

    .list-group-item {
        border-left: 3px solid #4e73df;
        margin-bottom: 5px;
    }
</style>
@endsection
