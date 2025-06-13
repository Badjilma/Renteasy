@extends('layout')

@section('title', 'Accueil')

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


<div class="py-5 bg-light site-section how-it-works" id="howitworks-section">
    <div class="container">
        <div class="row mb-5 justify-content-center">
            <div class="col-md-7 text-center">
                <h2 class="section-title mb-3">Comment ça marche</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="pr-5">
                    <span class="custom-icon flaticon-house text-primary"></span>
                    <h3 class="text-dark">Trouvez votre logement</h3>
                    <p>Parcourez notre sélection de propriétés et trouvez le logement parfait qui correspond à vos critères et votre budget.</p>
                </div>
            </div>

            <div class="col-md-4 text-center">
                <div class="pr-5">
                    <span class="custom-icon flaticon-coin text-primary"></span>
                    <h3 class="text-dark">Visitez la propriété</h3>
                    <p>Organisez une visite pour découvrir le logement en détail et vous assurer qu'il répond à toutes vos attentes.</p>
                </div>
            </div>

            <div class="col-md-4 text-center">
                <div class="pr-5">
                    <span class="custom-icon flaticon-home text-primary"></span>
                    <h3 class="text-dark">Contactez le propriétaire</h3>
                    <p>Entrez directement en contact avec le propriétaire pour finaliser les détails et conclure votre location.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="site-section" id="properties-section">
    <div class="container">
        <div class="row mb-5 align-items-center">
            <div class="col-md-7 text-left">
                <h2 class="section-title mb-3">Propriétés</h2>
            </div>
            <div class="col-md-5 text-left text-md-right">
                <div class="custom-nav1">
                    <a href="#" class="custom-prev1">Précédent</a><span class="mx-3">/</span><a href="#" class="custom-next1">Suivant</a>
                </div>
            </div>
        </div>

        <div class="owl-carousel nonloop-block-13 mb-5">
            @forelse($properties as $property)
            <div class="property">
                <a href="{{ route('public.property.show', $property->id) }}">
                    <img src="{{ asset('storage/'.$property->principal_photo) }}" alt="{{ $property->name }}" class="img-fluid">
                </a>
                <div class="prop-details p-3">
                    <div><strong class="property-name">{{ $property->name }}</strong></div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="w border-r">{{ $property->rooms->count() }} chambres</span>
                        <span class="w border-r">{{ $property->availability ? 'Disponible' : 'Occupée' }}</span>
                        <span class="w">{{ $property->rules->count() }} règles</span>
                    </div>
                    <div>{{ $property->address }}</div>
                    <div class="mt-2">
                        <a href="{{ route('public.property.show', $property->id) }}" class="btn btn-primary btn-sm">Visiter</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="property">
                <div class="prop-details p-3 text-center">
                    <div><strong>Aucune propriété disponible</strong></div>
                    <div>Revenez plus tard pour découvrir nos propriétés</div>
                </div>
            </div>
            @endforelse
        </div>

        <div class="row justify-content-center">
            <div class="col-md-4">
                <a href="{{ route('public.properties.all') }}" class="btn btn-primary btn-block">Voir tous les propriétés disponibles</a>
            </div>
        </div>
    </div>
</div>


