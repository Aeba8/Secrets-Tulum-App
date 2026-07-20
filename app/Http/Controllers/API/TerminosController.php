<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class TerminosController extends Controller
{
    public function show()
    {
        $texto = Setting::valor('terminos_y_condiciones', '');

        return response()->json([
            'success' => true,
            'texto' => $texto,
        ]);
    }
}
