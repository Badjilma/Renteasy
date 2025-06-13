<footer class="site-footer">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-6 col-xl-2">
                <h2 class="mb-0 footer-logo m-0 p-0">
                    <a href="index.html" class="mb-0">
                        <img src="{{asset('template/images/Renteasy.png')}}" alt="Logo" height="100px">
                    </a>
                </h2>
                <p class="mt-3">Avec Renteasy, simplifier la gestions de vos propriétés</p>
            </div>

            <div class="col-12 col-md-10 d-none d-xl-block">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="footer-heading mb-4">Liens Rapides</h2>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('home') }}">Accueil</a></li>
                            <li><a href="{{ route('public.properties.all') }}">Propriétés</a></li>
                            <li><a href="#news-section">Maintenance</a></li>
                            <li><a href="#contact-section">Contact</a></li>
                            <li><a href="#">Mentions légales</a></li>
                            <li><a href="#">Politique de confidentialité</a></li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <h2 class="footer-heading mb-4">Abonnez-vous à la Newsletter</h2>
                        <form action="#" method="post" class="footer-subscribe">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control border-secondary text-white bg-transparent" placeholder="Entrez votre email" aria-label="Entrez votre email" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary text-black" type="button" id="button-addon2">Envoyer</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-4">
                        <h2 class="footer-heading mb-4">Suivez-nous</h2>
                        <a href="#" class="pl-0 pr-3"><span class="icon-facebook"></span></a>
                        <a href="#" class="pl-3 pr-3"><span class="icon-twitter"></span></a>
                        <a href="#" class="pl-3 pr-3"><span class="icon-instagram"></span></a>
                        <a href="#" class="pl-3 pr-3"><span class="icon-linkedin"></span></a>
                    </div>
                </div>
            </div>

            <div class="col-6 d-inline-block d-xl-none ml-md-0 py-3">
                <a href="#" class="site-menu-toggle js-menu-toggle text-white float-right">
                    <span class="icon-menu h3"></span>
                </a>
            </div>

        </div>

        <div class="row pt-5 mt-5 text-center">
            <div class="col-md-12">
                <div class="border-top pt-5">
                    <p>
                        Copyright &copy;<script>document.write(new Date().getFullYear());</script> Tous droits réservés | Renteasy - Gestion immobilière simplifiée
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
