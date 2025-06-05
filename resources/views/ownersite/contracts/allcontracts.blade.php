@extends('ownersite.layout')
@section('title', 'Liste des contrats')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Liste des contrats</h1>
        <a href="{{ route('contracts.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Ajouter un contrat
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tous les contrats</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Locataire</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contracts as $contract)
                        <tr>
                            <td>{{ $contract->tenant->name }}</td>
                            <td>
                                @if($contract->start_date instanceof \Carbon\Carbon)
                                    {{ $contract->start_date->format('d/m/Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') }}
                                @endif
                            </td>
                            <td>
                                @if($contract->end_date instanceof \Carbon\Carbon)
                                    {{ $contract->end_date->format('d/m/Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{
                                    $contract->status == 'active' ? 'success' :
                                    ($contract->status == 'terminated' ? 'danger' : 'warning')
                                }}">
                                    {{ ucfirst($contract->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('contracts.show', $contract) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-sm btn-primary" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($contract->status == 'active')
                                <form action="{{ route('contracts.terminate', $contract) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" title="Terminer" onclick="return confirm('Êtes-vous sûr de vouloir terminer ce contrat?')">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('contracts.destroy', $contract) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucun contrat trouvé</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Page level plugins -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
            },
            "order": [[1, "desc"]]
        });
    });
</script>
@endsection
