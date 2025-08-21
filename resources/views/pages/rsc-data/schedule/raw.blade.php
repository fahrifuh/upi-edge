<x-app-layout>
    @push('styles')
        <style>
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.25rem 0.75rem !important;
                margin: 1rem 0.25rem !important;
                border: 1px solid #d1d5db !important;
                /* gray-300 */
                border-radius: 0.5rem !important;
                /* rounded-md */
                font-size: 1rem !important;
                /* text-sm */
                color: #374151;
                /* gray-700 */
                background-color: #ffffff !important;
                /* white */
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                background-color: #f3f4f6 !important;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                background-color: #EF4444 !important;
                /* blue-600 */
                color: #ffffff !important;
                /* white */
                font-weight: 600 !important;
                /* font-semibold */
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background-color: #DC2626 !important;
                /* blue-600 */
                color: #ffffff !important;
                /* white */
            }
        </style>
    @endpush
    <x-slot name="header">
        <h2 class="leading-tight">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('rsc-data.index') }}">Data Rapid Soil Checker</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('rsc-data.schedule.index') }}">Raw Penjadwalan</a>
                </li>
                <li class="breadcrumb-item breadcrumb-active">{{ __($schedule->agenda) }}</li>
            </ol>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col space-y-2">
                        <div class="flex justify-between">
                            <h1 class="text-3xl font-extrabold">Tabel Raw Data Telemetri Fix Station</h1>
                        </div>
                        <!-- Button untuk prompt rekomendasi tanaman ke gemini -->
                        <div class="w-auto ms-auto flex flex-col gap-1">
                            <button id="openModalBtn"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                                Cek Rekomendasi Tanaman
                            </button>
                            <span class="text-sm text-slate-600 italic text-end">*dikerjakan oleh Gemini AI</span>
                        </div>
                    </div>
                </div>
                @if ($status == 'belum')
                    <div class="text-yellow-600 text-center py-8">
                        Jadwal ini akan dimulai pada
                        <strong>{{ $start->translatedFormat('l, d F Y') . ' pukul ' . $start->translatedFormat('H:i') }}</strong>,
                        kembali lagi pada waktu tersebut.
                    </div>
                @elseif ($status == 'berlangsung')
                    <div class="overflow-x-scroll">
                        <table class="w-full align-middle border-slate-400 table mb-0" id="table-berlangsung">
                            <thead>
                                <tr>
                                    <th class="dt-center">Waktu</th>
                                    <th class="dt-center">ID Perangkat</th>
                                    <th class="dt-center">Nitrogen</th>
                                    <th class="dt-center">Fosfor</th>
                                    <th class="dt-center">Kalium</th>
                                    <th class="dt-center">EC</th>
                                    <th class="dt-center">pH Tanah</th>
                                    <th class="dt-center">Suhu Tanah</th>
                                    <th class="dt-center">Kelembapan Tanah</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="fix-station-tbody">
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->device_id }}</td>
                                        <td>{{ $item->samples->Nitrogen }} mg/kg</td>
                                        <td>{{ $item->samples->Phosporus }} mg/kg</td>
                                        <td>{{ $item->samples->Kalium }} mg/kg</td>
                                        <td>{{ $item->samples->Ec }} uS/cm</td>
                                        <td>{{ $item->samples->Ph }}</td>
                                        <td>{{ $item->samples->Temperature }} &deg;C</td>
                                        <td>{{ $item->samples->Humidity }} %</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif ($status == 'selesai')
                    <div class="overflow-x-scroll">
                        <table class="w-full align-middle border-slate-400 table mb-0" id="table-selesai">
                            <thead>
                                <tr>
                                    <th class="dt-center">Waktu</th>
                                    <th class="dt-center">ID Perangkat</th>
                                    <th class="dt-center">Nitrogen</th>
                                    <th class="dt-center">Fosfor</th>
                                    <th class="dt-center">Kalium</th>
                                    <th class="dt-center">EC</th>
                                    <th class="dt-center">pH Tanah</th>
                                    <th class="dt-center">Suhu Tanah</th>
                                    <th class="dt-center">Kelembapan Tanah</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->device_id }}</td>
                                        <td>{{ $item->samples->Nitrogen }} mg/kg</td>
                                        <td>{{ $item->samples->Phosporus }} mg/kg</td>
                                        <td>{{ $item->samples->Kalium }} mg/kg</td>
                                        <td>{{ $item->samples->Ec }} uS/cm</td>
                                        <td>{{ $item->samples->Ph }}</td>
                                        <td>{{ $item->samples->Temperature }} &deg;C</td>
                                        <td>{{ $item->samples->Humidity }} %</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="recommendationModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-3xl rounded-2xl shadow-lg p-6 relative max-h-[90vh] overflow-y-auto">
            <!-- Tombol close -->
            <button id="closeModalBtn" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
                <i class="fa-solid fa-xmark text-4xl"></i>
            </button>

            <h2 class="text-xl font-semibold mb-4">Rekomendasi Tanaman</h2>

            <!-- Loader -->
            <div id="loading" class="text-center py-4 hidden">
                <span class="text-gray-500">Sedang memproses...</span>
            </div>

            <!-- Klasifikasi tanah -->
            <div id="soilClassification" class="hidden mb-6 p-4 border-l-4 border-green-500 bg-green-50 rounded">
                <h3 class="font-semibold text-green-700">Klasifikasi Tanah</h3>
                <p id="soilCategory" class="text-gray-800 font-medium"></p>
                <p id="soilDescription" class="text-gray-600 text-sm"></p>
            </div>

            <!-- Container hasil -->
            <div id="recommendationList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Card hasil akan ditambahkan via JS -->
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
        <script>
            // Get current timestamp for filename
            const timestamp = () => {
                const now = new Date();
                const date = now.getDate().toString().padStart(2, '0');
                const month = (now.getMonth() + 1).toString().padStart(2, '0');
                const year = now.getFullYear();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const seconds = now.getSeconds().toString().padStart(2, '0');

                return `${year}${month}${date}_${hours}${minutes}${seconds}`;
            }

            const agenda = "{{ $schedule->agenda }}";
            const datetimeAgenda = "{{ $start->translatedFormat('l, d F Y H:i') . ' - ' . $end->translatedFormat('H:i') }}";

            // DataTable (using jQuery)
            let table;
            $(document).ready(function() {
                table = $('#table-berlangsung').DataTable({
                    responsive: true,
                    ordering: false,
                    dom: '<"ms-5 mb-2"B>rtp',
                    buttons: [{
                        extend: 'excel',
                        text: 'Export Excel',
                        title: function() {
                            return `Data Telemetri Rapid Soil Checker (${agenda} - ${datetimeAgenda})`;
                        },
                        className: 'bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600',
                        filename: function() {
                            return `data_telemetri_rsc_${timestamp()}`;
                        }
                    }],
                    columnDefs: [{
                        className: "text-center",
                        targets: "_all"
                    }],
                    language: {
                        emptyTable: "Tidak ada data telemetri yang tersedia",
                        paginate: {
                            previous: "<",
                            next: ">"
                        }
                    }
                });
            });

            $('#table-selesai').DataTable({
                responsive: true,
                ordering: false,
                dom: '<"ms-5 mb-2"B>rtp',
                buttons: [{
                    extend: 'excel',
                    text: 'Export Excel',
                    title: function() {
                        return `Data Telemetri Rapid Soil Checker (${agenda} - ${datetimeAgenda})`;
                    },
                    className: 'bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600',
                    filename: function() {
                        return `data_telemetri_rsc_${timestamp()}`;
                    }
                }],
                columnDefs: [{
                    className: "text-center",
                    targets: "_all"
                }],
                language: {
                    emptyTable: "Tidak ada data telemetri yang tersedia",
                    paginate: {
                        previous: "<",
                        next: ">"
                    }
                }
            });

            // Enable pusher logging - don't include this in production
            const formatTimestamp = (timestamp) => {
                return new Date(timestamp).toLocaleString('sv-SE');
            }

            Pusher.logToConsole = true;

            var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
                cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}"
            });

            var channel = pusher.subscribe('sensor-data');
            channel.bind('SensorData', function(p) {
                const data = p.raw;
                const timestamp = new Date(data.created_at);
                const start = new Date("{{ $start->format('Y-m-d H:i:s') }}");
                const end = new Date("{{ $end->format('Y-m-d H:i:s') }}");
                if (timestamp >= start && timestamp <= end) {
                    const newRow = table.row.add([
                        formatTimestamp(data.created_at),
                        data.device_id,
                        `${data.samples.Nitrogen} mg/kg`,
                        `${data.samples.Phosporus} mg/kg`,
                        `${data.samples.Kalium} mg/kg`,
                        `${data.samples.Ec} uS/cm`,
                        `${data.samples.Ph}`,
                        `${data.samples.Temperature} &deg;C`,
                        `${data.samples.Humidity} %`,
                    ]).draw(false);

                    let newIndex = table.rows().count() - 1;
                    let newNode = table.row(newIndex).node();
                    $(newNode).prependTo('#fix-station-tbody')
                }
            });

            document.addEventListener("DOMContentLoaded", function() {
                const openModalBtn = document.getElementById('openModalBtn');
                const closeModalBtn = document.getElementById('closeModalBtn');
                const modal = document.getElementById('recommendationModal');
                const loading = document.getElementById('loading');
                const listContainer = document.getElementById('recommendationList');
                const soilBox = document.getElementById("soilClassification");
                const soilCategory = document.getElementById("soilCategory");
                const soilDescription = document.getElementById("soilDescription");
                const scheduleId = "{{ $schedule->id }}";

                // Buka modal
                openModalBtn.addEventListener('click', async () => {
                    modal.classList.remove('hidden');
                    listContainer.innerHTML = ''; // reset isi
                    soilBox.classList.add("hidden"); // reset klasifikasi tanah
                    loading.classList.remove('hidden');

                    try {
                        // Panggil endpoint Laravel yang mengakses Gemini
                        const res = await fetch(
                            `/api/rekomendasi-tanaman?source=fix&scheduleId=${scheduleId}`);
                        const data = await res.json();
                        loading.classList.add('hidden');

                        if (data.response) {
                            if (data.response.klasifikasi_tanah) {
                                soilBox.classList.remove("hidden");
                                soilCategory.innerText =
                                    `Kategori Tanah: ${data.response.klasifikasi_tanah.kategori}`;
                                soilDescription.innerText = data.response.klasifikasi_tanah.deskripsi;
                            }
                            if (data.response.tanaman_rekomendasi) {
                                data.response.tanaman_rekomendasi.forEach(item => {
                                    const card = `
                                    <div class="border rounded-xl p-4 shadow hover:shadow-md transition">
                                        <h3 class="font-bold text-lg">${item.nama}</h3>
                                        <p class="text-sm text-green-600">Kategori: ${item.kategori}</p>
                                    <p class="text-gray-600 mt-2">${item.alasan}</p>
                                    </div>
                                `;
                                    listContainer.insertAdjacentHTML('beforeend', card);
                                });
                            }
                        } else {
                            listContainer.innerHTML =
                                `<p class="text-red-500">Tidak ada data rekomendasi.</p>`;
                        }
                    } catch (err) {
                        loading.classList.add('hidden');
                        listContainer.innerHTML = `<p class="text-red-500">Gagal mengambil data.</p>`;
                        console.error(err);
                    }
                });

                // Tutup modal
                closeModalBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });
            });
        </script>
    @endpush
</x-app-layout>
