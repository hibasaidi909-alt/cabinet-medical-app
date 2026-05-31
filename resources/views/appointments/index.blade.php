@extends('layouts.app')

@section('page-title', 'Gestion des Rendez-vous')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
        <!-- Moteur de recherche Axios -->
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">🔍</span>
            <input type="text" id="search-input" placeholder="Rechercher un patient ou service..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:border-blue-500 text-sm transition-all">
        </div>
        
        <!-- Button Ajouter (Ouvre la Modale) -->
        <button onclick="toggleModal(true)" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2 text-sm cursor-pointer">
            <span>➕</span> Nouveau Rendez-vous
        </button>
    </div>

    <!-- Table des Rendez-vous -->
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
                <!-- Ghadi itgénéra b Blade dynamic f l-awal wlla b Axios mn b3d -->
                @foreach($appointments as $app)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $app->user->name }}</td>
                    <td class="px-6 py-4">{{ $app->service->name }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ \Carbon\Carbon::parse($app->appointment_date)->format('d M Y - H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $app->status == 'confirmed' ? 'bg-emerald-50 text-emerald-700' : ($app->status == 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                            {{ $app->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="text-blue-600 hover:text-blue-800 font-medium">Modifier</button>
                        <form action="{{ route('appointments.destroy', $app->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Sûr ?')" class="text-rose-600 hover:text-rose-800 font-medium">Annuler</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- ================= MODALE AJOUT RAPIDE ================= -->
<div id="appointment-modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-slate-900 text-lg">Nouveau Rendez-vous</h3>
            <button onclick="toggleModal(false)" class="text-slate-400 hover:text-slate-600 text-xl cursor-pointer">&times;</button>
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
                <button type="button" onclick="toggleModal(false)" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 text-sm font-medium cursor-pointer">Annuler</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-sm cursor-pointer">Confirmer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Toggle Modal script smooth
    function toggleModal(show) {
        const modal = document.getElementById('appointment-modal');
        if(show) {
            modal.classList.remove('hidden');
            setTimeout(() => { modal.classList.add('opacity-100'); modal.firstElementChild.classList.add('scale-100'); }, 20);
        } else {
            modal.classList.remove('opacity-100'); modal.firstElementChild.classList.remove('scale-100');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    }

    // Axios Real-time Search
    document.getElementById('search-input').addEventListener('input', function(e) {
        let query = e.target.value;
        axios.get(`/api/appointments/search?q=${query}`)
            .then(response => {
                let html = '';
                response.data.forEach(app => {
                    html += `
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">${app.user.name}</td>
                            <td class="px-6 py-4">${app.service.name}</td>
                            <td class="px-6 py-4 text-slate-500">${app.appointment_date}</td>
                            <td class="px-6 py-4"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">${app.status}</span></td>
                            <td class="px-6 py-4 text-right font-medium text-blue-600">Gérer</td>
                        </tr>`;
                });
                document.getElementById('appointments-tbody').innerHTML = html;
            });
    });
</script>
@endpush
@endsection