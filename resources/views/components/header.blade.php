<header class="site-navbar py-4 js-sticky-header site-navbar-target" role="banner">

    <div class="container">
        <div class="row align-items-center">

            <div class="col-6 col-xl-2">
                <h1 class="mb-0 site-logo m-0 p-0"><a href="index.html" class="mb-0"><img src="{{asset('template/images/Renteasy.png')}}" alt="Logo" height="100px"></a></h1>
            </div>

            <div class="col-12 col-md-10 d-none d-xl-block">
                <nav class="site-navigation position-relative text-right" role="navigation">

                    <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                        <li><a href="{{ route('home') }}" class="nav-link">Accueil</a></li>
                        <li><a href="{{ route('public.properties.all') }}" class="nav-link">Propriétés</a></li>
                        <li><a href="#news-section" class="nav-link">Maintenance</a></li>
                        <li><a href="#contact-section" class="nav-link">Contact</a></li>
                    </ul>
                </nav>
            </div>


            <div class="col-6 d-inline-block d-xl-none ml-md-0 py-3"><a href="#" class="site-menu-toggle js-menu-toggle text-black float-right"><span class="icon-menu h3"></span></a></div>

        </div>
    </div>

</header>
