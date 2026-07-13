<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        // Buscamos al colaborador de forma flexible en tu tabla (case-insensitive)
        $loginInput = strtolower($request->login_input);
        $usuario = Usuario::whereRaw('LOWER(Nombre) = ?', [$loginInput])
                          ->orWhereRaw('LOWER(Email) = ?', [$loginInput])
                          ->first();

        // Validar que la cuenta esté activa
        if ($usuario && $usuario->Estado !== 'Activo') {
            return back()->withErrors([
                'login_input' => 'Esta cuenta se encuentra desactivada. Contacta al administrador.',
            ])->withInput($request->only('login_input'));
        }

        // Validar credenciales (soporta hash bcrypt y texto plano legacy)
        if ($usuario) {
            $valido = false;

            if (!Hash::needsRehash($usuario->Numero_de_colaborador)) {
                // Ya está hasheado con bcrypt
                $valido = Hash::check($request->numero_colaborador, $usuario->Numero_de_colaborador);
            } else {
                // Texto plano legacy — comparación directa
                $valido = $usuario->Numero_de_colaborador === $request->numero_colaborador;
                if ($valido) {
                    // Migrar a hash automáticamente en el primer login
                    $usuario->update([
                        'Numero_de_colaborador' => Hash::make($request->numero_colaborador),
                    ]);
                }
            }

            if ($valido) {
                Auth::login($usuario);
                $request->session()->regenerate();

                if ($usuario->Rol === 'Operativo') {
                    return redirect()->intended('/welcome');
                }

                return redirect()->intended('/admin/dashboard');
            }
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