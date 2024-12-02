<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function create()
    {
        return view('user_profiles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            // ...other validation rules...
        ]);

        UserProfile::create($request->all());

        return redirect()->route('user_profiles.show', $request->user_id);
    }

    public function show($id)
    {
        $profile = UserProfile::where('user_id', $id)->firstOrFail();
        return view('user_profiles.show', compact('profile'));
    }

    public function edit($id)
    {
        $profile = UserProfile::where('user_id', $id)->firstOrFail();
        return view('user_profiles.edit', compact('profile'));
    }

    public function update(Request $request, $id)
    {
        $profile = UserProfile::where('user_id', $id)->firstOrFail();

        $request->validate([
            // ...validation rules...
        ]);

        $profile->update($request->all());

        return redirect()->route('user_profiles.show', $id);
    }

    public function destroy($id)
    {
        $profile = UserProfile::where('user_id', $id)->firstOrFail();
        $profile->delete();

        return redirect()->route('users.index');
    }

    public function index()
    {
        $profiles = UserProfile::with('user')->get();
        return view('user_profiles.index', compact('profiles'));
    }

    public function completeProfile()
    {
        $universidades = \App\Models\Universidad::all();
        $estadosAcademicos = \App\Models\EstadoAcademico::all();
        $profile = \App\Models\UserProfile::where('user_id', auth()->id())->first();
        return view('user_profiles.complete', compact('universidades', 'estadosAcademicos', 'profile'));
    }

    public function storeCompleteProfile(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'estado_academico_id' => 'required|exists:estados_academicos,id',
            'acta_grado' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:Masculino,Femenino,Otro',
            'photo' => 'nullable|image|mimes:jpeg,png|max:2048',
            'cedula' => 'nullable|string|max:20',
            'direccion_calle' => 'nullable|string|max:255',
            'direccion_ciudad' => 'nullable|string|max:100',
            'direccion_provincia' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
            'numero_referencia' => 'nullable|string|max:50',
        ]);

        $data = $request->except(['photo', 'acta_grado']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('imguser', 'public');
        }

        if ($request->hasFile('acta_grado')) {
            $userAcademico = \App\Models\UserAcademico::where('user_id', $request->user_id)->first();
            if (isset($userAcademico->acta_grado)) {
                \Storage::disk('public')->delete($userAcademico->acta_grado);
            }
            $data['acta_grado'] = $request->file('acta_grado')->store('actas', 'public');
        }

        \App\Models\UserProfile::updateOrCreate(
            ['user_id' => $request->user_id],
            $data
        );
        \App\Models\UserAcademico::updateOrCreate(
            ['user_id' => $request->user_id],
            $data
        );

        return redirect()->route('dashboard')->with('success', 'Perfil completado');
    }
}