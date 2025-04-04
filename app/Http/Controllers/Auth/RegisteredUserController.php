<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $tiposCursos = \App\Models\TipoCurso::all();
        $cursos = \App\Models\Curso::all();
        $cursosPorTipo = [];
        foreach ($cursos as $curso) {
            $cursosPorTipo[$curso->tipo_curso_id][] = $curso;
        }
        $metodosPago = \App\Models\MetodoPago::all();

        return view('auth.register', compact('tiposCursos','cursosPorTipo','metodosPago'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function($attribute, $value, $fail) {
                    $words = preg_split('/\s+/', trim($value));
                    if (count($words) < 3) {
                        $fail('Debe ingresar al menos 3 nombres.');
                    }
                }
            ],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable','string','max:20'],
            'cedula' => ['required', 'string', 'max:20', 'unique:user_profiles,cedula'],
        ]);
        
        $name = mb_strtoupper($request->name, 'UTF-8');

        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        \App\Models\UserProfile::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'cedula' => $request->cedula,
        ]);

        $role = Role::find(2); // Default role ID 2
        $user->assignRole($role);

        // Crear la matrícula
        $matricula = \App\Models\Matricula::create([
            'usuario_id' => $user->id,
            'tipo_curso_id' => $request->tipo_curso_id,
            'curso_id' => $request->curso_id,
            'fecha_matricula' => $request->fecha_matricula,
            'monto_total' => $request->monto_total,
            'valor_pendiente' => $request->monto_total, // Establecer el valor pendiente igual al monto total
            'estado_matricula' => 'Aprobada', // Cambiar estado a Aprobada
            'tipo_pago' => $request->tipo_pago, // Agregar el tipo de pago
        ]);

        // Crear el pago
        $pagoData = [
            'matricula_id' => $matricula->id,
            'metodo_pago_id' => $request->metodo_pago_id,
            'monto' => $request->monto,
            'fecha_pago' => $request->fecha_pago,
            'estado' => 'Pendiente', // Asegurar que el pago también esté aprobado
        ];
        if ($request->hasFile('comprobante_pago')) {
            $pagoData['comprobante_pago'] = $request->file('comprobante_pago')->store('comprobantes', 'public');
        }
        \App\Models\Pago::create($pagoData);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
