<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PagoAprobado;

class PagoController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole(1)) {
            $pagos = Pago::with(['matricula.usuario', 'matricula.curso', 'metodoPago'])->get();
        } else {
            $pagos = Pago::with(['matricula.usuario', 'matricula.curso', 'metodoPago'])
                         ->whereHas('matricula', function($query) {
                             $query->where('usuario_id', auth()->id());
                         })
                         ->get();
        }
        return view('pagos.index', compact('pagos'));
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