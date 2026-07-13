<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    protected function reglasValidacion($id = null)
    {
        $emailUnique  = $id
            ? 'unique:Usuarios,Email,' . $id . ',Id'
            : 'unique:Usuarios,Email';

        $baseRules = $id
            ? 'nullable|digits:6'
            : 'required|digits:6';

        $numeroRules = [$baseRules, function ($attr, $value, $fail) use ($id) {
            $existing = Usuario::where('Id', '!=', $id ?? 0)->get();
            foreach ($existing as $u) {
                if (Hash::check($value, $u->Numero_de_colaborador)) {
                    $fail('Este número de colaborador ya existe.');
                    return;
                }
            }
        }];

        return [
            'nombre'             => 'required|string|max:100|regex:/^[a-zA-ZÀ-ÿ\s\.]+$/',
            'email'              => 'required|email|max:150|' . $emailUnique,
            'numero_colaborador' => $numeroRules,
            'activo'             => 'nullable|in:0,1,on,off',
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'nombre.required'             => 'El nombre es obligatorio.',
            'nombre.max'                  => 'El nombre no debe exceder 100 caracteres.',
            'nombre.regex'                => 'El nombre solo acepta letras, espacios y puntos.',
            'email.required'              => 'El correo electrónico es obligatorio.',
            'email.email'                 => 'Ingresa un correo electrónico válido.',
            'email.max'                   => 'El correo no debe exceder 150 caracteres.',
            'email.unique'                => 'Este correo electrónico ya está registrado.',
            'numero_colaborador.required' => 'El número de colaborador es obligatorio.',
            'numero_colaborador.digits'   => 'Debe tener exactamente 6 dígitos.',
            'numero_colaborador.unique'   => 'Este número de colaborador ya existe.',
        ];
    }

    protected function validarYFallar(Request $request, $id = null): array
    {
        $validator = Validator::make(
            $request->all(),
            $this->reglasValidacion($id),
            $this->mensajesValidacion()
        );

        if ($validator->fails()) {
            $redirect = redirect(route('admin.dashboard') . '#usuarios')
                ->withErrors($validator)
                ->withInput();
            if ($id) {
                $redirect->with('edit_id', $id);
            }
            throw new \Illuminate\Validation\ValidationException($validator, $redirect);
        }

        return $validator->validated();
    }

    public function store(Request $request)
    {
        $validated = $this->validarYFallar($request);

        Usuario::create([
            'Nombre'                => strip_tags($validated['nombre']),
            'Email'                 => strip_tags($validated['email']),
            'Numero_de_colaborador' => Hash::make(strip_tags($validated['numero_colaborador'])),
            'Rol'                   => 'Operativo',
            'Estado'                => ($request->boolean('activo')) ? 'Activo' : 'Inactivo',
        ]);

        return redirect(route('admin.dashboard') . '#usuarios')
            ->with('success', 'Usuario operativo creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $validated = $this->validarYFallar($request, $id);

        $data = [
            'Nombre'                => strip_tags($validated['nombre']),
            'Email'                 => strip_tags($validated['email']),
            'Estado'                => ($request->boolean('activo')) ? 'Activo' : 'Inactivo',
        ];
        if (!empty($validated['numero_colaborador'])) {
            $data['Numero_de_colaborador'] = Hash::make(strip_tags($validated['numero_colaborador']));
        }
        $usuario->update($data);

        return redirect(route('admin.dashboard') . '#usuarios')
            ->with('success', 'Usuario operativo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['Estado' => 'Inactivo']);
        return redirect(route('admin.dashboard') . '#usuarios')
            ->with('success', 'Usuario desactivado correctamente.');
    }

    public function activate($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['Estado' => 'Activo']);
        return redirect(route('admin.dashboard') . '#usuarios')
            ->with('success', 'Usuario activado correctamente.');
    }
}
