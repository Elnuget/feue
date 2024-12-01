<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::all();
        return view('documentos.index', compact('documentos'));
    }

    public function create()
    {
        return view('documentos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_documento' => 'required|in:Foto,Acta de Grado,Otro',
            'ruta' => 'required|string|max:255',
        ]);

        Auth::user()->documentos()->create($request->all());

        return redirect()->route('documentos.index');
    }

    public function destroy(Documento $documento)
    {
        $this->authorize('delete', $documento);
        $documento->delete();

        return redirect()->route('documentos.index');
    }
}