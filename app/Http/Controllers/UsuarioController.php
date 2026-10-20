<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $usuarios = User::when($request->buscar, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('name', 'like', "%{$buscar}%")
                      ->orWhere('email', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('name')
            ->paginate(15);

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
{
    return view('usuarios.create');
}

public function store(Request $request)
{
    $datos = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'rol' => 'required|in:superadmin,bibliotecario,usuario',
    ]);

    User::create([
        'name' => $datos['name'],
        'email' => $datos['email'],
        'password' => Hash::make($datos['password']),
        'rol' => $datos['rol'],
    ]);

    return redirect()->route('usuarios.index')
        ->with('success', "Usuario {$datos['name']} creado como {$datos['rol']}.");
}
    public function updateRol(Request $request, User $user)
    {
        $request->validate([
            'rol' => 'required|in:superadmin,bibliotecario,usuario',
        ]);

        // Evita quedarte sin ningún superadmin por accidente
        if ($user->id === auth()->id() && $request->rol !== 'superadmin') {
            return back()->with('error', 'No puedes quitarte tu propio rol de superadmin.');
        }

        $user->update(['rol' => $request->rol]);

        return back()->with('success', "Rol de {$user->name} actualizado a {$request->rol}.");
    }
}