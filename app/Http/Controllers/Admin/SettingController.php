<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function updateTerminos(Request $request)
    {
        $request->validate([
            'texto' => 'required|string|max:5000',
        ]);

        Setting::updateOrCreate(
            ['Clave' => 'terminos_y_condiciones'],
            ['Valor' => strip_tags($request->texto)]
        );

        return redirect(route('admin.dashboard') . '#configuracion')
            ->with('success', 'Términos y condiciones actualizados correctamente.');
    }
}
