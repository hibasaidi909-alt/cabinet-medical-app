<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
    <div id="add-appointment-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-600 bg-opacity-50 h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Nouveau Rendez-vous</h3>
            
            <form action="{{ route('appointments.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Patient</label>
                    <select name="patient_id" class="w-full mt-1 p-2 border rounded" required>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Médecin</label>
                    <select name="doctor_id" class="w-full mt-1 p-2 border rounded" required>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Service</label>
                    <select name="service_id" class="w-full mt-1 p-2 border rounded" required>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} ({{ $service->price }} DH)</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Date & Heure</label>
                    <input type="datetime-local" name="appointment_date" class="w-full mt-1 p-2 border rounded" required>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('add-appointment-modal')" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                        Annuler
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // دالات فتح وإغلاق الـ Modal
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // 2️⃣ محرك البحث الديناميكي باستعمال Axios
    document.getElementById('search-input').addEventListener('input', function(e) {
        let query = e.target.value;

        // صيفط طلب لـ الـ Route ديال البحث للي غانكريوه دابا
        axios.get('/appointments/search?query=' + query)
            .then(function (response) {
                let appointments = response.data;
                let html = '';

                // إعادة بناء أسطر الجدول ديناميكياً
                appointments.forEach(function(appointment) {
                    let statusClass = appointment.status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                      (appointment.status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
                    
                    html += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">${appointment.patient.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${appointment.doctor.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${appointment.service.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${appointment.appointment_date}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                    ${appointment.status}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="/appointments/${appointment.id}" method="POST" onsubmit="return confirm('Etes-vous sûr ?')">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="text-red-600 hover:text-red-900">Annuler</button>
                                </form>
                            </td>
                        </tr>
                    `;
                });

                document.getElementById('appointments-table-body').innerHTML = html;
            })
            .catch(function (error) {
                console.log(error);
            });
    });
</script>
</x-app-layout>
