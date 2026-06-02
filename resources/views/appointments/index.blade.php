@extends('layouts.app')

@section('page-title', 'Gestion des Rendez-vous')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    
    <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">🔍</span>
            <input type="text" id="search-input" placeholder="{{ __('messages.search') }}" 
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:border-blue-500 text-sm transition-all">
        </div>
        
        <button onclick="toggleAddModal(true)" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2 text-sm cursor-pointer">
            <span>➕</span> Nouveau Rendez-vous
        </button>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Patient</th>
                    <th class="px-6 py-4">Service</th>
                    <th class="px-6 py-4">Date & Heure</th>
                    <th class="px-6 py-4">Statut</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="appointments-tbody" class="divide-y divide-slate-100 text-sm text-slate-700">
                @foreach($appointments as $app)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-900">
                        {{ $app->user?->name ?? 'Patient Inconnu' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $app->service?->name ?? 'Service Non Spécifié' }}
                    </td>
                    <td class="px-6 py-4 text-slate-500">{{ \Carbon\Carbon::parse($app->appointment_date)->format('d M Y - H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $app->status == 'confirmed' ? 'bg-emerald-50 text-emerald-700' : ($app->status == 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                            {{ $app->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button type="button" onclick="openEditModal({{ $app->id }}, {{ $app->patient_id }}, {{ $app->doctor_id }}, {{ $app->service_id }}, '{{ $app->status }}', '{{ $app->appointment_date }}')" class="text-blue-600 hover:text-blue-800 font-medium cursor-pointer">
                            Modifier
                        </button>
                        <form action="{{ route('appointments.destroy', $app->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Sûr ?')" class="text-rose-600 hover:text-rose-800 font-medium cursor-pointer">Annuler</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="add-appointment-modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-slate-900 text-lg">Nouveau Rendez-vous</h3>
            <button onclick="toggleAddModal(false)" class="text-slate-400 hover:text-slate-600 text-xl cursor-pointer">&times;</button>
        </div>
        <form action="{{ route('appointments.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-500 mb-1.5">Patient</label>
                <select name="user_id" class="w-full p-2.5 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:border-blue-500">
                    @foreach($users as $user) <option value="{{ $user->id }}">{{ $user->name }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-500 mb-1.5">Médecin</label>
                <select name="doctor_id" class="w-full p-2.5 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:border-blue-500">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">Dr. {{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-500 mb-1.5">Service</label>
                <select name="service_id" class="w-full p-2.5 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:border-blue-500">
                    @foreach($services as $service) <option value="{{ $service->id }}">{{ $service->name }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-500 mb-1.5">Date & Heure</label>
                <input type="datetime-local" name="appointment_date" class="w-full p-2.5 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="toggleAddModal(false)" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 text-sm font-medium cursor-pointer">Annuler</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-sm cursor-pointer">Confirmer</button>
            </div>
        </form>
    </div>
</div>

<div id="edit-appointment-modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-slate-900 text-lg">Modifier le Rendez-vous</h3>
            <button onclick="toggleEditModal(false)" class="text-slate-400 hover:text-slate-600 text-xl cursor-pointer">&times;</button>
        </div>
        <form id="edit-form" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-500 mb-1.5">Patient</label>
                <select id="edit-user" name="user_id" class="w-full p-2.5 border border-slate-200 rounded-xl bg-slate-50 text-sm">
                    @foreach($users as $user) <option value="{{ $user->id }}">{{ $user->name }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-500 mb-1.5">Médecin</label>
                <select id="edit-doctor" name="doctor_id" class="w-full p-2.5 border border-slate-200 rounded-xl bg-slate-50 text-sm">
                    @foreach($users as $user) <option value="{{ $user->id }}">Dr. {{ $user->name }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-500 mb-1.5">Service</label>
                <select id="edit-service" name="service_id" class="w-full p-2.5 border border-slate-200 rounded-xl bg-slate-50 text-sm">
                    @foreach($services as $service) <option value="{{ $service->id }}">{{ $service->name }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-500 mb-1.5">Statut</label>
                <select id="edit-status" name="status" class="w-full p-2.5 border border-slate-200 rounded-xl bg-slate-50 text-sm">
                    <option value="pending">En attente (Pending)</option>
                    <option value="confirmed">Confirmé</option>
                    <option value="cancelled">Annulé</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-500 mb-1.5">Date & Heure</label>
                <input type="datetime-local" id="edit-date" name="appointment_date" class="w-full p-2.5 border border-slate-200 rounded-xl bg-slate-50 text-sm">
            </div>
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="toggleEditModal(false)" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 text-sm font-medium">Annuler</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium shadow-sm">Sauvegarder</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ➕ Gestion de la Modale d'Ajout
    function toggleAddModal(show) {
        const modal = document.getElementById('add-appointment-modal');
        if(show) {
            modal.classList.remove('hidden');
            setTimeout(() => { 
                modal.classList.add('opacity-100'); 
                modal.firstElementChild.classList.add('scale-100'); 
            }, 20);
        } else {
            modal.classList.remove('opacity-100'); 
            modal.firstElementChild.classList.remove('scale-100');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    }

    // 📝 Gestion de la Modale de Modification
    function toggleEditModal(show) {
        const modal = document.getElementById('edit-appointment-modal');
        if(show) {
            modal.classList.remove('hidden');
            setTimeout(() => { 
                modal.classList.add('opacity-100'); 
                modal.firstElementChild.classList.add('scale-100'); 
            }, 20);
        } else {
            modal.classList.remove('opacity-100'); 
            modal.firstElementChild.classList.remove('scale-100');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    }

    // 🔓 Fonction pour pré-remplir la modale de modification
    function openEditModal(appId, patientId, doctorId, serviceId, status, date) {
        document.getElementById('edit-form').action = `/appointments/${appId}`;
        document.getElementById('edit-user').value = patientId;
        document.getElementById('edit-doctor').value = doctorId;
        document.getElementById('edit-service').value = serviceId;
        document.getElementById('edit-status').value = status;
        
        if(date) {
            let formattedDate = date.replace(" ", "T").substring(0, 16);
            document.getElementById('edit-date').value = formattedDate;
        }
        
        toggleEditModal(true);
    }

    // 🔍 Recherche interactive Axios
    document.getElementById('search-input').addEventListener('input', function(e) {
        let query = e.target.value;
        
        axios.get(`/appointments/search?q=${query}`)
            .then(response => {
                let html = '';
                if(response.data.length === 0) {
                    html = `<tr><td colspan="5" class="px-6 py-4 text-center text-slate-400">Aucun rendez-vous trouvé</td></tr>`;
                } else {
                    response.data.forEach(app => {
                        let statusClass = app.status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : (app.status === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700');
                        
                        html += `
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-900">${app.user.name}</td>
                                <td class="px-6 py-4">${app.service.name}</td>
                                <td class="px-6 py-4 text-slate-500">${app.formatted_date}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${statusClass}">
                                        ${app.status}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button type="button" onclick="openEditModal(${app.id}, ${app.patient_id}, ${app.doctor_id}, ${app.service_id}, '${app.status}', '${app.appointment_date}')" class="text-blue-600 hover:text-blue-800 font-medium cursor-pointer">Modifier</button>
                                    <form action="/appointments/${app.id}" method="POST" class="inline">
                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content ?? ''}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" onclick="return confirm('Sûr ?')" class="text-rose-600 hover:text-rose-800 font-medium cursor-pointer">Annuler</button>
                                    </form>
                                </td>
                            </tr>`;
                    });
                }
                document.getElementById('appointments-tbody').innerHTML = html;
            })
            .catch(error => console.error("Erreur de recherche:", error));
    });
</script>
@endpush
@endsection