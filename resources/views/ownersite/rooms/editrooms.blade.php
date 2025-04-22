@extends('ownersite.layout')
@section('title', 'Modifier la chambre')
@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Modifier la chambre</h1>
        <a href="{{ route('rooms.show', ['property' => $property->id, 'room' => $room->id]) }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour
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

    <!-- Formulaire de modification -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informations de la chambre</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('rooms.update', ['property' => $property->id, 'room' => $room->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nom de la chambre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $room->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $room->description) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Prix mensuel (Fcfa) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" value="{{ old('price', $room->price) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Fcfa/mois</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_rented" name="is_rented" value="1" {{ $room->is_rented ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_rented">Chambre occupée</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Photo principale actuelle</label>
                            <div class="current-photo-container mb-3">
                                <img src="{{ asset('storage/'.$room->principal_photo) }}" alt="Photo principale" class="img-thumbnail" style="max-height: 200px;">
                            </div>

                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="principal_photo" name="principal_photo">
                                <label class="custom-file-label" for="principal_photo">Changer la photo principale</label>
                                <small class="form-text text-muted">Laissez vide pour conserver l'image actuelle</small>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <label>Photos secondaires actuelles</label>
                            <div class="row secondary-photos-preview mb-3">
                                @forelse($room->roomSecondaryPhoto as $photo)
                                <div class="col-md-4 mb-2">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/'.$photo->room_photo) }}" alt="Photo secondaire" class="img-thumbnail" style="height: 100px; width: 100%; object-fit: cover;">
                                        <div class="photo-overlay">
                                            <button type="button" class="btn btn-sm btn-danger delete-photo" data-photo-id="{{ $photo->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <p class="text-muted">Aucune photo secondaire disponible</p>
                                </div>
                                @endforelse
                            </div>

                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="secondary_photos" name="secondary_photos[]" multiple>
                                <label class="custom-file-label" for="secondary_photos">Ajouter des photos secondaires</label>
                                <small class="form-text text-muted">Vous pouvez sélectionner plusieurs photos</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h6 class="m-0 font-weight-bold text-primary">Caractéristiques de la chambre</h6>
                    </div>
                    <div class="card-body">
                        <div id="caracteristiques-container">
                            @forelse($room->roomCaracteristic as $index => $caracteristic)
                            <div class="input-group mb-3 caracteristique-input">
                                <input type="text" class="form-control" name="caracteristiques[]" value="{{ $caracteristic->title }}" placeholder="Caractéristique de la chambre (ex: Lit double, Bureau, etc.)">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-danger remove-caracteristique" type="button"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            @empty
                            <div class="input-group mb-3 caracteristique-input">
                                <input type="text" class="form-control" name="caracteristiques[]" placeholder="Caractéristique de la chambre (ex: Lit double, Bureau, etc.)">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-danger remove-caracteristique" type="button"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-caracteristique">
                            <i class="fas fa-plus mr-1"></i> Ajouter une caractéristique
                        </button>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression de photo -->
<div class="modal fade" id="deletePhotoModal" tabindex="-1" role="dialog" aria-labelledby="deletePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deletePhotoModalLabel">Supprimer la photo</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette photo ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form id="deletePhotoForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Affichage du nom de fichier pour les inputs de type file
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            if (fileName) {
                $(this).next('.custom-file-label').html(fileName);
            } else {
                let label = $(this).attr('id') === 'principal_photo' ? 'Changer la photo principale' : 'Ajouter des photos secondaires';
                $(this).next('.custom-file-label').html(label);
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

        // Gestion de la suppression de photo
        $('.delete-photo').click(function() {
            const photoId = $(this).data('photo-id');
            const deleteUrl = `/room-photos/${photoId}`;

            $('#deletePhotoForm').attr('action', deleteUrl);
            $('#deletePhotoModal').modal('show');
        });
    });
</script>

<style>
    .current-photo-container {
        text-align: center;
        background-color: #f8f9fc;
        padding: 10px;
        border-radius: 5px;
    }

    .secondary-photos-preview {
        background-color: #f8f9fc;
        padding: 10px;
        border-radius: 5px;
    }

    .photo-overlay {
        position: absolute;
        top: 0;
        right: 0;
        margin: 5px;
        opacity: 0.8;
    }

    .photo-overlay:hover {
        opacity: 1;
    }

    .caracteristique-input {
        transition: all 0.3s ease;
    }

    .caracteristique-input:hover {
        background-color: #f8f9fc;
        border-radius: 5px;
    }
</style>
@endsection
