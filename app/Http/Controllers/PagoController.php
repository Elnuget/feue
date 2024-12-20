<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Matricula;
use App\Models\TipoCurso;  // Add this import
use App\Models\Curso;      // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PagoAprobado;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pago::query()->with(['matricula.usuario', 'matricula.curso', 'metodoPago']);
        
        if (!auth()->user()->hasRole(1)) {
            $query->whereHas('matricula', function($query) {
                $query->where('usuario_id', auth()->id());
            });
        }

        $tiposCursos = TipoCurso::all();
        $tipoCursoId = $request->query('tipo_curso');
        $cursoId = $request->query('curso_id');

        // Get courses based on tipo_curso
        if ($tipoCursoId) {
            $cursos = Curso::where('tipo_curso_id', $tipoCursoId)->get();
            if ($cursoId) {
                $query->whereHas('matricula', function($q) use ($cursoId) {
                    $q->where('curso_id', $cursoId);
                });
            } else {
                $query->whereHas('matricula', function($q) use ($cursos) {
                    $q->whereIn('curso_id', $cursos->pluck('id'));
                });
            }
        } else {
            $cursos = collect();
        }

        // Add join with users table and sort by name
        $pagos = $query->join('matriculas', 'pagos.matricula_id', '=', 'matriculas.id')
                       ->join('users', 'matriculas.usuario_id', '=', 'users.id')
                       ->select('pagos.*')
                       ->orderBy('users.name')
                       ->get();

        return view('pagos.index', compact('pagos', 'tiposCursos', 'cursos', 'tipoCursoId', 'cursoId'));
    }

    public function create()
    {
        $matriculas = Matricula::with('curso')
                      ->where('usuario_id', auth()->id())
                      ->get();
        $metodosPago = \App\Models\MetodoPago::all();
        return view('pagos.create', compact('matriculas', 'metodosPago'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricula_id' => 'required|exists:matriculas,id',
            'metodo_pago_id' => 'required|exists:metodos_pago,id',
            'monto' => 'required|numeric',
            'fecha_pago' => 'required|date',
            'comprobante_pago' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:2048',
        ]);

        $matricula = Matricula::where('id', $request->matricula_id)
                              ->where('usuario_id', auth()->id())
                              ->firstOrFail();

        $data = $request->all();

        if ($request->hasFile('comprobante_pago')) {
            $data['comprobante_pago'] = $request->file('comprobante_pago')->store('comprobantes', 'public');
        }

        $pago = Pago::create($data);

        return redirect()->route('pagos.index')->with('success', 'Pago creado exitosamente.');
    }

    public function show(Pago $pago)
    {
        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago)
    {
        return view('pagos.edit', compact('pago'));
    }

    public function update(Request $request, Pago $pago)
    {
        $request->validate([
            'matricula_id' => 'required|exists:matriculas,id',
            'metodo_pago_id' => 'required|exists:metodos_pago,id',
            'monto' => 'required|numeric',
            'fecha_pago' => 'required|date',
        ]);

        $pago->update($request->all());

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado exitosamente.');
    }

    public function destroy(Pago $pago)
    {
        $pago->delete();

        return redirect()->route('pagos.index')->with('success', 'Pago eliminado exitosamente.');
    }

    public function aprobar($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->estado = 'Aprobado';
        $pago->save();

        $matricula = $pago->matricula;
        $matricula->valor_pendiente -= $pago->monto;
        $matricula->save();

        // Send email to the user
        Mail::to($pago->matricula->usuario->email)->send(new PagoAprobado($pago));

        return redirect()->route('pagos.index')->with('success', 'Pago aprobado y valor pendiente actualizado.');
    }

    public function rechazar($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->estado = 'Rechazado';
        $pago->save();

        return redirect()->route('pagos.index')->with('success', 'Pago rechazado.');
    }
}