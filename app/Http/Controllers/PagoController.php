<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Matricula;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole(1)) {
            $pagos = Pago::with(['matricula.usuario', 'matricula.curso', 'metodoPago'])->get();
        } else {
            $pagos = Pago::with(['matricula.usuario', 'matricula.curso', 'metodoPago'])
                         ->where('user_id', auth()->id())
                         ->get();
        }
        return view('pagos.index', compact('pagos'));
    }

    public function create()
    {
        $matriculas = Matricula::with('curso')->get();
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
        $data['user_id'] = auth()->id(); // Associate the payment with the authenticated user

        if ($request->hasFile('comprobante_pago')) {
            $data['comprobante_pago'] = $request->file('comprobante_pago')->store('comprobantes', 'public');
        }

        $pago = Pago::create($data);

        // Debug statement to log the created payment
        \Log::info('Pago created: ', $pago->toArray());

        return redirect()->route('pagos.index')->with('success', 'Pago created successfully.');
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

        return redirect()->route('pagos.index')->with('success', 'Pago updated successfully.');
    }

    public function destroy(Pago $pago)
    {
        $pago->delete();

        return redirect()->route('pagos.index')->with('success', 'Pago deleted successfully.');
    }

    public function aprobar($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->estado = 'Aprobado';
        $pago->save();

        $matricula = $pago->matricula;
        $matricula->valor_pendiente -= $pago->monto;
        $matricula->save();

        return redirect()->route('pagos.index')->with('success', 'Pago aprobado y valor pendiente actualizado.');
    }
}