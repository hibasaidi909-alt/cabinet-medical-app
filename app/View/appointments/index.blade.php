<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('messages.appointments') }}
            </h2>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Ajouter un RDV
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-4">
                    <input type="text" id="search-input" placeholder="{{ __('messages.search') }}" class="w-full p-2 border rounded shadow-sm">
                </div>

                <table class="min-w-full divide-y divide-gray-200 mt-4">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Médecin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="appointments-table-body">
                        @foreach($appointments as $appointment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->patient->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->doctor->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->service->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->appointment_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $appointment->status == 'confirmed' ? 'bg-green-100 text-green-800' : ($appointment->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $appointment->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" onsubmit="return confirm('Etes-vous sûr ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Annuler</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>