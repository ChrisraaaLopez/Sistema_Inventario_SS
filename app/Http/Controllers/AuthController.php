<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return Auth::check() ? redirect()->route('dashboard') : view('auth.login');
    }

    public function autenticar(Request $request)
    {
        $datos = $request->validate([
            'Nombre_Usuario' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ]);

        $usuario = Usuario::with('rol')->where('Nombre_Usuario', $datos['Nombre_Usuario'])->first();
        if (!$usuario || $usuario->Estatus !== 'Activo' || !Hash::check($datos['password'], $usuario->Password_Hash)) {
            return back()->withErrors(['Nombre_Usuario' => 'Usuario o contraseña incorrectos, o cuenta inactiva.'])->onlyInput('Nombre_Usuario');
        }

        Auth::login($usuario);
        $request->session()->regenerate();
        $usuario->Ultimo_Acceso = now();
        $usuario->save();
        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'Sesión cerrada correctamente.');
    }
}
