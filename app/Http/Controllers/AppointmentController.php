<?php

namespace App\Http\Controllers;
use App\Mail\AppointmentConfirmed;
use Illuminate\Support\Facades\Mail;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // عرض قائمة المواعيد
    public function index()
    {
        // غانجيبو المواعيد كاملة مع العلاقات ديالها (Eager Loading) باش تكون سريعة
        $appointments = Appointment::with(['patient', 'doctor', 'service'])->latest()->get();
        
        // غانجيبو الأطباء والخدمات باش نستعملوهم ف الـ Modals ديال الإضافة والتعديل من بعد
        $doctors = User::where('role', 'doctor')->get();
        $patients = User::where('role', 'patient')->get();
        $services = Service::all();

        return view('appointments.index', compact('appointments', 'doctors', 'patients', 'services'));
    }

    // حفظ موعد جديد (غانحتاجوها ف الـ Modal)
    public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required',
        'service_id' => 'required',
        'appointment_date' => 'required|date',
    ]);

    $appointment = Appointment::create($validated);

    // Envoi de l'email
    $user = User::find($request->user_id);
    Mail::to($user->email)->send(new AppointmentConfirmed($appointment));

    return redirect()->back()->with('success', 'Rendez-vous créé et e-mail envoyé!');
}

    // حذف أو إلغاء موعد
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Rendez-vous supprimé avec succès!');
    }
    public function search(Request $request)
{
    $query = $request->get('query');

    // جلب المواعيد حسب البحث أو جلب الكل إذا كانت الخانة فارغة
    $appointmentsQuery = Appointment::with(['patient', 'doctor', 'service']);

    if (!empty($query)) {
        $appointmentsQuery->where(function($mainQuery) use ($query) {
            $mainQuery->whereHas('patient', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('doctor', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('service', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            });
        });
    }

    $appointments = $appointmentsQuery->latest()->get();

    // 🌟 تحويل البيانات لـ مصفوفة بسيطة ومضمونة لتفادي خطأ 500 بصفة نهائية
    $data = $appointments->map(function ($appointment) {
        return [
            'id' => $appointment->id,
            'appointment_date' => $appointment->appointment_date,
            'status' => $appointment->status ?? 'pending',
            'patient' => [
                'name' => $appointment->patient ? $appointment->patient->name : '-'
            ],
            'doctor' => [
                'name' => $appointment->doctor ? $appointment->doctor->name : '-'
            ],
            'service' => [
                'name' => $appointment->service ? $appointment->service->name : '-'
            ],
        ];
    });

    return response()->json($data);
}

public function dashboard()
{
    $doctors = User::where('role', 'doctor')->get();
    $patients = User::where('role', 'patient')->get();
    $services = Service::all(); // يلا كنتي محتاجها ف الـ dashboard

    return view('dashboard', compact('doctors', 'patients', 'services'));
}
}
