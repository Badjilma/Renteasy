@extends('ownersite.layout')
@section('title', 'Liste de toutes les propriétés')
@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mes propriétés</h1>
        <a href="{{ route('properties.form') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Ajouter une propriété
        </a>
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

    <!-- Affichage des propriétés -->
    <div class="row" id="properties-container">
        @if($properties->isEmpty())
        <div class="col-12 text-center py-5" id="no-properties">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <i class="fas fa-home fa-4x text-gray-300 mb-3"></i>
                    <h4 class="text-gray-800">Vous n'avez pas encore de propriétés</h4>
                    <p class="text-gray-600">Commencez par ajouter votre première propriété dès maintenant.</p>
                    <a href="{{ route('properties.form') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter une propriété
                    </a>
                </div>
            </div>
        </div>
        @else
            @foreach($properties as $property)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="property-image-container">
                        <img class="card-img-top property-image" src="{{ asset('storage/'.$property->principal_photo) }}" alt="Photo de la propriété">
                        <div class="availability-badge badge {{ $property->availability ? 'badge-success' : 'badge-danger' }}">
                            {{ $property->availability ? 'Disponible' : 'Occupée' }}
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title property-name font-weight-bold text-primary">{{ $property->name }}</h5>
                        <p class="card-text property-address text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2"></i> {{ $property->address }}
                        </p>
                        <div class="property-description text-gray-700 mb-3">
                            {{ Str::limit($property->description, 100) }}
                        </div>

                        <div class="rules-container mb-3">
                            <h6 class="font-weight-bold text-gray-800">
                                <i class="fas fa-scroll mr-1"></i> Règles
                            </h6>
                            <ul class="rules-list">
                                @forelse($property->rules as $rule)
                                    <li>{{ $rule->title }}</li>
                                @empty
                                    <li class="text-muted">Aucune règle définie</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="rooms-info mb-3">
                            <h6 class="font-weight-bold text-gray-800">
                                <i class="fas fa-door-open mr-1"></i> Chambres:
                            </h6>
                            <span class="rooms-count badge badge-info">{{ $property->rooms->count() }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="btn-group w-100">
                            <a href="{{ route('properties.show', $property->id) }}" class="btn btn-info btn-sm view-property">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                            <a href="{{ route('properties.edit', $property->id) }}" class="btn btn-primary btn-sm edit-property">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <button class="btn btn-danger btn-sm delete-property" data-toggle="modal" data-target="#deletePropertyModal" data-id="{{ $property->id }}" data-name="{{ $property->name }}">
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
                <p class="font-weight-bold" id="deletePropertyName"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form id="deletePropertyForm" method="POST">
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
        $('#deletePropertyModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const name = button.data('name');

            const modal = $(this);
            modal.find('#deletePropertyName').text(name);
            modal.find('#deletePropertyForm').attr('action', `/properties/${id}`);
        });
    });
</script>

<style>
    .property-image-container {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .property-image {
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

    .rules-list {
        padding-left: 20px;
        margin-bottom: 0;
    }

    .property-description {
        min-height: 60px;
    }
</style>
@endsection
