@extends('layout')

@section('title', 'Toutes les propriétés disponibles')

@section('content')

    <div class="site-blocks-cover overlay" style="background-image: url('template/images/hero_1.jpg');" data-aos="fade"
        id="home-section">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-6 mt-lg-5 text-center">
                    <h2>Toutes les propriétés disponibles sur Renteasy</h2>
                    <p class="mb-5">Découvrez notre sélection complète de propriétés disponibles à la location.
                        Explorez des chambres, appartements et maisons dans différents quartiers,
                        avec des photos détaillées, des descriptions complètes et des prix transparents.
                        Trouvez le logement parfait qui correspond à vos besoins et votre budget !
                    </p>
                </div>
            </div>
        </div>
        <a href="#properties-list-section" class="smoothscroll arrow-down"><span class="icon-arrow_downward"></span></a>
    </div>

    <!-- Section des filtres -->
    <div class="site-section bg-light" id="properties-list-section">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtrer les propriétés</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('public.properties.all') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="search">Rechercher</label>
                                            <input type="text" name="search" id="search" class="form-control"
                                                   placeholder="Nom ou adresse..." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="availability">Disponibilité</label>
                                            <select name="availability" id="availability" class="form-control">
                                                <option value="">Toutes</option>
                                                <option value="1" {{ request('availability') == '1' ? 'selected' : '' }}>Disponibles</option>
                                                <option value="0" {{ request('availability') == '0' ? 'selected' : '' }}>Occupées</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="min_rooms">Nombre de chambres min.</label>
                                            <select name="min_rooms" id="min_rooms" class="form-control">
                                                <option value="">Aucun minimum</option>
                                                @for($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}" {{ request('min_rooms') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search"></i> Filtrer
                                                </button>
                                                <a href="{{ route('public.properties.all') }}" class="btn btn-secondary ml-2">
                                                    <i class="fas fa-times"></i> Reset
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Nos propriétés ({{ $properties->total() }} résultats)</h3>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary active" onclick="switchView('grid')">
                                <i class="fas fa-th"></i> Grille
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="switchView('list')">
                                <i class="fas fa-list"></i> Liste
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vue grille (par défaut) -->
            <div id="grid-view" class="row">
                @forelse($properties as $property)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card property-card h-100">
                        <div class="position-relative">
                            <a href="{{ route('public.property.show', $property->id) }}">
                                <img src="{{ asset('storage/'.$property->principal_photo) }}"
                                     alt="{{ $property->name }}"
                                     class="card-img-top property-image"
                                     style="height: 200px; object-fit: cover;">
                            </a>
                            <div class="position-absolute" style="top: 10px; right: 10px;">
                                @if($property->availability)
                                    <span class="badge badge-success">Disponible</span>
                                @else
                                    <span class="badge badge-danger">Occupée</span>
                                @endif
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $property->name }}</h5>
                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-map-marker-alt"></i> {{ $property->address }}
                            </p>

                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <div class="stat-item">
                                        <i class="fas fa-bed text-primary"></i>
                                        <small class="d-block">{{ $property->rooms->count() }} chambres</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-item">
                                        <i class="fas fa-users text-primary"></i>
                                        <small class="d-block">{{ $property->rooms->where('availability', true)->count() }} libres</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-item">
                                        <i class="fas fa-list-ul text-primary"></i>
                                        <small class="d-block">{{ $property->rules->count() }} règles</small>
                                    </div>
                                </div>
                            </div>

                            @if($property->description)
                            <p class="card-text">{{ Str::limit($property->description, 100) }}</p>
                            @endif

                            <div class="mt-auto">
                                <a href="{{ route('public.property.show', $property->id) }}"
                                   class="btn btn-primary btn-block">
                                    <i class="fas fa-eye"></i> Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-home fa-3x text-muted mb-3"></i>
                            <h4>Aucune propriété trouvée</h4>
                            <p class="text-muted">Aucune propriété ne correspond à vos critères de recherche.</p>
                            <a href="{{ route('public.properties.all') }}" class="btn btn-primary">
                                <i class="fas fa-refresh"></i> Voir toutes les propriétés
                            </a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Vue liste (cachée par défaut) -->
            <div id="list-view" class="d-none">
                @forelse($properties as $property)
                <div class="card mb-3">
                    <div class="row no-gutters">
                        <div class="col-md-3">
                            <a href="{{ route('public.property.show', $property->id) }}">
                                <img src="{{ asset('storage/'.$property->principal_photo) }}"
                                     alt="{{ $property->name }}"
                                     class="card-img property-image"
                                     style="height: 200px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="col-md-9">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="card-title">{{ $property->name }}</h5>
                                    @if($property->availability)
                                        <span class="badge badge-success">Disponible</span>
                                    @else
                                        <span class="badge badge-danger">Occupée</span>
                                    @endif
                                </div>

                                <p class="card-text text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ $property->address }}
                                </p>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <i class="fas fa-bed text-primary"></i>
                                        <small>{{ $property->rooms->count() }} chambres</small>
                                    </div>
                                    <div class="col-sm-3">
                                        <i class="fas fa-users text-primary"></i>
                                        <small>{{ $property->rooms->where('availability', true)->count() }} libres</small>
                                    </div>
                                    <div class="col-sm-3">
                                        <i class="fas fa-list-ul text-primary"></i>
                                        <small>{{ $property->rules->count() }} règles</small>
                                    </div>
                                </div>

                                @if($property->description)
                                <p class="card-text">{{ Str::limit($property->description, 150) }}</p>
                                @endif

                                <a href="{{ route('public.property.show', $property->id) }}"
                                   class="btn btn-primary">
                                    <i class="fas fa-eye"></i> Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-home fa-3x text-muted mb-3"></i>
                        <h4>Aucune propriété trouvée</h4>
                        <p class="text-muted">Aucune propriété ne correspond à vos critères de recherche.</p>
                        <a href="{{ route('liste.properties.all') }}" class="btn btn-primary">
                            <i class="fas fa-refresh"></i> Voir toutes les propriétés
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($properties->hasPages())
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-center">
                        {{ $properties->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

@endsection

@section('scripts')
<script>
function switchView(viewType) {
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const buttons = document.querySelectorAll('.btn-group button');

    buttons.forEach(btn => btn.classList.remove('active'));

    if (viewType === 'grid') {
        gridView.classList.remove('d-none');
        listView.classList.add('d-none');
        buttons[0].classList.add('active');
    } else {
        gridView.classList.add('d-none');
        listView.classList.remove('d-none');
        buttons[1].classList.add('active');
    }

    // Sauvegarder la préférence
    localStorage.setItem('viewPreference', viewType);
}

// Charger la préférence au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('viewPreference');
    if (savedView) {
        switchView(savedView);
    }
});
</script>

<style>
.property-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.property-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.property-image {
    transition: transform 0.3s ease;
}

.property-card:hover .property-image {
    transform: scale(1.05);
}

.stat-item {
    padding: 0.5rem 0;
}

.stat-item i {
    font-size: 1.2rem;
    margin-bottom: 0.25rem;
}

.card-img-top {
    border-radius: 0.375rem 0.375rem 0 0;
}

@media (max-width: 768px) {
    .btn-group {
        width: 100%;
        margin-top: 1rem;
    }

    .btn-group button {
        flex: 1;
    }
}
</style>
@endsection
