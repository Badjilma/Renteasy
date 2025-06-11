@extends('layout')

@section('title', $room->name . ' - ' . $property->name)

@section('content')
<div class="site-blocks-cover overlay" style="background-image: url('template/images/hero_1.jpg');" data-aos="fade" id="home-section">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-8 mt-lg-5 text-center">
                <h1>{{ $room->name }}</h1>
                <h2 class="text-white">{{ $property->name }}</h2>
                <p class="mb-5 text-white">
                    <i class="icon-location-pin"></i> {{ $property->address }}
                </p>
            </div>
        </div>
    </div>
    <a href="#room-details" class="smoothscroll arrow-down"><span class="icon-arrow_downward"></span></a>
</div>

<div class="site-section" id="room-details">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light p-3 rounded">
                        <li class="breadcrumb-item">
                            <a href="{{ route('public.properties.all') }}">Toutes les propriétés</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('public.property.show', $property->id) }}">{{ $property->name }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('public.rooms.index', $property->id) }}">Chambres</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $room->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <!-- Galerie d'images -->
            <div class="col-lg-8">
                <div class="mb-4">
                    <!-- Carrousel d'images -->
                    <div class="owl-carousel slide-one-item with-dots">
                        <!-- Photo principale -->
                        <div>
                            <img src="{{ asset('storage/'.$room->principal_photo) }}"
                                 alt="{{ $room->name }}"
                                 class="img-fluid rounded">
                        </div>

                        <!-- Photos secondaires -->
                        @forelse($room->roomSecondaryPhoto as $photo)
                        <div>
                            <img src="{{ asset('storage/'.$photo->room_photo) }}"
                                 alt="{{ $room->name }}"
                                 class="img-fluid rounded">
                        </div>
                        @empty
                        <!-- Si pas de photos secondaires, répéter la photo principale -->
                        <div>
                            <img src="{{ asset('storage/'.$room->principal_photo) }}"
                                 alt="{{ $room->name }}"
                                 class="img-fluid rounded">
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Détails de la chambre -->
            <div class="col-lg-4">
                <div class="bg-light p-4 rounded mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="text-primary mb-0">{{ $room->name }}</h3>
                        <span class="badge {{ $room->is_rented ? 'badge-danger' : 'badge-success' }} p-2">
                            {{ $room->is_rented ? 'Occupée' : 'Disponible' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <h4 class="text-dark">{{ number_format($room->price) }} FCFA/mois</h4>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="text-dark mb-3">Description</h5>
                        <p class="text-muted">{{ $room->description }}</p>
                    </div>

                    <!-- Caractéristiques -->
                    @if($room->roomCaracteristic->count() > 0)
                    <div class="mb-4">
                        <h5 class="text-dark mb-3">
                            <i class="icon-check"></i> Caractéristiques
                        </h5>
                        <ul class="list-unstyled">
                            @foreach($room->roomCaracteristic as $caracteristic)
                            <li class="mb-2">
                                <i class="icon-check text-success mr-2"></i>
                                {{ $caracteristic->title }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="mb-3">
                        @if(!$room->is_rented)
                        <a href="#contact-section" class="btn btn-primary btn-block btn-lg smoothscroll">
                            <i class="icon-phone"></i> Contacter pour cette chambre
                        </a>
                        @else
                        <button class="btn btn-secondary btn-block btn-lg" disabled>
                            <i class="icon-lock"></i> Chambre non disponible
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Informations sur la propriété -->
                <div class="bg-white border p-4 rounded">
                    <h5 class="text-dark mb-3">À propos de la propriété</h5>
                    <div class="d-flex mb-3">
                        <img src="{{ asset('storage/'.$property->principal_photo) }}"
                             alt="{{ $property->name }}"
                             class="rounded mr-3"
                             style="width: 60px; height: 60px; object-fit: cover;">
                        <div>
                            <h6 class="mb-1">{{ $property->name }}</h6>
                            <small class="text-muted">
                                <i class="icon-location-pin"></i> {{ $property->address }}
                            </small>
                        </div>
                    </div>
                    <p class="text-muted small">{{ Str::limit($property->description, 100) }}</p>
                    <a href="{{ route('public.property.show', $property->id) }}"
                       class="btn btn-outline-primary btn-sm">
                        Voir la propriété complète
                    </a>
                </div>
            </div>
        </div>

        <!-- Section contact -->
        @if(!$room->is_rented)
        <div class="row mt-5" id="contact-section">
            <div class="col-12">
                <div class="bg-primary text-white p-5 rounded">
                    <div class="row">
                        <div class="col-lg-6">
                            <h3 class="text-white mb-4">Intéressé par cette chambre ?</h3>
                            <p class="mb-4">Contactez le propriétaire pour plus d'informations ou pour organiser une visite.</p>

                            <div class="mb-4">
                                <h5 class="text-white">{{ $property->user->name ?? 'Propriétaire' }}</h5>
                                <p class="text-white-50">Propriétaire de {{ $property->name }}</p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <!-- Formulaire de contact -->
                            <form action="#" method="POST" class="bg-white p-4 rounded">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                <input type="hidden" name="property_id" value="{{ $property->id }}">

                                <div class="form-group">
                                    <input type="text" class="form-control" name="name"
                                           placeholder="Votre nom complet" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email"
                                           placeholder="Votre adresse email" required>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" name="phone"
                                           placeholder="Votre numéro de téléphone">
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" name="message" rows="4"
                                              placeholder="Votre message concernant la chambre '{{ $room->name }}'"
                                              required>Je suis intéressé(e) par la chambre "{{ $room->name }}" dans votre propriété "{{ $property->name }}". Pourriez-vous me donner plus d'informations ?</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="icon-paper-plane"></i> Envoyer le message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Autres chambres de cette propriété -->
        @php
        $otherRooms = $property->rooms()->where('id', '!=', $room->id)->where('is_rented', false)->limit(3)->get();
        @endphp

        @if($otherRooms->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="text-center mb-4">Autres chambres disponibles dans cette propriété</h3>
            </div>
        </div>
        <div class="row">
            @foreach($otherRooms as $otherRoom)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <img class="card-img-top"
                         src="{{ asset('storage/'.$otherRoom->principal_photo) }}"
                         alt="{{ $otherRoom->name }}"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $otherRoom->name }}</h5>
                        <p class="text-muted">{{ Str::limit($otherRoom->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold text-dark">{{ number_format($otherRoom->price) }} FCFA</span>
                            <a href="{{ route('public.rooms.show', [$property->id, $otherRoom->id]) }}"
                               class="btn btn-primary btn-sm">Voir</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Boutons de navigation -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="{{ route('public.rooms.index', $property->id) }}" class="btn btn-outline-primary mr-3">
                    <i class="icon-arrow-left"></i> Toutes les chambres
                </a>
                <a href="{{ route('public.property.show', $property->id) }}" class="btn btn-outline-secondary mr-3">
                    <i class="icon-home"></i> Voir la propriété
                </a>
                <a href="{{ route('public.properties.all') }}" class="btn btn-outline-dark">
                    <i class="icon-list"></i> Toutes les propriétés
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.owl-carousel .owl-item img {
    height: 400px;
    object-fit: cover;
    width: 100%;
}

.badge {
    font-size: 0.8rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s;
}
</style>
@endsection