{{-- <section class="site-section border-bottom" id="agents-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-7 text-left">
                <h2 class="section-title mb-3">Agents</h2>
                <p class="lead">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minus minima neque tempora reiciendis.</p>
            </div>
        </div>
        <div class="row">


            <div class="col-md-6 col-lg-4 mb-4">
                <div class="team-member">
                    <figure>
                        <ul class="social">
                            <li><a href="#"><span class="icon-facebook"></span></a></li>
                            <li><a href="#"><span class="icon-twitter"></span></a></li>
                            <li><a href="#"><span class="icon-linkedin"></span></a></li>
                            <li><a href="#"><span class="icon-instagram"></span></a></li>
                        </ul>
                        <img src="images/person_5.jpg" alt="Image" class="img-fluid">
                    </figure>
                    <div class="p-3">
                        <h3 class="mb-2">Kaiara Spencer</h3>
                        <span class="position">Real Estate Agent</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="team-member">
                    <figure>
                        <ul class="social">
                            <li><a href="#"><span class="icon-facebook"></span></a></li>
                            <li><a href="#"><span class="icon-twitter"></span></a></li>
                            <li><a href="#"><span class="icon-linkedin"></span></a></li>
                            <li><a href="#"><span class="icon-instagram"></span></a></li>
                        </ul>
                        <img src="images/person_6.jpg" alt="Image" class="img-fluid">
                    </figure>
                    <div class="p-3">
                        <h3 class="mb-2">Dave Simpson</h3>
                        <span class="position">Real Estate Agent</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="team-member">
                    <figure>
                        <ul class="social">
                            <li><a href="#"><span class="icon-facebook"></span></a></li>
                            <li><a href="#"><span class="icon-twitter"></span></a></li>
                            <li><a href="#"><span class="icon-linkedin"></span></a></li>
                            <li><a href="#"><span class="icon-instagram"></span></a></li>
                        </ul>
                        <img src="images/person_7.jpg" alt="Image" class="img-fluid">
                    </figure>
                    <div class="p-3">
                        <h3 class="mb-2">Ben Thompson</h3>
                        <span class="position">Real Estate Agent</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="team-member">
                    <figure>
                        <ul class="social">
                            <li><a href="#"><span class="icon-facebook"></span></a></li>
                            <li><a href="#"><span class="icon-twitter"></span></a></li>
                            <li><a href="#"><span class="icon-linkedin"></span></a></li>
                            <li><a href="#"><span class="icon-instagram"></span></a></li>
                        </ul>
                        <img src="images/person_8.jpg" alt="Image" class="img-fluid">
                    </figure>
                    <div class="p-3">
                        <h3 class="mb-2">Kyla Stewart</h3>
                        <span class="position">Real Estate Agent</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="team-member">
                    <figure>
                        <ul class="social">
                            <li><a href="#"><span class="icon-facebook"></span></a></li>
                            <li><a href="#"><span class="icon-twitter"></span></a></li>
                            <li><a href="#"><span class="icon-linkedin"></span></a></li>
                            <li><a href="#"><span class="icon-instagram"></span></a></li>
                        </ul>
                        <img src="images/person_5.jpg" alt="Image" class="img-fluid">
                    </figure>
                    <div class="p-3">
                        <h3 class="mb-2">Kaiara Spencer</h3>
                        <span class="position">Real Estate Agent</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="team-member">
                    <figure>
                        <ul class="social">
                            <li><a href="#"><span class="icon-facebook"></span></a></li>
                            <li><a href="#"><span class="icon-twitter"></span></a></li>
                            <li><a href="#"><span class="icon-linkedin"></span></a></li>
                            <li><a href="#"><span class="icon-instagram"></span></a></li>
                        </ul>
                        <img src="images/person_6.jpg" alt="Image" class="img-fluid">
                    </figure>
                    <div class="p-3">
                        <h3 class="mb-2">Dave Simpson</h3>
                        <span class="position">Real Estate Agent</span>
                    </div>
                </div>
            </div>


        </div>
    </div>
</section> --}}

{{-- A propos --}}
<section class="site-section" id="about-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="owl-carousel slide-one-item-alt">
                    <img src="./template/images/property_1.jpg" alt="Image" class="img-fluid">
                    <img src="./template/images/property_2.jpg" alt="Image" class="img-fluid">
                    <img src="./template/images/property_3.jpg" alt="Image" class="img-fluid">
                    <img src="./template/images/property_4.jpg" alt="Image" class="img-fluid">
                </div>
                <div class="custom-direction">
                    <a href="#" class="custom-prev">Précédent</a><a href="#" class="custom-next">Suivant</a>
                </div>
            </div>
            <div class="col-lg-5 ml-auto">
                <h2 class="section-title mb-3">RentEasy - La Meilleure Plateforme de Location Immobilière</h2>
                <p class="lead">Trouvez votre logement idéal en toute simplicité avec RentEasy.</p>
                <p>RentEasy révolutionne la recherche de logements en connectant directement locataires et propriétaires. Notre plateforme moderne offre une expérience fluide et sécurisée pour tous vos besoins de location immobilière.</p>
                <ul class="list-unstyled ul-check success">
                    <li>Recherche avancée et personnalisée</li>
                    <li>Contact direct avec les propriétaires</li>
                    <li>Visites virtuelles disponibles</li>
                    <li>Processus de location simplifié</li>
                    <li>Support client dédié</li>
                </ul>
                <p><a href="#services-section" class="btn btn-primary mr-2 mb-2">En Savoir Plus</a></p>
            </div>
        </div>
    </div>
</section>
{{-- A propos --}}

