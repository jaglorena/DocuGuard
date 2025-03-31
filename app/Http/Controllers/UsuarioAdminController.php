<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Enums\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioAdminController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();
        return view('Usuario.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('Usuario.usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|confirmed|min:6',
            'rol' => 'required'
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'correo' => $request->correo,
            'clave' => Hash::make($request->password),
            'rol' => $request->rol,
        ]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado');
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('Usuario.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id_usuario,
            'rol' => 'required',
        ]);

        $usuario->update($request->only('nombre', 'apellido', 'correo', 'rol'));

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado');
    }

    public function destroy($id)
    {
        Usuario::destroy($id);
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado');
    }

    public function cambiarClave(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $usuario = Usuario::findOrFail($id);
        $usuario->update(['clave' => Hash::make($request->password)]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Contraseña actualizada');
    }
}
