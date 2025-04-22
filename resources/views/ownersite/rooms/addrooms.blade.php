@extends('ownersite.layout')
@section('title', 'Ajouter une chambre')
@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        {{-- <h1 class="h3 mb-0 text-gray-800">Ajouter une chambre à "{{ $property->name }}"</h1> --}}
        <a href="{{ route('rooms.all', $property->id) }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour aux chambres
        </a>
    </div>

    <!-- Messages d'erreur -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i> Veuillez corriger les erreurs suivantes :
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Formulaire d'ajout -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informations de la chambre</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('rooms.store', $property->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nom de la chambre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Prix mensuel (Fcfa) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" value="{{ old('price') }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Fcfa/mois</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="principal_photo">Photo principale <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="principal_photo" name="principal_photo" required>
                                <label class="custom-file-label" for="principal_photo">Choisir une image</label>
                            </div>
                            <div class="mt-2 text-center preview-image-container d-none">
                                <img id="preview-principal" src="#" alt="Aperçu de l'image" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <label for="secondary_photos">Photos secondaires (facultatif)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="secondary_photos" name="secondary_photos[]" multiple>
                                <label class="custom-file-label" for="secondary_photos">Choisir des images</label>
                            </div>
                            <small class="form-text text-muted">Vous pouvez sélectionner plusieurs photos</small>
                            <div class="mt-2 row" id="preview-secondary-container"></div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h6 class="m-0 font-weight-bold text-primary">Caractéristiques de la chambre</h6>
                    </div>
                    <div class="card-body">
                        <div id="caracteristiques-container">
                            <div class="input-group mb-3 caracteristique-input">
                                <input type="text" class="form-control" name="caracteristiques[]" placeholder="Caractéristique de la chambre (ex: Lit double, Bureau, etc.)">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-danger remove-caracteristique" type="button"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-caracteristique">
                            <i class="fas fa-plus mr-1"></i> Ajouter une caractéristique
                        </button>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save mr-2"></i> Enregistrer la chambre
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prévisualisation de la photo principale
        $('#principal_photo').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-principal').attr('src', e.target.result);
                    $('.preview-image-container').removeClass('d-none');
                }
                reader.readAsDataURL(file);
                $(this).next('.custom-file-label').html(file.name);
            }
        });

        // Prévisualisation des photos secondaires
        $('#secondary_photos').on('change', function() {
            const files = this.files;
            $('#preview-secondary-container').empty();

            if (files.length > 0) {
                $(this).next('.custom-file-label').html(files.length + ' fichiers sélectionnés');

                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = `
                            <div class="col-md-4 mb-2">
                                <img src="${e.target.result}" alt="Aperçu" class="img-thumbnail" style="height: 100px; width: 100%; object-fit: cover;">
                            </div>
                        `;
                        $('#preview-secondary-container').append(preview);
                    }
                    reader.readAsDataURL(files[i]);
                }
            } else {
                $(this).next('.custom-file-label').html('Choisir des images');
            }
        });

        // Ajout d'une caractéristique
        $('#add-caracteristique').click(function() {
            const newCaracteristique = `
                <div class="input-group mb-3 caracteristique-input">
                    <input type="text" class="form-control" name="caracteristiques[]" placeholder="Caractéristique de la chambre (ex: Lit double, Bureau, etc.)">
                    <div class="input-group-append">
                        <button class="btn btn-outline-danger remove-caracteristique" type="button"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
            `;
            $('#caracteristiques-container').append(newCaracteristique);
        });

        // Suppression d'une caractéristique
        $(document).on('click', '.remove-caracteristique', function() {
            // Ne pas supprimer si c'est le seul champ
            if ($('.caracteristique-input').length > 1) {
                $(this).closest('.caracteristique-input').remove();
            } else {
                // Vider le champ au lieu de le supprimer
                $(this).closest('.caracteristique-input').find('input').val('');
            }
        });
    });
</script>
@endsection
