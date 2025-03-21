@extends('ownersite.layout')
@section('title', 'Ajout de propriété')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ajouter une propriété</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informations de la propriété</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('properties.create') }}" method="POST" enctype="multipart/form-data">
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

                <!-- Nom de la propriété -->
                <div class="form-group">
                    <label for="name">Nom de la propriété <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <!-- Adresse -->
                <div class="form-group">
                    <label for="address">Adresse <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>

                <!-- Photo principale -->
                <div class="form-group">
                    <label for="principal_photo">Photo principale <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="principal_photo" name="principal_photo" accept="image/*" required>
                        <label class="custom-file-label" for="principal_photo">Choisir une image</label>
                    </div>
                    <div class="mt-2">
                        <img id="principal_photo_preview" src="#" alt="Aperçu" style="max-width: 300px; max-height: 200px; display: none;">
                    </div>
                </div>

                <!-- Photos secondaires -->
                <div class="form-group">
                    <label for="secondary_photos">Photos secondaires</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="secondary_photos" name="secondary_photos[]" accept="image/*" multiple>
                        <label class="custom-file-label" for="secondary_photos">Choisir plusieurs images</label>
                    </div>
                    <div id="secondary_photos_preview" class="d-flex flex-wrap mt-2">
                        <!-- Les aperçus des photos secondaires seront affichés ici -->
                    </div>
                </div>

                <!-- Règles de la propriété -->
                <div class="form-group">
                    <label>Règles de la propriété</label>
                    <div id="rules_container">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="rules[]" placeholder="Règle de la propriété">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger remove-rule" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary mt-2" id="add_rule">
                        <i class="fas fa-plus"></i> Ajouter une règle
                    </button>
                </div>

                <!-- Boutons -->
                <div class="form-group text-right">
                    <a href="{{ route('properties.all') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts pour la prévisualisation des images et la gestion des règles -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prévisualisation de la photo principale
        document.getElementById('principal_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const preview = document.getElementById('principal_photo_preview');

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.readAsDataURL(file);
                document.querySelector('label[for="principal_photo"]').textContent = file.name;
            }
        });

        // Prévisualisation des photos secondaires
        document.getElementById('secondary_photos').addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('secondary_photos_preview');
            previewContainer.innerHTML = '';

            if (files.length > 0) {
                document.querySelector('label[for="secondary_photos"]').textContent = `${files.length} fichier(s) sélectionné(s)`;

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const imgContainer = document.createElement('div');
                        imgContainer.className = 'm-2';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = `Photo ${i+1}`;
                        img.style.maxWidth = '150px';
                        img.style.maxHeight = '100px';

                        imgContainer.appendChild(img);
                        previewContainer.appendChild(imgContainer);
                    };

                    reader.readAsDataURL(file);
                }
            }
        });

        // Gestion des règles
        document.getElementById('add_rule').addEventListener('click', function() {
            const rulesContainer = document.getElementById('rules_container');
            const ruleGroups = rulesContainer.querySelectorAll('.input-group');

            // Activer les boutons de suppression pour les règles existantes
            ruleGroups.forEach(group => {
                group.querySelector('.remove-rule').disabled = false;
            });

            // Créer une nouvelle règle
            const newRuleGroup = document.createElement('div');
            newRuleGroup.className = 'input-group mb-2';
            newRuleGroup.innerHTML = `
                <input type="text" class="form-control" name="rules[]" placeholder="Règle de la propriété">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-rule">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

            rulesContainer.appendChild(newRuleGroup);

            // Ajouter l'événement de suppression
            newRuleGroup.querySelector('.remove-rule').addEventListener('click', function() {
                newRuleGroup.remove();

                // Si c'est la dernière règle, désactiver le bouton de suppression
                const remainingRules = rulesContainer.querySelectorAll('.input-group');
                if (remainingRules.length === 1) {
                    remainingRules[0].querySelector('.remove-rule').disabled = true;
                }
            });
        });

        // Initialiser les gestionnaires d'événements pour les boutons de suppression existants
        document.querySelectorAll('.remove-rule').forEach(button => {
            button.addEventListener('click', function() {
                button.closest('.input-group').remove();

                // Si c'est la dernière règle, désactiver le bouton de suppression
                const remainingRules = document.querySelectorAll('#rules_container .input-group');
                if (remainingRules.length === 1) {
                    remainingRules[0].querySelector('.remove-rule').disabled = true;
                }
            });
        });
    });
</script>
@endsection
