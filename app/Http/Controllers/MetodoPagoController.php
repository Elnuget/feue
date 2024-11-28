<?php

namespace App\Http\Controllers;

use App\Models\MetodoPago;
use Illuminate\Http\Request;

class MetodoPagoController extends Controller
{
    public function index()
    {
        $metodosPago = MetodoPago::all();
        return view('metodos_pago.index', compact('metodosPago'));
    }

    public function create()
    {
        return view('metodos_pago.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
        ]);

        MetodoPago::create($request->all());

        return redirect()->route('metodos_pago.index')->with('success', 'Método de pago creado exitosamente.');
    }

    public function edit(MetodoPago $metodoPago)
    {
        return view('metodos_pago.edit', compact('metodoPago'));
    }

    public function update(Request $request, MetodoPago $metodoPago)
    {
        $request->validate([
            'nombre' => 'required|max:100',
        ]);

        $metodoPago->update($request->all());

        return redirect()->route('metodos_pago.index')->with('success', 'Método de pago actualizado exitosamente.');
    }

    public function destroy(MetodoPago $metodoPago)
    {
        // Check for associated payments before deleting
        if ($metodoPago->pagos()->exists()) {
            return redirect()->route('metodos_pago.index')->with('error', 'No se puede eliminar el método de pago porque tiene pagos asociados.');
        }

        $metodoPago->delete();

        return redirect()->route('metodos_pago.index')->with('success', 'Método de pago eliminado exitosamente.');
    }
}