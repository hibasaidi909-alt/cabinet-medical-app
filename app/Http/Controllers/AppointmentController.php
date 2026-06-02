<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\AppointmentConfirmed;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    
    public function index()
    {
        $appointments = Appointment::with(['user', 'service'])->latest()->get();
        $users = User::all(); 
        $services = Service::all(); 

        return view('appointments.index', compact('appointments', 'users', 'services'));
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'          => 'required', 
            'doctor_id'        => 'required', 
            'service_id'       => 'required',
            'appointment_date' => 'required',
        ]);

        $appointment = Appointment::create([
            'patient_id'       => $validated['user_id'],
            'doctor_id'        => $validated['doctor_id'],
            'service_id'       => $validated['service_id'],
            'appointment_date' => $validated['appointment_date'],
        ]);

        try {
            $user = User::find($request->user_id);
            if ($user && $user->email) {
                Mail::to($user->email)->send(new AppointmentConfirmed($appointment));
            }
        } catch (\Exception $e) {
           
        }

        return redirect()->back()->with('success', 'Rendez-vous ajouté !');
    }

    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id'          => 'required',
            'doctor_id'        => 'required',
            'service_id'       => 'required',
            'appointment_date' => 'required',
            'status'           => 'required|in:pending,confirmed,cancelled',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update([
            'patient_id'       => $validated['user_id'],
            'doctor_id'        => $validated['doctor_id'],
            'service_id'       => $validated['service_id'],
            'appointment_date' => $validated['appointment_date'],
            'status'           => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Rendez-vous modifié avec succès!');
    }

    
    public function destroy($id)
    {
        Appointment::destroy($id);
        return redirect()->back()->with('success', 'Rendez-vous annulé!');
    }

   
    public function search(Request $request)
    {
        $query = $request->get('q'); 

        $appointmentsQuery = Appointment::with(['user', 'service']);

        if (!empty($query)) {
            $appointmentsQuery->where(function($mainQuery) use ($query) {
                $mainQuery->whereHas('user', function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('service', function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                });
            });
        }

        $appointments = $appointmentsQuery->latest()->get();

        $data = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'service_id' => $appointment->service_id,
                'appointment_date' => $appointment->appointment_date,
                'formatted_date' => \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y - H:i'),
                'status' => $appointment->status ?? 'pending',
                'user' => [
                    'name' => $appointment->user ? $appointment->user->name : 'Patient Inconnu'
                ],
                'service' => [
                    'name' => $appointment->service ? $appointment->service->name : 'Non spécifié'
                ],
            ];
    });

        return response()->json($data);
    }
    public function dashboard()
{
    // Calcul des statistiques pour les graphiques
    $stats = [
        'total'     => Appointment::count(),
        'confirmed' => Appointment::where('status', 'confirmed')->count(),
        'pending'   => Appointment::where('status', 'pending')->count(),
        'cancelled' => Appointment::where('status', 'cancelled')->count(),
    ];

    // Récupérer le nombre de rendez-vous par service pour le graphique
    $appointmentsByService = Appointment::select('service_id', DB::raw('count(*) as total'))
        ->with('service')
        ->groupBy('service_id')
        ->get();

    return view('dashboard', compact('stats', 'appointmentsByService'));
}
}