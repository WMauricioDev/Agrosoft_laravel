<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IoT\TipoSensor;

class TipoSensorSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            [
                'nombre' => 'temperatura',
                'unidad_medida' => '°C',
                'medida_minima' => -40,
                'medida_maxima' => 125,
                'descripcion' => 'Mide la temperatura ambiente',
            ],
            [
                'nombre' => 'humedad_ambiente',
                'unidad_medida' => '%',
                'medida_minima' => 0,
                'medida_maxima' => 100,
                'descripcion' => 'Mide la humedad del aire',
            ],
            [
                'nombre' => 'luminosidad',
                'unidad_medida' => 'lux',
                'medida_minima' => 0,
                'medida_maxima' => 100000,
                'descripcion' => 'Mide la intensidad de la luz',
            ],
            [
                'nombre' => 'lluvia',
                'unidad_medida' => 'mm/h',
                'medida_minima' => 0,
                'medida_maxima' => 500,
                'descripcion' => 'Mide la precipitación',
            ],
            [
                'nombre' => 'velocidad_viento',
                'unidad_medida' => 'm/s',
                'medida_minima' => 0,
                'medida_maxima' => 100,
                'descripcion' => 'Mide la velocidad del viento',
            ],
            [
                'nombre' => 'direccion_viento',
                'unidad_medida' => '°',
                'medida_minima' => 0,
                'medida_maxima' => 360,
                'descripcion' => 'Mide la dirección del viento',
            ],
            [
                'nombre' => 'humedad_suelo',
                'unidad_medida' => '%',
                'medida_minima' => 0,
                'medida_maxima' => 100,
                'descripcion' => 'Mide la humedad del suelo',
            ],
            [
                'nombre' => 'ph_suelo',
                'unidad_medida' => '',
                'medida_minima' => 0,
                'medida_maxima' => 14,
                'descripcion' => 'Mide el pH del suelo',
            ],
            [
                'nombre' => 'calidad_aire',
                'unidad_medida' => 'PPM',
                'medida_minima' => 10,
                'medida_maxima' => 1000,
                'descripcion' => 'Mide la calidad del aire (MQ-135)',
            ],
        ];

        foreach ($tipos as $tipo) {
            TipoSensor::updateOrCreate(
                ['nombre' => $tipo['nombre']],
                [
                    'unidad_medida' => $tipo['unidad_medida'],
                    'medida_minima' => $tipo['medida_minima'],
                    'medida_maxima' => $tipo['medida_maxima'],
                    'descripcion' => $tipo['descripcion'],
                ]
            );
        }
    }
}