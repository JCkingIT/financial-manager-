<?php

namespace Database\Seeders;

use App\Enums\TypeCategory;
use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Category::create([
        //     'name' => 'Salario',
        //     'type' => TypeCategory::ICOME,
        //     'user_id' => 1,
        //     'description' => 'Ingresos provenientes de su trabajo.',
        //     'icon' => 'briefcase'
        // ]);

        // Category::create([
        //     'name' => 'Bonos',
        //     'type' => TypeCategory::ICOME,
        //     'user_id' => 1,
        //     'description' => 'Ingresos adicionales o incentivos laborales.',
        //     'icon' => 'gift'
        // ]);
        // Category::create([
        //     'name' => 'Intereses',
        //     'type' => TypeCategory::ICOME,
        //     'user_id' => 1,
        //     'description' => 'Intereses generados por ahorros o inversiones.',
        //     'icon' => 'chart-bar'
        // ]);
        // Category::create([
        //     'name' => 'Venta de Bienes',
        //     'type' => TypeCategory::ICOME,
        //     'user_id' => 1,
        //     'description' => 'Ingresos por ventas ocasionales de bienes.',
        //     'icon' => 'shopping-cart'
        // ]);
        // Category::create([
        //     'name' => '	Regalos',
        //     'type' => TypeCategory::ICOME,
        //     'user_id' => 1,
        //     'description' => 'Dinero recibido como regalos.',
        //     'icon' => 'sparkles'
        // ]);
        // Category::create([
        //     'name' => 'Alquiler',
        //     'type' => TypeCategory::EXPENSE,
        //     'user_id' => 1,
        //     'description' => 'Pago mensual por vivienda o alquiler.',
        //     'icon' => 'home'
        // ]);
        // Category::create([
        //     'name' => 'Comida',
        //     'type' => TypeCategory::EXPENSE,
        //     'user_id' => 1,
        //     'description' => 'Gasto en alimentos y comidas.',
        //     'icon' => 'cake'
        // ]);
        // Category::create([
        //     'name' => 'Transporte',
        //     'type' => TypeCategory::EXPENSE,
        //     'user_id' => 1,
        //     'description' => 'Costos relacionados con transporte (gasolina, pasajes).',
        //     'icon' => 'truck'
        // ]);
        // Category::create([
        //     'name' => 'Entretenimiento',
        //     'type' => TypeCategory::EXPENSE,
        //     'user_id' => 1,
        //     'description' => 'Gasto en ocio, salidas y actividades recreativas.',
        //     'icon' => 'controller'
        // ]);
        // Category::create([
        //     'name' => 'Servicios Públicos',
        //     'type' => TypeCategory::EXPENSE,
        //     'user_id' => 1,
        //     'description' => 'Pagos de luz, agua, gas e internet.',
        //     'icon' => 'lightning-bolt'
        // ]);
    }
}