{{-- services --}}
<section class="site-section border-bottom bg-light" id="services-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title mb-3">Nos Services</h2>
            </div>
        </div>
        <div class="row align-items-stretch">
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-4" data-aos="fade-up">
                <div class="unit-4 d-flex">
                    <div class="unit-4-icon mr-4"><span class="text-primary flaticon-house"></span></div>
                    <div>
                        <h3>Recherche de Propriétés</h3>
                        <p>Explorez notre large catalogue de propriétés avec photos détaillées, informations complètes sur chaque chambre et caractéristiques. Contactez directement les propriétaires pour organiser une visite.</p>
                        <p><a href="#">En savoir plus</a></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="unit-4 d-flex">
                    <div class="unit-4-icon mr-4"><span class="text-primary flaticon-coin"></span></div>
                    <div>
                        <h3>Gestion des Locations</h3>
                        <p>Une fois le contrat signé, les propriétaires peuvent facilement ajouter et gérer leurs locations depuis leur tableau de bord personnalisé. Suivi simplifié des paiements et des locataires.</p>
                        <p><a href="#">En savoir plus</a></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="unit-4 d-flex">
                    <div class="unit-4-icon mr-4"><span class="text-primary flaticon-home"></span></div>
                    <div>
                        <h3>Visite Virtuelle</h3>
                        <p>Visitez les propriétés depuis chez vous grâce à nos visites virtuelles détaillées. Découvrez chaque pièce, chaque chambre avec des images haute qualité et des informations précises.</p>
                        <p><a href="#">En savoir plus</a></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4 mb-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="unit-4 d-flex">
                    <div class="unit-4-icon mr-4"><span class="text-primary flaticon-flat"></span></div>
                    <div>
                        <h3>Publication de Propriétés</h3>
                        <p>Propriétaires, publiez facilement vos biens immobiliers avec photos, descriptions détaillées de chaque chambre et informations de contact pour attirer les meilleurs locataires.</p>
                        <p><a href="#">En savoir plus</a></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="unit-4 d-flex">
                    <div class="unit-4-icon mr-4"><span class="text-primary flaticon-location"></span></div>
                    <div>
                        <h3>Demandes de Maintenance</h3>
                        <p>Les locataires peuvent soumettre leurs demandes de maintenance directement via la plateforme. Les propriétaires reçoivent instantanément les notifications pour une intervention rapide.</p>
                        <p><a href="#">En savoir plus</a></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-4" data-aos="fade-up" data-aos-delay="500">
                <div class="unit-4 d-flex">
                    <div class="unit-4-icon mr-4"><span class="text-primary flaticon-mobile-phone"></span></div>
                    <div>
                        <h3>Application Renteasy</h3>
                        <p>Accédez à tous nos services depuis votre smartphone. Recherchez des propriétés, gérez vos locations, soumettez des demandes de maintenance, le tout dans une application intuitive.</p>
                        <p><a href="#">En savoir plus</a></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
{{-- services --}}

{{-- temoignages --}}
<section class="site-section testimonial-wrap" id="testimonials-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title mb-3">Ce que disent nos clients...</h2>
            </div>
        </div>
    </div>
    <div class="slide-one-item home-slider owl-carousel">
        <div>
            <div class="testimonial">

                <blockquote class="mb-5">
                    <p>&ldquo;Renteasy a complètement transformé ma façon de gérer mes propriétés. Grâce à leur plateforme, je trouve facilement des locataires fiables et la gestion des paiements est devenue si simple. Je recommande vivement leurs services à tous les propriétaires de Lomé.&rdquo;</p>
                </blockquote>

                <figure class="mb-4 d-flex align-items-center justify-content-center">
                    <div><img src="./template/images/person_3.jpg" alt="Image" class="w-50 img-fluid mb-3"></div>
                    <p>Kofi Mensah</p>
                </figure>
            </div>
        </div>
        <div>
            <div class="testimonial">

                <blockquote class="mb-5">
                    <p>&ldquo;En tant que locataire, j'ai trouvé mon appartement idéal en quelques clics sur Renteasy. Le processus de location était transparent et rapide. L'équipe de maintenance intervient toujours rapidement quand j'ai un problème. C'est vraiment un service de qualité !&rdquo;</p>
                </blockquote>
                <figure class="mb-4 d-flex align-items-center justify-content-center">
                    <div><img src="./template/images/person_2.jpg" alt="Image" class="w-50 img-fluid mb-3"></div>
                    <p>Akosua Adjei</p>
                </figure>

            </div>
        </div>

        <div>
            <div class="testimonial">

                <blockquote class="mb-5">
                    <p>&ldquo;Depuis que j'utilise Renteasy pour mes investissements immobiliers au Togo, ma rentabilité a considérablement augmenté. Leur analyse de marché et leurs conseils m'ont aidé à prendre les bonnes décisions. Une plateforme indispensable pour tout investisseur sérieux.&rdquo;</p>
                </blockquote>
                <figure class="mb-4 d-flex align-items-center justify-content-center">
                    <div><img src="./template/images/person_4.jpg" alt="Image" class="w-50 img-fluid mb-3"></div>
                    <p>Mamadou Traoré</p>
                </figure>

            </div>
        </div>

        <div>
            <div class="testimonial">

                <blockquote class="mb-5">
                    <p>&ldquo;La gestion de mes nombreuses propriétés était un cauchemar avant Renteasy. Maintenant, tout est centralisé : suivi des loyers, demandes de maintenance, communication avec les locataires. Cette application a révolutionné mon activité immobilière à Lomé.&rdquo;</p>
                </blockquote>
                <figure class="mb-4 d-flex align-items-center justify-content-center">
                    <div><img src="./template/images/person_4.jpg" alt="Image" class="w-50 img-fluid mb-3"></div>
                    <p>Afi Kpenou</p>
                </figure>

            </div>
        </div>

    </div>
