@extends('ownersite.layout')
@section('title', 'Ajouter un locataire')
@section('content')
    <div class="container-fluid">
        <!-- En-tête de page -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Ajouter un nouveau locataire</h1>
            <a href="{{ route('tenants.all') }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour aux locataires
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
                <h6 class="m-0 font-weight-bold text-primary">Informations du locataire</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('tenants.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nom complet <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Numéro de téléphone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ old('phone') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="CNI">Numéro CNI <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cni" name="cni"
                                    value="{{ old('cni') }}" required>
                                <small class="form-text text-muted">Numéro de la Carte Nationale d'Identité</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="room_id">Chambre à affecter <span class="text-danger">*</span></label>
                                <select class="form-control" id="room_id" name="room_id" required>
                                    <option value="">Sélectionnez une chambre</option>
                                    @foreach ($availableRooms as $room)
                                        <option value="{{ $room->id }}"
                                            {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->property->name ?? 'Propriété sans nom' }} -
                                            Chambre {{ $room->name }} -
                                            {{ $room->price }} FCFA -
                                            Caractéristiques:
                                            @foreach ($room->roomCaracteristic as $caract)
                                                {{ $caract->title }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">Date de début de location <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ old('start_date', date('Y-m-d')) }}" required>
                                <small class="form-text text-muted">Date à partir de laquelle le locataire occupe la
                                    chambre</small>
                            </div>
                        </div>

                        <div class="col-md-6">
    <div class="form-group">
        <label for="end_date">Date de fin de location (optionnel)</label>
        <input type="date" class="form-control" id="end_date" name="end_date" 
               value="{{ old('end_date') }}">
        <small class="form-text text-muted">Laisser vide si durée indéterminée</small>
    </div>
</div>
                    </div>


                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save mr-2"></i> Enregistrer le locataire
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
