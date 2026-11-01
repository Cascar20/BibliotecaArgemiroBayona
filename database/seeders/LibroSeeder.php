<?php

namespace Database\Seeders;

use App\Models\Libro;
use Illuminate\Database\Seeder;

class LibroSeeder extends Seeder
{
    public function run(): void
    {
        $libros = [
            // [título, autor, categoría, etiqueta, ejemplares, descripción]
            ['Cien años de soledad', 'Gabriel García Márquez', 'Literatura', 'rojo', 3, 'Novela cumbre del realismo mágico: la saga de la familia Buendía en Macondo.'],
            ['El amor en los tiempos del cólera', 'Gabriel García Márquez', 'Literatura', 'rojo', 2, 'Historia de un amor que espera más de cincuenta años.'],
            ['Crónica de una muerte anunciada', 'Gabriel García Márquez', 'Literatura', 'rojo', 2, 'Reconstrucción de un crimen anunciado en un pueblo caribeño.'],
            ['La vorágine', 'José Eustasio Rivera', 'Literatura', 'rojo', 2, 'Novela clásica colombiana sobre la selva y el caucho.'],
            ['Don Quijote de la Mancha', 'Miguel de Cervantes', 'Literatura', 'rojo', 2, 'Las aventuras del ingenioso hidalgo y su escudero Sancho Panza.'],
            ['Orgullo y prejuicio', 'Jane Austen', 'Literatura', 'rojo', 2, 'Clásico de la novela inglesa sobre Elizabeth Bennet y el señor Darcy.'],
            ['1984', 'George Orwell', 'Literatura', 'rojo', 2, 'Novela distópica sobre vigilancia y control del Estado.'],
            ['El principito', 'Antoine de Saint-Exupéry', 'Literatura', 'rojo', 3, 'Cuento filosófico sobre la amistad y la mirada de la infancia.'],

            ['Cálculo de una variable', 'James Stewart', 'Matemáticas', 'rojo', 4, 'Texto base de cálculo diferencial e integral.'],
            ['Física universitaria', 'Sears y Zemansky', 'Física', 'rojo', 3, 'Física general para ciencias e ingeniería.'],
            ['Ingeniería de software', 'Ian Sommerville', 'Ingeniería de sistemas', 'rojo', 3, 'Procesos, requisitos, diseño y pruebas de software.'],
            ['Fundamentos de bases de datos', 'Silberschatz, Korth y Sudarshan', 'Ingeniería de sistemas', 'rojo', 3, 'Modelo relacional, SQL y diseño de bases de datos.'],
            ['Introduction to Algorithms', 'Cormen, Leiserson, Rivest y Stein', 'Ingeniería de sistemas', 'rojo', 2, 'Referencia de algoritmos y estructuras de datos.'],
            ['Clean Code', 'Robert C. Martin', 'Ingeniería de sistemas', 'rojo', 2, 'Prácticas para escribir código legible y mantenible.'],

            ['Diccionario de la lengua española', 'Real Academia Española', 'Consulta', 'verde', 2, 'Obra de consulta con el léxico del español.'],
            ['Diccionario panhispánico de dudas', 'Real Academia Española', 'Consulta', 'verde', 2, 'Resuelve dudas de ortografía, gramática y uso del español.'],
            ['Manual de publicaciones de la APA', 'American Psychological Association', 'Consulta', 'verde', 2, 'Normas para citar y presentar trabajos académicos.'],
            ['Constitución Política de Colombia', 'Asamblea Nacional Constituyente', 'Derecho', 'verde', 2, 'Texto de la Constitución de 1991.'],

            ['Enciclopedia Británica', 'Varios autores', 'Consulta', 'amarillo', 1, 'Enciclopedia general de consulta.'],
            ['Obras completas', 'Simón Bolívar', 'Historia', 'amarillo', 1, 'Escritos, discursos y cartas del Libertador.'],
        ];

        foreach ($libros as [$titulo, $autor, $categoria, $etiqueta, $cantidad, $descripcion]) {
            $libro = Libro::firstOrCreate(
                ['titulo' => $titulo, 'autor' => $autor],
                ['categoria' => $categoria, 'etiqueta' => $etiqueta, 'descripcion' => $descripcion]
            );

            // Solo crea ejemplares si el libro todavía no tiene
            if ($libro->ejemplares()->count() === 0) {
                $libro->agregarEjemplares($cantidad);
            }
        }
    }
}