</section>
{{-- temoignages --}}

{{-- annonces et pub --}}
<section class="site-section" id="news-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title mb-3">Annonces &amp; Pubs</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-4">
                <div class="h-entry">
                    <a href="#"><img src="./template/images/property_1.jpg" alt="Image" class="img-fluid"></a>
                    <h2 class="font-size-regular"><a href="#" class="text-dark">Plus de 20 Propriétés Immobilières Disponibles</a></h2>
                    <div class="meta mb-4">Marie Kouassi <span class="mx-2">&bullet;</span> 18 Jan, 2025<span class="mx-2">&bullet;</span> <a href="#">Actualités</a></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-4">
                <div class="h-entry">
                    <a href="#"><img src="./template/images/property_2.jpg" alt="Image" class="img-fluid"></a>
                    <h2 class="font-size-regular"><a href="#" class="text-dark">Nouvelles Opportunités d'Investissement Immobilier</a></h2>
                    <div class="meta mb-4">Kofi Mensah <span class="mx-2">&bullet;</span> 15 Jan, 2025<span class="mx-2">&bullet;</span> <a href="#">Actualités</a></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-4">
                <div class="h-entry">
                    <a href="#"><img src="./template/images/property_3.jpg" alt="Image" class="img-fluid"></a>
                    <h2 class="font-size-regular"><a href="#" class="text-dark">Services de Maintenance et Gestion Immobilière</a></h2>
                    <div class="meta mb-4">Fatou Diallo <span class="mx-2">&bullet;</span> 12 Jan, 2025<span class="mx-2">&bullet;</span> <a href="#">Actualités</a></div>
                </div>
            </div>

        </div>
    </div>
</section>
{{-- annonces et pub --}}

{{-- contact --}}
<section class="site-section bg-light bg-image" id="contact-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <!-- <h3 class="section-sub-title">Prendre</h3> -->
                <h2 class="section-title mb-3">Contactez-nous</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7 mb-5">

                <form action="#" class="p-5 bg-white">

                    <h2 class="h4 text-black mb-5">Formulaire de Contact</h2>

                    <div class="row form-group">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="text-black" for="fname">Prénom</label>
                            <input type="text" id="fname" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="text-black" for="lname">Nom</label>
                            <input type="text" id="lname" class="form-control">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-12">
                            <label class="text-black" for="email">Email</label>
                            <input type="email" id="email" class="form-control">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-12">
                            <label class="text-black" for="subject">Sujet</label>
                            <input type="subject" id="subject" class="form-control">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-12">
                            <label class="text-black" for="message">Message</label>
                            <textarea name="message" id="message" cols="30" rows="7" class="form-control" placeholder="Écrivez vos notes ou questions ici..."></textarea>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-12">
                            <input type="submit" value="Envoyer le Message" class="btn btn-primary btn-md text-white">
                        </div>
                    </div>

                </form>
            </div>
            <div class="col-md-5">

                <div class="p-4 mb-3 bg-white">
                    <p class="mb-0 font-weight-bold">Adresse</p>
                    <p class="mb-4">123 Rue de la Paix, Lomé, Maritime, Togo</p>

                    <p class="mb-0 font-weight-bold">Téléphone</p>
                    <p class="mb-4"><a href="#">+228 22 12 34 56</a></p>

                    <p class="mb-0 font-weight-bold">Adresse Email</p>
                    <p class="mb-0"><a href="#">contact@renteasy.tg</a></p>

                </div>

            </div>
        </div>
    </div>
</section>
{{-- contact --}}
@endsection
