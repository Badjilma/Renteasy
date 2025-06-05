{{-- Dans votre vue showcontract.blade.php --}}
@extends('ownersite.layout')
@section('title', 'Voir le contrat')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Détails du contrat</h1>
        <div>
            <a href="{{ route('contracts.edit', $contract) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Modifier
            </a>
            @if($contract->status == 'active')
            <form action="{{ route('contracts.terminate', $contract) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"
                        onclick="return confirm('Êtes-vous sûr de vouloir terminer ce contrat?')">
                    <i class="fas fa-ban fa-sm text-white-50"></i> Terminer
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Informations du contrat -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations générales</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Locataire</th>
                                    <td>{{ $contract->tenant->name }}</td>
                                </tr>
                                <tr>
                                    <th>CNI</th>
                                    <td>{{ $contract->tenant->cni }}</td>
                                </tr>
                                <tr>
                                    <th>Téléphone</th>
                                    <td>{{ $contract->tenant->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Date de début</th>
                                    <td>{{ $contract->start_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Date de fin</th>
                                    <td>{{ $contract->end_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        <span class="badge badge-{{
                                            $contract->status == 'active' ? 'success' :
                                            ($contract->status == 'terminated' ? 'danger' : 'warning')
                                        }}">
                                            {{ ucfirst($contract->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Créé le</th>
                                    <td>{{ $contract->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Mis à jour le</th>
                                    <td>{{ $contract->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document du contrat -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Document du contrat</h6>
                    @if($contract->document && Storage::disk('public')->exists($contract->document))
                    <div>
                        <a href="{{ Storage::url($contract->document) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Voir
                        </a>
                        <a href="{{ Storage::url($contract->document) }}" download class="btn btn-sm btn-success">
                            <i class="fas fa-download"></i> Télécharger
                        </a>
                    </div>
                    @endif
                </div>
                <div class="card-body text-center">
                    @if($contract->document && Storage::disk('public')->exists($contract->document))
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe src="{{ Storage::url($contract->document) }}" class="embed-responsive-item"
                                    style="width: 100%; height: 400px;"></iframe>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Le document du contrat n'est pas disponible ou a été supprimé.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
