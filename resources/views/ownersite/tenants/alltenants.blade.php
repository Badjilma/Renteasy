@extends('ownersite.layout')
@section('title', 'Liste des locataires')
@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des locataires</h1>
        <a href="{{ route('tenants.create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Ajouter un locataire
        </a>
    </div>

    <!-- Messages de succès -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Messages d'erreur -->
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Liste des locataires -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Liste des locataires</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Options :</div>
                    <a class="dropdown-item" href="#" id="exportPDF"><i class="fas fa-file-pdf mr-2 text-danger"></i>Exporter en PDF</a>
                    <a class="dropdown-item" href="#" id="exportExcel"><i class="fas fa-file-excel mr-2 text-success"></i>Exporter en Excel</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tenantsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>CNI</th>
                            <th>Chambres louées</th>
                            <th>Contrat</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenants as $tenant)
                        <tr>
                            <td>{{ $tenant->name }}</td>
                            <td>{{ $tenant->phone }}</td>
                            <td>{{ $tenant->cni }}</td>
                            <td>
                                @if($tenant->rooms->count() > 0)
                                    <span class="badge badge-success">{{ $tenant->rooms->count() }}</span>
                                @else
                                    <span class="badge badge-warning">Aucune</span>
                                @endif
                            </td>
                            <td>
                                @if($tenant->contract)
                                    <span class="badge badge-info">Actif</span>
                                @else
                                    <span class="badge badge-secondary">Aucun</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('tenants.show', $tenant->id) }}" class="btn btn-info btn-sm" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tenants.edit', $tenant->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                               
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucun locataire enregistré</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection