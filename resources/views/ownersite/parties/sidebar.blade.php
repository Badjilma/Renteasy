<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-house-user"></i>
        </div>
        <div class="sidebar-brand-text mx-3">RENTEASY <sup>2025</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('accueilindex') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Tableau de bord</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Propriétés -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProperties"
            aria-expanded="true" aria-controls="collapseProperties">
            <i class="fas fa-house-user"></i>
            <span>Propriétés</span>
        </a>
        <div id="collapseProperties" class="collapse" aria-labelledby="headingProperties" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menus</h6>
                <a class="collapse-item" href="{{ route('properties.form') }}">Ajouter propriété</a>
                <a class="collapse-item" href="{{ route('properties.all') }}">Voir mes propriétés</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Locataires -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTenants"
            aria-expanded="true" aria-controls="collapseTenants">
            <i class="fas fa-users"></i>
            <span>Mes locataires</span>
        </a>
        <div id="collapseTenants" class="collapse" aria-labelledby="headingTenants" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menus</h6>
                <a class="collapse-item" href="{{ route('tenants.create') }}">Ajouter un locataire</a>
                <a class="collapse-item" href="{{ route('tenants.all') }}">Voir tout</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Contrats -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseContracts"
            aria-expanded="true" aria-controls="collapseContracts">
            <i class="fas fa-file-contract"></i>
            <span>Contrats</span>
        </a>
        <div id="collapseContracts" class="collapse" aria-labelledby="headingContracts" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menus</h6>
                <a class="collapse-item" href="{{ route('contracts.create') }}">Ajouter un contrat</a>
                <a class="collapse-item" href="{{ route('contracts.all') }}">Voir tous les contrats</a>
            </div>
        </div>
    </li>

      <!-- Nav Item - Maintenances -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaintenances"
            aria-expanded="true" aria-controls="collapseMaintenances">
            <i class="fas fa-file-contract"></i>
            <span>Maintenance</span>
        </a>
        <div id="collapseMaintenances" class="collapse" aria-labelledby="headingMaintenances" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menus</h6>
                <a class="collapse-item" href="{{ route('maintenance.index') }}">Boite des Maintenances</a>
                {{-- <a class="collapse-item" href="{{ route('contracts.all') }}">Voir tous les contrats</a> --}}
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
