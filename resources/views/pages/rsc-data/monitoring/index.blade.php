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
                    <table class="w-full align-middle border-slate-400 table mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>ID Perangkat</th>
                                <th>Nitrogen</th>
                                <th>Fosfor</th>
                                <th>Kalium</th>
                                <th>EC</th>
                                <th>pH Tanah</th>
                                <th>Suhu Tanah</th>
                                <th>Kelembapan Tanah</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0" id="fix-station-tbody">
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->device_id }}</td>
                                    <td>{{ $item->samples->Nitrogen }}</td>
                                    <td>{{ $item->samples->Phosporus }}</td>
                                    <td>{{ $item->samples->Kalium }}</td>
                                    <td>{{ $item->samples->Ec }}</td>
                                    <td>{{ $item->samples->Ph }}</td>
                                    <td>{{ $item->samples->Temperature }}</td>
                                    <td>{{ $item->samples->Humidity }}</td>
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
