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

    public function create(Request $request)
    {
        $search = $request->input('search'); // Add this line

        $matriculas = auth()->user()->hasRole(1)
            ? Matricula::with(['curso', 'usuario'])
                ->select('matriculas.id', 'curso_id', 'usuario_id', 'valor_pendiente')
                ->when($search, function ($query, $search) {
                    return $query->whereHas('usuario', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                })
                ->orderBy('valor_pendiente', 'desc')
                ->get()
            : Matricula::with('curso', 'usuario')
                ->select('matriculas.id', 'curso_id', 'usuario_id', 'valor_pendiente')
                ->where('usuario_id', auth()->id())
                ->when($search, function ($query, $search) {
                    return $query->whereHas('usuario', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                })
                ->orderBy('valor_pendiente', 'desc')
                ->get();

        $selectedMatriculaId = $request->input('matricula_id');
        $selectedMatricula = $selectedMatriculaId ? $matriculas->find($selectedMatriculaId) : null;
        
        $metodosPago = \App\Models\MetodoPago::all();
        return view('pagos.create', compact('matriculas', 'metodosPago', 'selectedMatricula', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricula_id' => 'required|exists:matriculas,id',
            'metodo_pago_id' => 'required|exists:metodos_pago,id',
            'monto' => 'required|numeric',
            'fecha_pago' => 'required|date',
            'comprobante_pago' => 'required|file|mimes:png,jpg,jpeg,pdf|max:2048',
        ]);

        // Modificar la consulta para permitir acceso a administradores
        $matricula = Matricula::where('id', $request->matricula_id);
        if (!auth()->user()->hasRole(1)) {
            $matricula->where('usuario_id', auth()->id());
        }
        $matricula = $matricula->firstOrFail();

        // Validar que el monto no exceda el valor pendiente
        if ($request->monto > $matricula->valor_pendiente) {
            return back()->withErrors(['monto' => 'El monto no puede ser mayor al valor pendiente.']);
        }

        $data = $request->all();
        
        if ($request->hasFile('comprobante_pago')) {
            $data['comprobante_pago'] = $request->file('comprobante_pago')->store('comprobantes', 'public');
        }

        // Si es usuario normal, siempre será pendiente
        if (!auth()->user()->hasRole(1)) {
            $data['estado'] = 'Pendiente';
        }

        $pago = Pago::create($data);

        // Actualizar valor pendiente solo si el pago es aprobado inmediatamente por un admin
        if (auth()->user()->hasRole(1) && $data['estado'] === 'Aprobado') {
            $matricula->valor_pendiente -= $request->monto;
            if ($matricula->valor_pendiente <= 0) {
                $matricula->valor_pendiente = 0;
                $matricula->estado_matricula = 'Completada';
            }
            $matricula->save();
        }

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
        if ($matricula->valor_pendiente <= 0) {
            $matricula->valor_pendiente = 0;
            $matricula->estado_matricula = 'Completada';
        }
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