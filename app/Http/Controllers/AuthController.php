<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Enums\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('Usuario.auth.login'); // 👈 Vista login.blade.php
    }

    public function showRegister()
    {
        return view('Usuario.auth.register'); // 👈 Vista register.blade.php
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $rol = Rol::USUARIO->value;

        // El primer usuario será administrador
        if (Usuario::count() === 0) {
            $rol = Rol::ADMINISTRADOR->value;
        }

        $usuario = Usuario::create([
            'nombre'    => $request->nombre,
            'apellido'  => $request->apellido,
            'correo'    => $request->correo,
            'clave'     => Hash::make($request->password), // 👈 Encriptada
            'rol'       => $rol,
        ]);

        Auth::login($usuario);

        return redirect('/dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // ✅ Laravel ahora usará el método getAuthPassword() de tu modelo
        if (Auth::attempt([
            'correo' => $request->correo,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'correo' => 'Las credenciales no coinciden.',
        ])->onlyInput('correo');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
