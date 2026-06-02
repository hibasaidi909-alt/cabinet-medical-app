@extends('layouts.app') @section('title', 'Dashboard - Statistiques')

@section('content')
<div class="p-6 space-y-6">
    <h1 class="text-2xl font-bold text-slate-800">Tableau de Bord</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-sm text-slate-400 font-medium">Total RDV</p>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-sm text-emerald-500 font-medium">Confirmés</p>
            <p class="text-2xl font-bold text-emerald-600">{{ $stats['confirmed'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-sm text-amber-500 font-medium">En attente</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-sm text-rose-500 font-medium">Annulés</p>
            <p class="text-2xl font-bold text-rose-600">{{ $stats['cancelled'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col items-center">
            <h3 class="text-sm font-semibold text-slate-700 mb-4 self-start">Répartition des Statuts</h3>
            <div class="w-64 h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col items-center">
            <h3 class="text-sm font-semibold text-slate-700 mb-4 self-start">Rendez-vous par Service</h3>
            <div class="w-full h-64">
                <canvas id="serviceChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Chart des Statuts (Pie Chart)
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'pie',
        data: {
            labels: ['Confirmé', 'En attente', 'Annulé'],
            datasets: [{
                data: [{{ $stats['confirmed'] }}, {{ $stats['pending'] }}, {{ $stats['cancelled'] }}],
                backgroundColor: ['#10b981', '#f59e0b', '#f43f5e']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // 2. Chart des Services (Bar Chart)
    const ctxService = document.getElementById('serviceChart').getContext('2d');
    new Chart(ctxService, {
        type: 'bar',
        data: {
            labels: {!! json_encode($appointmentsByService->map(fn($s) => $s->service?->name ?? 'Inconnu')) !!},
            datasets: [{
                label: 'Nombre de RDV',
                data: {!! json_encode($appointmentsByService->pluck('total')) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
</script>
@endsection