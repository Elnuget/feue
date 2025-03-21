<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use Illuminate\Http\Request;

class CertificadoController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasRole(1)) {
                abort(403, 'No tienes permiso para acceder a esta sección.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $certificados = Certificado::with('usuario')->paginate(10);
        return view('certificados.index', compact('certificados'));
    }

    public function create()
    {
        return view('certificados.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'nombre_completo' => 'required|string|max:255',
            'horas_curso' => 'required|integer',
            'sede_curso' => 'required|string|max:100',
            'fecha_emision' => 'required|date',
            'anio_emision' => 'required|string|size:2',
            'numero_certificado' => 'required|string|max:50|unique:certificados',
            'estado' => 'boolean',
            'observaciones' => 'nullable|string',
        ]);

        Certificado::create($validated);

        return redirect()->route('certificados.index')
            ->with('success', 'Certificado creado exitosamente.');
    }

    public function show(Certificado $certificado)
    {
        return view('certificados.show', compact('certificado'));
    }

    public function edit(Certificado $certificado)
    {
        return view('certificados.edit', compact('certificado'));
    }

    public function update(Request $request, Certificado $certificado)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'nombre_completo' => 'required|string|max:255',
            'horas_curso' => 'required|integer',
            'sede_curso' => 'required|string|max:100',
            'fecha_emision' => 'required|date',
            'anio_emision' => 'required|string|size:2',
            'numero_certificado' => 'required|string|max:50|unique:certificados,numero_certificado,' . $certificado->id,
            'estado' => 'boolean',
            'observaciones' => 'nullable|string',
        ]);

        $certificado->update($validated);

        return redirect()->route('certificados.index')
            ->with('success', 'Certificado actualizado exitosamente.');
    }

    public function destroy(Certificado $certificado)
    {
        $certificado->delete();

        return redirect()->route('certificados.index')
            ->with('success', 'Certificado eliminado exitosamente.');
    }
} 