@extends('ownersite.layout')
@section('title', 'Modifier le contrat')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Modifier le contrat</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informations du contrat</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('contracts.update', $contract) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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

                <!-- Locataire (non modifiable) -->
                <div class="form-group">
                    <label>Locataire</label>
                    <input type="text" class="form-control" value="{{ $contract->tenant->name }}" readonly>
                </div>

                <!-- Date de début -->
                <div class="form-group">
                    <label for="start_date">Date de début <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" required>
                </div>

                <!-- Date de fin -->
                <div class="form-group">
                    <label for="end_date">Date de fin <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ old('end_date', $contract->end_date->format('Y-m-d')) }}" required>
                </div>

                <!-- Statut -->
                <div class="form-group">
                    <label for="status">Statut <span class="text-danger">*</span></label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="active" {{ old('status', $contract->status) == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="terminated" {{ old('status', $contract->status) == 'terminated' ? 'selected' : '' }}>Terminé</option>
                        <option value="expired" {{ old('status', $contract->status) == 'expired' ? 'selected' : '' }}>Expiré</option>
                    </select>
                </div>

                <!-- Document actuel -->
                <div class="form-group">
                    <label>Document actuel</label>
                    <div class="mb-2">
                        <a href="{{ Storage::url($contract->document) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-file-pdf"></i> Voir le contrat actuel
                        </a>
                    </div>
                </div>

                <!-- Nouveau document -->
                <div class="form-group">
                    <label for="document">Nouveau document (PDF)</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="document" name="document" accept="application/pdf">
                        <label class="custom-file-label" for="document">Choisir un nouveau fichier PDF</label>
                    </div>
                    <small class="form-text text-muted">
                        Laissez vide pour conserver le document actuel
                    </small>
                </div>

                <!-- Boutons -->
                <div class="form-group text-right">
                    <a href="{{ route('contracts.show', $contract) }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer les modifications
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

        // Validation des dates
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        startDateInput.addEventListener('change', function() {
            if (startDateInput.value && endDateInput.value) {
                if (new Date(startDateInput.value) > new Date(endDateInput.value)) {
                    alert('La date de début doit être antérieure à la date de fin');
                    startDateInput.value = '';
                }
            }
        });

        endDateInput.addEventListener('change', function() {
            if (startDateInput.value && endDateInput.value) {
                if (new Date(startDateInput.value) > new Date(endDateInput.value)) {
                    alert('La date de fin doit être postérieure à la date de début');
                    endDateInput.value = '';
                }
            }
        });
    });
</script>
@endsection