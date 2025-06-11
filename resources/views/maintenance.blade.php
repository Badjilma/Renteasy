@extends('layout')

@section('title', 'Chambres de ' . $property->name)

@section('content')
<div class="site-blocks-cover overlay" style="background-image: url('template/images/hero_1.jpg');" data-aos="fade" id="home-section">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-8 mt-lg-5 text-center">
                <h1>Requêtes de maintenance des chambres</h1>
                <h2 class="text-white">Service réservé aux locataires</h2>
                <p class="mb-5 text-white">
                    <i class="icon-info"></i> Ce service est exclusivement destiné aux locataires pour signaler des problèmes concernant leur chambre. Les propriétaires recevront ces requêtes pour traitement.
                </p>
            </div>
        </div>
    </div>
    <a href="#rooms-section" class="smoothscroll arrow-down"><span class="icon-arrow_downward"></span></a>
</div>

@endsection
