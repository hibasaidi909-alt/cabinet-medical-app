<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
            'patient_id' => User::where('role', 'patient')->inRandomOrder()->first()?->id ?? User::factory()->create(['role' => 'patient'])->id,
            
            'doctor_id' => User::where('role', 'doctor')->inRandomOrder()->first()?->id ?? User::factory()->create(['role' => 'doctor'])->id,
            
            'service_id' => Service::inRandomOrder()->first()?->id ?? Service::factory()->create()->id,
            
            'appointment_date' => fake()->dateTimeBetween('now', '+1 month'), // موعد في هاد الشهر الجديد
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled']),
            'notes' => fake()->paragraph(),
        ];
    }
}
