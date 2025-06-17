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
                                <select name="tenant_id" id="tenant_id" class="form-control @error('tenant_id') is-invalid @enderror" required style="color: #333; background-color: #fff;" onchange="loadTenantData()">
                                    <option value="">-- Choisissez votre nom --</option>
                                    @foreach($tenants as $tenant)
                                        <option value="{{ $tenant->id }}"
                                                data-property-id="{{ $tenant->rooms->first()->property->id ?? '' }}"
                                                data-property-name="{{ $tenant->rooms->first()->property->name ?? '' }} - {{ $tenant->rooms->first()->property->address ?? '' }}"
                                                data-room-id="{{ $tenant->rooms->first()->id ?? '' }}"
                                                data-room-name="{{ $tenant->rooms->first()->name ?? '' }}"
                                                {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                            {{ $tenant->name }} {{ $tenant->phone }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tenant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="property_id">Propriété associée *</label>
                                <select name="property_id" id="property_id" class="form-control @error('property_id') is-invalid @enderror" required readonly style="background-color: #f8f9fa;">
                                    <option value="">-- Sera rempli automatiquement --</option>
                                </select>
                                @error('property_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    La propriété sera automatiquement sélectionnée en fonction du locataire choisi.
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="room_id">Chambre associée *</label>
                                <select name="room_id" id="room_id" class="form-control @error('room_id') is-invalid @enderror" required readonly style="background-color: #f8f9fa;">
                                    <option value="">-- Sera remplie automatiquement --</option>
                                </select>
                                @error('room_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    La chambre sera automatiquement sélectionnée en fonction du locataire choisi.
                                </small>
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
                                <button type="submit" class="btn btn-primary btn-lg" id="submit-btn" disabled>
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
// Fonction pour charger les données du locataire
function loadTenantData() {
    const tenantSelect = document.getElementById('tenant_id');
    const propertySelect = document.getElementById('property_id');
    const roomSelect = document.getElementById('room_id');
    const submitBtn = document.getElementById('submit-btn');

    // Réinitialiser les champs
    propertySelect.innerHTML = '<option value="">-- Sera rempli automatiquement --</option>';
    roomSelect.innerHTML = '<option value="">-- Sera remplie automatiquement --</option>';
    submitBtn.disabled = true;

    if (!tenantSelect.value) {
        return;
    }

    // Récupérer l'option sélectionnée
    const selectedOption = tenantSelect.options[tenantSelect.selectedIndex];

    // Extraire les données depuis les attributs data-*
    const propertyId = selectedOption.getAttribute('data-property-id');
    const propertyName = selectedOption.getAttribute('data-property-name');
    const roomId = selectedOption.getAttribute('data-room-id');
    const roomName = selectedOption.getAttribute('data-room-name');

    // Vérifier si les données existent
    if (propertyId && roomId && propertyName && roomName) {
        // Remplir le champ propriété
        propertySelect.innerHTML = '';
        const propertyOption = document.createElement('option');
        propertyOption.value = propertyId;
        propertyOption.textContent = propertyName;
        propertyOption.selected = true;
        propertySelect.appendChild(propertyOption);

        // Remplir le champ chambre
        roomSelect.innerHTML = '';
        const roomOption = document.createElement('option');
        roomOption.value = roomId;
        roomOption.textContent = roomName;
        roomOption.selected = true;
        roomSelect.appendChild(roomOption);

        // Activer le bouton de soumission
        submitBtn.disabled = false;

        // Afficher un message de confirmation
        showMessage('Propriété et chambre chargées automatiquement', 'success');
    } else {
        // Aucune donnée trouvée pour ce locataire
        propertySelect.innerHTML = '<option value="">Aucune propriété associée</option>';
        roomSelect.innerHTML = '<option value="">Aucune chambre associée</option>';

        showMessage('Aucune propriété ou chambre associée à ce locataire', 'warning');
    }
}

// Fonction pour afficher des messages temporaires
function showMessage(message, type) {
    // Supprimer les messages existants
    const existingMessages = document.querySelectorAll('.temp-message');
    existingMessages.forEach(msg => msg.remove());

    // Créer le nouveau message
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'warning'} temp-message`;
    alertDiv.style.marginTop = '10px';
    alertDiv.innerHTML = `
        <i class="icon-${type === 'success' ? 'check' : 'exclamation-triangle'}"></i> ${message}
    `;

    // Insérer le message après le formulaire
    const cardBody = document.querySelector('.card-body');
    cardBody.appendChild(alertDiv);

    // Supprimer le message après 3 secondes
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Si un locataire est pré-sélectionné (en cas d'erreur de validation)
    const tenantSelect = document.getElementById('tenant_id');
    if (tenantSelect.value) {
        loadTenantData();
    }

    // Validation du formulaire avant soumission
    document.querySelector('form').addEventListener('submit', function(e) {
        const tenantId = document.getElementById('tenant_id').value;
        const propertyId = document.getElementById('property_id').value;
        const roomId = document.getElementById('room_id').value;

        if (!tenantId || !propertyId || !roomId) {
            e.preventDefault();
            showMessage('Veuillez sélectionner un locataire valide avec une propriété et une chambre associées', 'error');
            return false;
        }
    });
});
</script>

@endsection
