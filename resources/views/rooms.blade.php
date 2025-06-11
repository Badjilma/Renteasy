@extends('layout')

@section('title', 'Chambres de ' . $property->name)

@section('content')
<div class="site-blocks-cover overlay" style="background-image: url('template/images/hero_1.jpg');" data-aos="fade" id="home-section">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-8 mt-lg-5 text-center">
                <h1>Chambres disponibles</h1>
                <h2 class="text-white">{{ $property->name }}</h2>
                <p class="mb-5 text-white">
                    <i class="icon-location-pin"></i> {{ $property->address }}
                </p>
            </div>
        </div>
    </div>
    <a href="#rooms-section" class="smoothscroll arrow-down"><span class="icon-arrow_downward"></span></a>
</div>

<div class="site-section" id="rooms-section">
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
                        <li class="breadcrumb-item active" aria-current="page">Chambres</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Information sur la propriété -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="bg-light p-4 rounded">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <img src="{{ asset('storage/'.$property->principal_photo) }}"
                                 alt="{{ $property->name }}"
                                 class="img-fluid rounded">
                        </div>
                        <div class="col-md-9">
                            <h3 class="text-primary mb-2">{{ $property->name }}</h3>
                            <p class="text-muted mb-2">
                                <i class="icon-location-pin"></i> {{ $property->address }}
                            </p>
                            <p>{{ Str::limit($property->description, 150) }}</p>
                            <span class="badge badge-success">
                                {{ $rooms->where('is_rented', false)->count() }} chambres disponibles
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des chambres -->
        @if($rooms->isEmpty())
        <div class="row">
            <div class="col-12 text-center py-5">
                <div class="bg-light p-5 rounded">
                    <i class="icon-home text-muted mb-3" style="font-size: 4rem;"></i>
                    <h4 class="text-muted">Aucune chambre disponible</h4>
                    <p class="text-muted">Cette propriété n'a pas encore de chambres enregistrées.</p>
                    <a href="{{ route('public.property.show', $property->id) }}" class="btn btn-primary">
                        <i class="icon-arrow-left"></i> Retour à la propriété
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="row">
            @foreach($rooms as $room)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="position-relative">
                        <img class="card-img-top"
                             src="{{ asset('storage/'.$room->principal_photo) }}"
                             alt="{{ $room->name }}"
                             style="height: 250px; object-fit: cover;">

                        <!-- Badge de disponibilité -->
                        <div class="position-absolute" style="top: 15px; right: 15px;">
                            <span class="badge {{ $room->is_rented ? 'badge-danger' : 'badge-success' }} p-2">
                                {{ $room->is_rented ? 'Occupée' : 'Disponible' }}
                            </span>
                        </div>

                        <!-- Prix -->
                        <div class="position-absolute bg-dark text-white p-2 rounded"
                             style="bottom: 15px; right: 15px;">
                            <strong>{{ number_format($room->price) }} FCFA/mois</strong>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary font-weight-bold">{{ $room->name }}</h5>
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($room->description, 100) }}
                        </p>

                        <!-- Caractéristiques -->
                        @if($room->roomCaracteristic->count() > 0)
                        <div class="mb-3">
                            <h6 class="font-weight-bold text-dark mb-2">
                                <i class="icon-check"></i> Caractéristiques
                            </h6>
                            <div class="row">
                                @foreach($room->roomCaracteristic->take(4) as $caracteristic)
                                <div class="col-6 mb-1">
                                    <small class="text-muted">
                                        <i class="icon-check text-success"></i> {{ $caracteristic->title }}
                                    </small>
                                </div>
                                @endforeach
                            </div>
                            @if($room->roomCaracteristic->count() > 4)
                            <small class="text-muted">
                                et {{ $room->roomCaracteristic->count() - 4 }} autres...
                            </small>
                            @endif
                        </div>
                        @endif

                        <div class="mt-auto">
                            @if(!$room->is_rented)
                            <a href="{{ route('public.rooms.show', [$property->id, $room->id]) }}"
                               class="btn btn-primary btn-block">
                                <i class="icon-eye"></i> Voir les détails
                            </a>
                            @else
                            <button class="btn btn-secondary btn-block" disabled>
                                <i class="icon-lock"></i> Chambre occupée
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Bouton retour -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="{{ route('public.property.show', $property->id) }}" class="btn btn-outline-primary mr-3">
                    <i class="icon-arrow-left"></i> Retour à la propriété
                </a>
                <a href="{{ route('public.properties.all') }}" class="btn btn-outline-secondary">
                    <i class="icon-home"></i> Toutes les propriétés
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.badge {
    font-size: 0.75rem;
}

.breadcrumb {
    margin-bottom: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
}
</style>
@endsection
