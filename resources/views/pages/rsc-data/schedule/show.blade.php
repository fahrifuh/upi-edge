<x-app-layout>
    <x-slot name="header">
        <h2 class="leading-tight">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('rsc-data.index') }}">Data Rapid Soil Checker</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('rsc-data.schedule.index') }}">Penjadwalan</a>
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
                            <h1 class="text-3xl font-extrabold">Tabel Data Telemetri Fix Station</h1>
                        </div>
                        {{-- <div>
                            <h3>Terakhir diupdate: <span
                                    id="datetime-newest-data">{{ \Carbon\Carbon::parse($lastUpdated->created_at)->translatedFormat('d F Y H:i:s') }}</span>
                            </h3>
                        </div> --}}
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
                @elseif ($status == 'selesai')
                    <div class="overflow-x-scroll">
                        <table class="w-full align-middle border-slate-400 table mb-0" id="table-selesai">
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
                            <tbody class="table-border-bottom-0">
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
                @endif
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
                const timestamp = new Date(data.created_at);
                const start = new Date("{{ $start->format('Y-m-d H:i:s') }}");
                const end = new Date("{{ $end->format('Y-m-d H:i:s') }}");
                const row = document.createElement("tr");

                if (timestamp >= start && timestamp <= end) {
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
                }
            });
        </script>
    @endpush
</x-app-layout>
