<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    //fonction pour afficher la page de l'inscription
    public function showRegisterForm()
    {
        return view('ownersite.register');
    }
    //fonction pour afficher la page de connexion
    public function showLoginForm()
    {
        return view('ownersite.login');
    }
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed', // Ceci vérifie si password_confirmation correspond
        'phone' => 'required|string|max:8',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
    ]);

    return redirect()->route('login.form')->with('success', 'Vous êtes enregistré avec succès. Veuillez vous connecter.');
}

public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        // Si la connexion réussit, redirigez vers la page d'index
        return redirect('/indexOwner')->with('success', 'Connexion réussie !');
    }

    // Si la connexion échoue, retournez à la page de connexion avec une erreur
    return back()->withErrors([
        'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
    ])->withInput($request->only('email'));
}
}
