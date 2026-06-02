<?php

namespace Database\Factories;

use App\Models\Service; // <-- تأكد من هاد السطر
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    
    protected $model = Service::class; 

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Consultation Générale', 'Dermatologie', 'Pédiatrie', 'Cardiologie', 'Dentaire']),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 200, 600),
        ];
    }
}