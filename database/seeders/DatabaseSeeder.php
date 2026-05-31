<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء المستخدمين الأساسيين لتسجيل الدخول
        User::factory()->create([
            'name' => 'Dr. Ahmed Alami',
            'email' => 'doctor@example.com',
            'password' => Hash::make('password123'),
            'role' => 'doctor',
        ]);

        User::factory()->create([
            'name' => 'Yassine Nasir',
            'email' => 'patient@example.com',
            'password' => Hash::make('password123'),
            'role' => 'patient',
        ]);

        // 2. إنشاء 8 مستخدمين عشوائيين آخرين (المجموع 10 مستخدمين)
        User::factory(8)->create();

        // 3. إنشاء الخدمات مباشرة بـ create() لتفادي خطأ الـ Factory
        $services = [
            ['name' => 'Consultation Générale', 'description' => 'Consultation médicale générale.', 'price' => 200.00],
            ['name' => 'Dermatologie', 'description' => 'Consultation pour les problèmes de peau.', 'price' => 300.00],
            ['name' => 'Pédiatrie', 'description' => 'Suivi et consultation pour enfants.', 'price' => 250.00],
            ['name' => 'Cardiologie', 'description' => 'Examen et suivi cardiaque.', 'price' => 400.00],
            ['name' => 'Dentaire', 'description' => 'Soins et consultation dentaire.', 'price' => 150.00],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // 4. إنشاء 20 موعداً عشوائياً
        // بما أن الـ AppointmentFactory كيعيط على Service::factory()، غانبدلو الطريقة هنا باش نكريوهم حبة بحبة ونربطوهم بالخدمات اللي ديجا تكراو
        for ($i = 0; $i < 20; $i++) {
            Appointment::create([
                'patient_id' => User::where('role', 'patient')->inRandomOrder()->first()->id,
                'doctor_id' => User::where('role', 'doctor')->inRandomOrder()->first()->id,
                'service_id' => Service::inRandomOrder()->first()->id,
                'appointment_date' => now()->addDays(rand(1, 30))->setTime(rand(9, 17), 0, 0), // موعد عشوائي في الـ 30 يوم القادمة بين 9 صباحا و 5 مساءا
                'status' => collect(['pending', 'confirmed', 'cancelled'])->random(),
                'notes' => 'Note de test pour le rendez-vous numéro ' . ($i + 1),
            ]);
        }
    }
}