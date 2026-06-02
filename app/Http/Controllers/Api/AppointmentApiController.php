<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentApiController extends Controller
{
    // 1. Lister les rendez-vous en JSON (Barème: 3 pts)
    public function index()
    {
        // Eloquent eager loading bach l-JSON ykon fih smya dial user wl-service
        $appointments = Appointment::with(['user', 'service'])->latest()->get();
        return response()->json($appointments);
    }

    // 2. Création via requête externe (Barème: 3 pts)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::create($validated);

        return response()->json([
            'message' => 'Rendez-vous créé avec succès via API',
            'data' => $appointment
        ], 201);
    }

    // 3. Moteur de recherche temps réel via Axios (Barème: 2 pts)
    public function search(Request $request)
    {
        $query = $request->get('q');

        $appointments = Appointment::with(['user', 'service'])
            ->whereHas('user', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('service', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->get();

        // N-génériw format dial l-date mzyan 
        $formatted = $appointments->map(function($app) {
            return [
                'id' => $app->id,
                'user' => ['name' => $app->user->name],
                'service' => ['name' => $app->service->name],
                'appointment_date' => \Carbon\Carbon::parse($app->appointment_date)->format('d M Y - H:i'),
                'status' => $app->status
            ];
    });

        return response()->json($formatted);
    }
}