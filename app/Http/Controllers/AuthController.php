<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthController extends Controller
{
    // Renderiza la hermosa vista Glassmorphism que preparamos
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesa el inicio de sesión
    public function login(Request $request)
    {
        $request->validate([
            'login_input'        => 'required|string', // Acepta Nombre o Email
            'numero_colaborador' => 'required|string', // Actúa como contraseña
        ]);

        // Buscamos al colaborador de forma flexible en tu tabla
        $usuario = Usuario::where('Nombre', $request->login_input)
                          ->orWhere('Email', $request->login_input)
                          ->first();

        // Si el usuario existe y su número de colaborador coincide con lo tecleado
        if ($usuario && $usuario->Numero_de_colaborador === $request->numero_colaborador) {
            
            // Logueamos al usuario en la sesión web
            Auth::login($usuario);
            $request->session()->regenerate();

            // Redirección inteligente usando tu columna 'Rol'
            if ($usuario->Rol === 'Operativo') {
                return redirect()->intended('/welcome');
            }
            
            return redirect()->intended('/admin/dashboard');
        }

        // Si falla, regresa con error elegante
        return back()->withErrors([
            'login_input' => 'El colaborador o el número de ID no coinciden con los registros de Secrets Tulum.',
        ])->withInput($request->only('login_input'));
    }

    // Cierre de sesión seguro y limpieza de cookies en el iPad
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}