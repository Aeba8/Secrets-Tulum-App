<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CenaEspecial;
use App\Models\Experiencia;
use App\Models\Balinesa;
use App\Models\Categoria; 
use Illuminate\Support\Str;

class HotelCatalogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Asegurar o crear las categorías base
        $catCena = Categoria::firstOrCreate(
            ['Nombre' => 'Cenas Especiales'], 
            [
                'Nombre' => 'Cenas Especiales',
                'Slug'   => 'cenas-especiales'
            ]
        );

        $catExperiencia = Categoria::firstOrCreate(
            ['Nombre' => 'Experiencias'], 
            [
                'Nombre' => 'Experiencias',
                'Slug'   => 'experiencias'
            ]
        );

        // 2. INYECTAR CENA ESPECIAL
        CenaEspecial::create([
            'Nombre' => 'Cenas Románticas Grotto',
            'slug' => Str::slug('Cenas Románticas Grotto'),
            'Precio' => 2500.00, // <-- Corregido con P mayúscula
            'Restaurant' => 'The Grotto', 
            'Numero_Personas' => 2,
            'Is_Active' => true,
            'Id_Categoria' => $catCena->Id, 
            'Ficha_Tecnica' => [
                'botella_incluida' => '1 botella de Moët & Chandon Brut 750ml',
                'entrada' => 'Tartar de salmón fresco con aderezo de cítricos y brotes orgánicos.',
                'crema' => 'Crema aterciopelada de langosta con un toque de coñac.',
                'plato_fuerte' => 'Filete Mignon en reducción de oporto acompañado de puré de camote.',
                'postre' => 'Esfera de chocolate amargo con sorpresa de frutos rojos.'
            ]
        ]);

        // 3. INYECTAR EXPERIENCIA TIPO SPA
        Experiencia::create([
            'Nombre' => 'Spa con Burbujas',
            'slug' => Str::slug('Spa con Burbujas'),
            'Precio' => 1800.00,
            'Tipo' => 'SPA', 
            'Lugar' => 'Spa Principal',
            'Duracion' => '2 masajes de 25 mins',
            'Horario' => '24 h',
            'numero_personas' => 2,
            'is_active' => true,
            'id_categoria' => $catExperiencia->Id, 
            'ficha_tecnica' => [
                'botella_incluida' => '1 botella de Moët & Chandon Brut 750ml',
                'descripcion' => 'Disfrute de un oasis de relajación absoluta con nuestra sesión exclusiva de hidroterapia seguida de masajes focalizados diseñados para liberar la tensión corporal.',
                'producto' => 'Plato artesanal de fresas selectas cubiertas con chocolate semi-amargo.',
                'servicios_extra' => 'Acceso extendido al área de saunas, reflexología podal de 10 minutos y aromaterapia personalizada.'
            ]
        ]);

        // 4. INYECTAR CAMA BALINESA
        Balinesa::create([
            'Nombre' => 'Balinesa con Burbujas',
            'slug' => Str::slug('Balinesa con Burbujas'),
            'Precio' => 3200.00,
            'Capacidad_Maxima' => 2,
            'Is_Active' => true,
            'id_categoria' => $catExperiencia->Id, 
            'Ficha_Tecnica' => [
                'comida' => 'Caja China', 
                'botella_incluida' => '1 botella de Moët & Chandon Brut 750ml',
                'descripcion' => 'Viva una experiencia de lujo frente al mar en nuestras exclusivas camas balinesas, acondicionadas con un servicio preferencial y amenidades VIP.',
                'producto' => 'Surtido de snacks premium, aguas artesanales y brochetas de frutas de temporada.'
            ]
        ]);
    }
}