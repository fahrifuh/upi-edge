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
            let table;
            $(document).ready(function() {
                table = $('#table-berlangsung').DataTable({
                    responsive: true,
                    ordering: false,
                    dom: '<"ms-5 mb-2"B>rtp',
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

            $('#table-selesai').DataTable({
                responsive: true,
                ordering: false,
                dom: '<"ms-5 mb-2"B>rtp',
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
        </script>
    @endpush
</x-app-layout>
