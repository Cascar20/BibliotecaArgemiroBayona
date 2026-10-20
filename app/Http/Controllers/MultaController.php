<?php

namespace App\Http\Controllers;

use App\Models\Multa;

class MultaController extends Controller
{
    public function saldar(Multa $multa)
    {
        $multa->update(['estado' => 'saldada']);

        return back()->with('success', "Multa de {$multa->user->name} saldada.");
    }
}