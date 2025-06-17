@extends('layout')
@section('title', 'Demande de Maintenance')
@section('content')

<div class="site-blocks-cover overlay" style="background-image: url('template/images/hero_1.jpg');" data-aos="fade" id="home-section">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-8 mt-lg-5 text-center">
                <h1>Requêtes de maintenance des chambres</h1>
                <h2 class="text-white">Service réservé aux locataires</h2>
                <p class="mb-5 text-white">
                    <i class="icon-info"></i> Ce service est exclusivement destiné aux locataires pour signaler des problèmes concernant leur chambre. Les propriétaires recevront ces requêtes pour traitement.
                </p>
            </div>
        </div>
    </div>
    <a href="#maintenance-form" class="smoothscroll arrow-down"><span class="icon-arrow_downward"></span></a>
</div>

<div class="site-section" id="maintenance-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Succès!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erreur!</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Nouvelle demande de maintenance</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('maintenance.store') }}">
                            @csrf

                           <div class="form-group">
                                <label for="tenant_id">Sélectionnez votre nom *</label>
                                <select name="tenant_id" id="tenant_id" class="form-control @error('tenant_id') is-invalid @enderror" required style="color: #333; background-color: #fff;">
                                    <option value="">-- Choisissez votre nom --</option>
                                    @foreach($tenants as $tenant)
                                        <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                            {{ $tenant->name }} {{ $tenant->phone }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tenant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="property_id">Sélectionnez la propriété *</label>
                                <select name="property_id" id="property_id" class="form-control @error('property_id') is-invalid @enderror" required onchange="loadRooms()">
                                    <option value="">-- Choisissez la propriété --</option>
                                    @foreach($properties as $property)
                                        <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                                            {{ $property->name }} - {{ $property->address }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('property_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="room_id">Sélectionnez la chambre *</label>
                                <select name="room_id" id="room_id" class="form-control @error('room_id') is-invalid @enderror" required disabled>
                                    <option value="">-- Sélectionnez d'abord une propriété --</option>
                                </select>
                                @error('room_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description du problème *</label>
                                <textarea name="description" id="description" rows="6" class="form-control @error('description') is-invalid @enderror" placeholder="Décrivez en détail le problème rencontré (minimum 10 caractères)..." required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Soyez le plus précis possible pour permettre une intervention rapide et efficace.
                                </small>
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="icon-paper-plane"></i> Envoyer la demande
                                </button>
                                <a href="{{ url('/') }}" class="btn btn-secondary btn-lg ml-2">
                                    <i class="icon-arrow-left"></i> Retour
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="alert alert-info">
                        <h5><i class="icon-info"></i> Information importante</h5>
                        <p class="mb-2"><strong>Délai de traitement :</strong> Les demandes de maintenance sont généralement traitées dans un délai de 24 à 48 heures ouvrables.</p>
                        <p class="mb-2"><strong>Urgences :</strong> Pour les urgences (fuite d'eau, panne électrique, etc.), contactez directement le propriétaire par téléphone.</p>
                        <p class="mb-0"><strong>Suivi :</strong> Vous pouvez suivre l'état de votre demande en contactant le propriétaire.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadRooms() {
    const propertyId = document.getElementById('property_id').value;
    const roomSelect = document.getElementById('room_id');

    if (propertyId) {
        // Activer le select des chambres
        roomSelect.disabled = false;
        roomSelect.innerHTML = '<option value="">Chargement...</option>';

        // Requête AJAX pour charger les chambres
        fetch(`/maintenance/rooms/${propertyId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(rooms => {
                roomSelect.innerHTML = '<option value="">-- Choisissez la chambre --</option>';

                if (rooms && rooms.length > 0) {
                    rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.id;
                        // Vérification des propriétés avant utilisation
                        const roomNumber = room.room_number || room.number || 'N/A';
                        const roomType = room.type || 'Type non défini';
                        option.textContent = `Chambre ${roomNumber} - ${roomType}`;
                        roomSelect.appendChild(option);
                    });
                } else {
                    roomSelect.innerHTML = '<option value="">Aucune chambre disponible</option>';
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des chambres:', error);
                roomSelect.innerHTML = '<option value="">Erreur lors du chargement</option>';
            });
    } else {
        roomSelect.disabled = true;
        roomSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord une propriété --</option>';
    }
}

// Pré-remplir les chambres si une propriété est sélectionnée
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const propertyId = urlParams.get('property_id');
    const tenantId = urlParams.get('tenant_id');

    if (propertyId) {
        document.getElementById('property_id').value = propertyId;
        if (tenantId) {
            document.getElementById('tenant_id').value = tenantId;
        }
        // Charger les chambres automatiquement
        loadRooms();
    }
});
</script>

@endsection
