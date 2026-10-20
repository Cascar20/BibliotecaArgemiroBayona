<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    // Catálogo público: aquí se reserva
    public function catalogo(Request $request)
    {
        $libros = Libro::with('ejemplares')
            ->when($request->buscar, function ($query, $buscar) {
                $query->where('titulo', 'like', "%{$buscar}%")
                      ->orWhere('autor', 'like', "%{$buscar}%");
            })
            ->paginate(9);

        return view('libros.catalogo', compact('libros'));
    }

    // ---- Panel del administrador ----

    public function index()
    {
        $libros = Libro::withCount([
            'ejemplares',
            'ejemplares as disponibles_count' => fn ($q) => $q->where('estado', 'disponible'),
        ])->latest()->paginate(10);

        return view('libros.index', compact('libros'));
    }

    public function create()
    {
        return view('libros.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:255|unique:libros,isbn',
            'categoria' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'cantidad' => 'required|integer|min:1|max:50',
            'etiqueta' => 'required|in:rojo,verde,amarillo',
        ]);

        $libro = Libro::create(collect($datos)->except('cantidad')->toArray());
        $libro->agregarEjemplares($datos['cantidad']);

        return redirect()->route('libros.index')
            ->with('success', "Libro agregado con {$datos['cantidad']} ejemplar(es).");
    }

    public function edit(Libro $libro)
    {
        return view('libros.edit', compact('libro'));
    }

    public function update(Request $request, Libro $libro)
{
    $datos = $request->validate([
        'titulo' => 'required|string|max:255',
        'autor' => 'required|string|max:255',
        'isbn' => 'nullable|string|max:255|unique:libros,isbn,' . $libro->id,
        'categoria' => 'nullable|string|max:255',
        'etiqueta' => 'required|in:rojo,verde,amarillo',
        'descripcion' => 'nullable|string',
    ]);

    $libro->update($datos);

    return redirect()->route('libros.index')->with('success', 'Libro actualizado.');
}

    public function destroy(Libro $libro)
    {
        // No se borra si hay copias reservadas o prestadas
        if ($libro->ejemplares()->where('estado', '!=', 'disponible')->exists()) {
            return back()->with('error', 'No se puede eliminar: tiene ejemplares reservados, prestados o dañados.');
        }

        $libro->delete();

        return back()->with('success', 'Libro eliminado.');
    }

    public function agregarEjemplares(Request $request, Libro $libro)
    {
        $request->validate(['cantidad' => 'required|integer|min:1|max:50']);

        $libro->agregarEjemplares((int) $request->cantidad);

        return back()->with('success', "Se agregaron {$request->cantidad} ejemplar(es) a \"{$libro->titulo}\".");
    }
}
