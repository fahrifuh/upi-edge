<x-app-layout>
    <x-slot name="header">
        <h2 class="leading-tight">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('rsc-data.index') }}">Data Rapid Soil Checker (RSC)</a>
                </li>
                <li class="breadcrumb-item breadcrumb-active">{{ __('Monitoring') }}</li>
            </ol>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col space-y-2">
                        <div class=" flex justify-between">
                            <h1 class="text-3xl font-extrabold">Tabel Monitoring Data Telemetri Fix Station</h1>
                        </div>
                        <div>
                            <h3>Terakhir diupdate: <span
                                    id="datetime-newest-data">{{ \Carbon\Carbon::parse($lastUpdated->created_at)->translatedFormat('d F Y H:i:s') }}</span>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-scroll">
                    <table class="w-full align-middle border-slate-400 table mb-0 px-2" id="fix-station-table">
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
                            @forelse ($data as $item)
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
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak Ada Data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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

            // DataTable (using jQuery)
            $(document).ready(function() {
                $('#fix-station-table').DataTable({
                    responsive: true,
                    ordering: false,
                    dom: '<"ms-5 mb-2"B>rt',
                    buttons: [{
                        extend: 'excel',
                        text: 'Export Excel',
                        title: 'Data Telemetri Rapid Soil Checker',
                        className: 'bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600',
                        filename: function() {
                            return `data_telemetri_rsc_${timestamp()}`;
                        }
                    }],
                    columnDefs: [{
                        className: "text-center",
                        targets: "_all"
                    }]
                });
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
                const data = p.data;
                const row = document.createElement("tr");

                row.innerHTML = `
                <td>${formatTimestamp(data.created_at)}</td>
                <td>${data.device_id}</td>
                <td>${data.samples.Nitrogen}</td>
                <td>${data.samples.Phosporus}</td>
                <td>${data.samples.Kalium}</td>
                <td>${data.samples.Ec}</td>
                <td>${data.samples.Ph}</td>
                <td>${data.samples.Temperature}</td>
                <td>${data.samples.Humidity}</td>
                `;

                document.getElementById("fix-station-tbody").prepend(row);
            });
        </script>
    @endpush
</x-app-layout>
