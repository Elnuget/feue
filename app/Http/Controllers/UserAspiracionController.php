<?php

namespace App\Http\Controllers;

use App\Models\UserAspiracion;
use App\Models\Universidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAspiracionController extends Controller
{
    public function index()
    {
        $aspiraciones = UserAspiracion::with('user', 'universidad')->get();
        return view('user_aspiraciones.index', compact('aspiraciones'));
    }

    public function create()
    {
        $universidades = Universidad::all();
        return view('user_aspiraciones.create', compact('universidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'universidad_id' => 'required|exists:universidades,id',
        ]);

        UserAspiracion::create([
            'user_id' => Auth::id(),
            'universidad_id' => $request->universidad_id,
        ]);

        return redirect()->route('user_aspiraciones.index');
    }

    public function show($id)
    {
        $aspiracion = UserAspiracion::with('user', 'universidad')->where('universidad_id', $id)->firstOrFail();
        return view('user_aspiraciones.show', compact('aspiracion'));
    }

    public function edit($id)
    {
        $aspiracion = UserAspiracion::where('universidad_id', $id)->firstOrFail();
        $universidades = Universidad::all();
        return view('user_aspiraciones.edit', compact('aspiracion', 'universidades'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'universidad_id' => 'required|exists:universidades,id',
        ]);

        $aspiracion = UserAspiracion::where('universidad_id', $id)->firstOrFail();
        $aspiracion->update([
            'universidad_id' => $request->universidad_id,
        ]);

        return redirect()->route('user_aspiraciones.index');
    }

    public function destroy($id)
    {
        $aspiracion = UserAspiracion::where('user_id', Auth::id())->where('universidad_id', $id)->firstOrFail();
        $aspiracion->delete();

        return redirect()->route('user_aspiraciones.index');
    }
}