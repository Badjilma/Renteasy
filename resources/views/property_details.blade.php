@extends('layout')

@section('title', $property->name)

@section('content')
<div class="site-blocks-cover overlay" style="background-image: url('template/images/hero_1.jpg');" data-aos="fade" id="home-section">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-6 mt-lg-5 text-center">
                <h1>Mettez à profit votre propriété dans le numérique</h1>
                <p class="mb-5">Vous avez des soucis pour gérer vos locataires de vos propriétés, les paiements, les règles de la maison,
                    les contrats, des maintenances de la maison...? Vous êtes au bon endroit!!!
                </p>
            </div>
        </div>
    </div>
    <a href="#howitworks-section" class="smoothscroll arrow-down"><span class="icon-arrow_downward"></span></a>
</div>

<div class="site-section" id="property-details">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="owl-carousel slide-one-item with-dots">
                    <!-- Photo principale -->
                    <div>
                        <img src="{{ asset('storage/'.$property->principal_photo) }}" alt="{{ $property->name }}" class="img-fluid">
                    </div>

                    <!-- Photos secondaires -->
                    @forelse($property->secondaryPhotos as $photo)
                    <div>
                        <img src="{{ asset('storage/'.$photo->property_photo) }}" alt="{{ $property->name }}" class="img-fluid">
                    </div>
                    @empty
                    <!-- Si pas de photos secondaires, répéter la photo principale -->
                    <div>
                        <img src="{{ asset('storage/'.$property->principal_photo) }}" alt="{{ $property->name }}" class="img-fluid">
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-5 pl-lg-5 ml-auto">
                <div class="mb-5">
                    <h3 class="text-black mb-4">{{ $property->name }}</h3>
                    <p class="text-primary mb-3">
                        <i class="icon-location-pin"></i> {{ $property->address }}
                    </p>

                    <!-- Description de la propriété -->
                    <div class="mb-4">
                        <h5 class="text-black mb-3">Description</h5>
                        <p>{{ $property->description }}</p>
                    </div>

                    <!-- Informations sur les chambres -->
                    @if($property->rooms->count() > 0)
                    <div class="mb-4">
                        <h5 class="text-black mb-3">Chambres disponibles</h5>
                        <div class="bg-light p-3 rounded mb-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <h4 class="text-primary mb-1">{{ $property->rooms->count() }}</h4>
                                    <small class="text-muted">Total</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-success mb-1">{{ $property->rooms->where('is_rented', false)->count() }}</h4>
                                    <small class="text-muted">Disponibles</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-danger mb-1">{{ $property->rooms->where('is_rented', true)->count() }}</h4>
                                    <small class="text-muted">Occupées</small>
                                </div>
                            </div>
                        </div>

                        <!-- Aperçu des chambres -->
                        <div class="row">
                            @foreach($property->rooms->take(4) as $room)
                            <div class="col-md-6 mb-2">
                                <div class="border p-2 rounded d-flex align-items-center">
                                    <img src="{{ asset('storage/'.$room->principal_photo) }}"
                                         alt="{{ $room->name }}"
                                         class="rounded mr-2"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <small class="text-dark font-weight-bold d-block">{{ $room->name }}</small>
                                        <small class="text-muted">{{ number_format($room->price) }} FCFA/mois</small>
                                    </div>
                                    <span class="badge {{ $room->is_rented ? 'badge-danger' : 'badge-success' }} badge-sm">
                                        {{ $room->is_rented ? 'Occupée' : 'Libre' }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($property->rooms->count() > 4)
                        <small class="text-muted">et {{ $property->rooms->count() - 4 }} autres chambres...</small>
                        @endif

                        <!-- Bouton pour voir toutes les chambres -->
                        <div class="mt-3">
                            <a href="{{ route('public.rooms.index', $property->id) }}" class="btn btn-outline-primary btn-block">
                                <i class="icon-eye"></i> Voir toutes les chambres ({{ $property->rooms->count() }})
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="mb-4">
                        <div class="bg-light p-3 rounded text-center">
                            <i class="icon-home text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">Aucune chambre enregistrée pour cette propriété</p>
                        </div>
                    </div>
                    @endif

                    <!-- Règles de la propriété -->
                    @if($property->rules->count() > 0)
                    <div class="mb-4">
                        <h5 class="text-black mb-3">Règles de la propriété</h5>
                        <ul class="list-unstyled">
                            @foreach($property->rules as $rule)
                            <li class="mb-2">
                                <i class="icon-check text-primary mr-2"></i>{{ $rule->title }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Statut de disponibilité -->
                    <div class="mb-4">
                        <span class="badge {{ $property->availability ? 'badge-success' : 'badge-danger' }} p-2">
                            {{ $property->availability ? 'Disponible' : 'Non disponible' }}
                        </span>
                    </div>

                    @if($property->availability)
                    <p><a href="#contact-owner" class="btn btn-primary">Contacter le propriétaire</a></p>
                    @else
                    <p><span class="btn btn-secondary disabled">Propriété non disponible</span></p>
                    @endif
                </div>

                <div class="mb-5" id="contact-owner">
                    <h3 class="text-black mb-4">Contacter le propriétaire</h3>
                    <div class="mt-5">
                        <div class="bg-light p-4 rounded">
                            <h4 class="text-black">{{ $property->user->name ?? 'Propriétaire' }}</h4>
                            <p class="text-muted mb-4">Propriétaire de {{ $property->name }}</p>
                            <p>Pour plus d'informations sur cette propriété ou pour organiser une visite, n'hésitez pas à nous contacter.</p>

                            <!-- Formulaire de contact simple -->
                            <form action="#" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" placeholder="Votre nom" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Votre email" required>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" name="phone" placeholder="Votre téléphone">
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" name="message" rows="4" placeholder="Votre message" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Envoyer le message</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bouton retour -->
                <div class="mb-3">
                    <a href="{{ route('public.properties.all') }}" class="btn btn-outline-primary">
                        <i class="icon-arrow-left"></i> Retour aux propriétés
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.badge-sm {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
}
</style>
@endsection
