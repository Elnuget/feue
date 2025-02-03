<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

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
        $profile = \App\Models\UserProfile::with('estadoAcademico')
            ->where('user_id', auth()->id())
            ->first();
        
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
        ]);

        $data = $request->except(['photo', 'acta_grado']);

        $profile = \App\Models\UserProfile::where('user_id', $request->user_id)->first();

        if ($request->hasFile('photo')) {
            if ($profile && $profile->photo) {
                \Storage::disk('public')->delete($profile->photo);
            }
            $data['photo'] = $request->file('photo')->store('profiles/photos', 'public');
        }

        if ($profile && $profile->carnet) {
            $data['carnet'] = $profile->carnet;
        }

        $profile = \App\Models\UserProfile::updateOrCreate(
            ['user_id' => $request->user_id],
            $data
        );

        if ($request->filled('estado_academico_id')) {
            $userAcademico = \App\Models\UserAcademico::updateOrCreate(
                ['user_id' => $request->user_id],
                [
                    'estado_academico_id' => $request->estado_academico_id,
                ]
            );
        }

        return redirect()->route('matriculas.create')->with('success', 'Perfil completado exitosamente');
    }

    /**
     * Verificar si la cédula ya está registrada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCedula(Request $request)
    {
        $request->validate([
            'cedula' => 'required|string|max:20',
        ]);

        $exists = UserProfile::where('cedula', $request->cedula)->exists();

        return response()->json(['exists' => $exists]);
    }
}