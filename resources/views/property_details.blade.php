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
                        <div class="row">
                            @foreach($property->rooms as $room)
                            <div class="col-md-6 mb-2">
                                <div class="border p-2 rounded">
                                    <small class="text-muted">Chambre {{ $loop->iteration }}</small>
                                    <!-- Ajoutez ici d'autres détails de chambre si disponibles -->
                                </div>
                            </div>
                            @endforeach
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
@endsection
