@extends('ownersite.layout')
@section('title', 'Ajout de contrat')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ajouter un contrat</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informations du contrat</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Messages d'erreur -->
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Sélection du locataire -->
                <div class="form-group">
                    <label for="tenant_id">Locataire <span class="text-danger">*</span></label>
                    <select class="form-control" id="tenant_id" name="tenant_id" required>
                        <option value="">Sélectionner un locataire</option>
                        @foreach($tenants as $tenant)
                            <option value="{{ $tenant->id }}">{{ $tenant->name }} ({{ $tenant->phone }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date de début -->
                <div class="form-group">
                    <label for="start_date">Date de début <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ old('start_date') }}" required>
                </div>

                <!-- Date de fin -->
                <div class="form-group">
                    <label for="end_date">Date de fin <small class="text-muted">(optionnel)</small></label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ old('end_date') }}">
                    <small class="form-text text-muted">
                        Laissez vide pour un contrat à durée indéterminée
                    </small>
                </div>

                <!-- Statut du contrat -->
                <div class="form-group">
                    <label for="status">Statut du contrat</label>
                    <select class="form-control" id="status" name="status">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>Terminé</option>
                        <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expiré</option>
                    </select>
                    <small class="form-text text-muted">
                        Généralement "Actif" pour un nouveau contrat
                    </small>
                </div>

                <!-- Type de contrat -->
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="indefinite_contract">
                        <label class="form-check-label" for="indefinite_contract">
                            Contrat à durée indéterminée
                        </label>
                    </div>
                </div>

                <!-- Document du contrat -->
                <div class="form-group">
                    <label for="document">Document du contrat (PDF) <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="document" name="document"
                               accept="application/pdf" required>
                        <label class="custom-file-label" for="document">Choisir un fichier PDF</label>
                    </div>
                    <small class="form-text text-muted">
                        Téléversez le contrat signé au format PDF
                    </small>
                </div>

                <!-- Boutons -->
                <div class="form-group text-right">
                    <a href="{{ route('contracts.all') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts pour la gestion du formulaire -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Afficher le nom du fichier sélectionné
        document.getElementById('document').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.querySelector('label[for="document"]').textContent = file.name;
            }
        });

        // Gestion du contrat à durée indéterminée
        const indefiniteCheckbox = document.getElementById('indefinite_contract');
        const endDateInput = document.getElementById('end_date');

        indefiniteCheckbox.addEventListener('change', function() {
            if (this.checked) {
                endDateInput.value = '';
                endDateInput.disabled = true;
                endDateInput.style.backgroundColor = '#f8f9fa';
            } else {
                endDateInput.disabled = false;
                endDateInput.style.backgroundColor = '';
            }
        });

        // Validation des dates
        const startDateInput = document.getElementById('start_date');

        function validateDates() {
            if (startDateInput.value && endDateInput.value && !endDateInput.disabled) {
                if (new Date(startDateInput.value) >= new Date(endDateInput.value)) {
                    alert('La date de fin doit être postérieure à la date de début');
                    return false;
                }
            }
            return true;
        }

        startDateInput.addEventListener('change', function() {
            validateDates();
        });

        endDateInput.addEventListener('change', function() {
            validateDates();
        });

        // Validation du formulaire avant soumission
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!validateDates()) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
