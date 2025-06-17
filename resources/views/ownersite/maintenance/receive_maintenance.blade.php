@extends('ownersite.layout')
@section('title', 'Demande de maintenance')
@section('content')

<div class="site-blocks-cover overlay" data-aos="fade" id="home-section">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-8 mt-lg-5 text-center">
                <h1>Gestion des Demandes de Maintenance</h1>
                <p class="mb-5 text-black">
                    <i class="icon-wrench"></i> Recevez et gérez toutes les demandes de maintenance de vos propriétés
                </p>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-12">

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Toutes les demandes de maintenance</h3>
                        <div>
                            <span class="badge badge-warning">{{ $requests->where('status', 'pending')->count() }} En attente</span>
                            <span class="badge badge-info">{{ $requests->where('status', 'in_progress')->count() }} En cours</span>
                            <span class="badge badge-success">{{ $requests->where('status', 'completed')->count() }} Terminées</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Locataire</th>
                                        <th>Propriété</th>
                                        <th>Chambre</th>
                                        <th>Description</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                    <tr>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <strong>{{ $request->tenant->first_name }} {{ $request->tenant->last_name }}</strong><br>
                                            <small class="text-muted">{{ $request->tenant->email }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $request->property->name }}</strong><br>
                                            <small class="text-muted">{{ $request->property->address }}</small>
                                        </td>
                                        <td>
                                            Chambre <strong>{{ $request->room->room_number }}</strong><br>
                                            <small class="text-muted">{{ $request->room->type }}</small>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $request->description }}">
                                                {{ $request->description }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $request->status_badge }}">
                                                @switch($request->status)
                                                    @case('pending')
                                                        En attente
                                                        @break
                                                    @case('in_progress')
                                                        En cours
                                                        @break
                                                    @case('completed')
                                                        Terminée
                                                        @break
                                                    @case('cancelled')
                                                        Annulée
                                                        @break
                                                    @default
                                                        {{ $request->status }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                                                    Changer statut
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if($request->status !== 'pending')
                                                    <form method="POST" action="{{ route('maintenance.status', $request->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" class="dropdown-item">En attente</button>
                                                    </form>
                                                    @endif

                                                    @if($request->status !== 'in_progress')
                                                    <form method="POST" action="{{ route('maintenance.status', $request->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="in_progress">
                                                        <button type="submit" class="dropdown-item">En cours</button>
                                                    </form>
                                                    @endif

                                                    @if($request->status !== 'completed')
                                                    <form method="POST" action="{{ route('maintenance.status', $request->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="dropdown-item">Terminée</button>
                                                    </form>
                                                    @endif

                                                    @if($request->status !== 'cancelled')
                                                    <form method="POST" action="{{ route('maintenance.status', $request->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="dropdown-item text-danger">Annuler</button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $requests->links() }}
                        </div>

                        @else
                        <div class="text-center py-5">
                            <i class="icon-wrench" style="font-size: 48px; color: #ccc;"></i>
                            <h4>Aucune demande de maintenance</h4>
                            <p class="text-muted">Il n'y a actuellement aucune demande de maintenance.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
