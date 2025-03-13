<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>RENTEASY - Inscription</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('owner/css/sb-admin-2.min.css')}}" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image">
                        <img src="{{asset('owner/img/login.png')}}" alt="Maison" height="500px">
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Créeer votre compte</h1>
                            </div>
                            <form class="user" method="POST" action="{{ route('register.attempt') }}">
                            @csrf
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input name="name" type="text" class="form-control form-control-user" id="exampleFirstName"
                                            placeholder="Nom Complet">
                                    </div>
                                    <div class="col-sm-6">
                                        <input name="phone" type="number" class="form-control form-control-user" id="exampleLastName"
                                            placeholder="Numéro de téléphone">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input name="email" type="email" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Email">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input name="password" type="password" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="Mot de passe">
                                    </div>
                                    <div class="col-sm-6">
                                        <input name="password" type="password" class="form-control form-control-user"
                                            id="exampleRepeatPassword" placeholder="Confirmation de mot de passe">
                                    </div>
                                </div>
                                <button type="submit"  class="btn btn-primary btn-user btn-block">Inscription</button>
                                <hr>
                            </form>
                            <hr>
                            <!-- <div class="text-center">
                                <a class="small" href="forgot-password.html">Forgot Password?</a>
                            </div> -->
                            <div class="text-center">
                                <a class="small" href="{{route('login.form')}}">Vous avez déjà un compte? Connexion!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('owner/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('owner/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('owner/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('owner/js/sb-admin-2.min.js')}}"></script>

</body>

</html>