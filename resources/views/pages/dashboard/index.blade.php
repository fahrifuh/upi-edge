<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    @push('styles')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <x-card-summary href="{{ route('lecturer.index') }}" title="Total Dosen" total="{{ $lecturers }}" icon="fa-solid fa-chalkboard-user" />
                <x-card-summary href="{{ route('student.index') }}" title="Total Mahasiswa" total="{{ $students }}" icon="fa-solid fa-user-graduate" />
                <x-card-summary href="{{ route('device.index') }}" title="Total Perangkat" total="{{ $devices }}" icon="fa-solid fa-satellite-dish" />
                <x-card-summary href="{{ route('activity-schedule.index') }}" title="Total Jadwal Kegiatan Praktikum" total="{{ $activitySchedules }}"
                    icon="fa-solid fa-calendar-days" />
            </div>

            <div class="bg-white rounded-xl shadow p-6 mb-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-calendar text-blue-500"></i>
                    Jadwal Kegiatan Mendatang
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse ($upcomingActivities as $activity)
                        <div class="mb-4 border border-gray-200 p-4 rounded-lg">
                            <div class="text-xl font-semibold text-gray-900">{{ $activity->agenda }}</div>
                            <div class="text-base text-gray-500 mt-2">
                                {{ \Carbon\Carbon::parse($activity->date)->format('d M Y') }}<br>
                                {{ substr($activity->start_time, 0, 5) }} – {{ substr($activity->end_time, 0, 5) }}
                            </div>
                            <div class="mt-5 text-blue-600 font-bold text-xl countdown"
                                data-start="{{ \Carbon\Carbon::parse($activity->date . ' ' . $activity->start_time)->toIso8601String() }}">
                                Memuat countdown...
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-500">Belum ada kegiatan terjadwal.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 mb-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-blue-500"></i>
                    Grafik Telemetri 24 Jam Terakhir
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="w-full">
                        <div id="nitrogenChart"></div>
                    </div>
                    <div class="w-full">
                        <div id="phosporusChart"></div>
                    </div>
                    <div class="w-full">
                        <div id="kaliumChart"></div>
                    </div>
                    <div class="w-full">
                        <div id="ecChart"></div>
                    </div>
                    <div class="w-full">
                        <div id="phChart"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @push('scripts')
        <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
        <script>
            // generate random color for chart
            const chartColors = [
                '#42a5f5', // biru
                '#66bb6a', // hijau
                '#ffa726', // oranye
                '#ab47bc', // ungu
                '#ef5350', // merah
                '#26c6da', // cyan
                '#ffca28', // kuning
                '#8d6e63', // coklat
            ];
            let usedColors = [];

            const getUniqueRandomColor = () => {
                if (usedColors.length === chartColors.length) {
                    usedColors = []; // reset colors
                }
                let color;
                do {
                    color = chartColors[Math.floor(Math.random() * chartColors.length)];
                } while (usedColors.includes(color));
                usedColors.push(color);
                return color;
            }

            // function for render chart
            const renderAreaChart = (containerId, title, titleSeries, data, unit = '') => {
                const chartId = containerId.replace('#', '');
                const fillColor = getUniqueRandomColor();

                const options = {
                    chart: {
                        id: chartId,
                        type: 'area',
                        zoom: {
                            enabled: false
                        },
                        toolbar: {
                            show: false
                        }
                    },
                    title: {
                        text: title + (unit ? ` (${unit})` : ''),
                        align: 'center',
                        style: {
                            fontSize: '16px',
                            fontWeight: 'bold',
                            color: '#333'
                        }
                    },
                    series: [{
                        name: titleSeries,
                        data: data // format: [{ x: '2025-07-22 08:00:00', y: 7.1 }, ...]
                    }],
                    xaxis: {
                        type: 'datetime',
                        labels: {
                            datetimeUTC: false,
                            format: 'HH:mm'
                        },
                        title: {
                            text: 'Waktu (WIB)'
                        }
                    },
                    yaxis: {
                        title: {
                            text: `${titleSeries} (${unit})`
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    fill: {
                        type: 'solid',
                        colors: [fillColor]
                    },
                    tooltip: {
                        x: {
                            format: 'dd MMM yyyy HH:mm'
                        },
                        y: {
                            formatter: function(value) {
                                return value !== null ? value + ' ' + unit : '-';
                            }
                        }
                    }
                };

                const chart = new ApexCharts(document.querySelector(containerId), options);
                chart.render();
            }

            // function for update area chart when new data received
            const updateAreaChart = (chartId, value, timestamp) => {
                ApexCharts.exec(chartId, 'appendData', [{
                    data: [{
                        x: new Date(timestamp),
                        y: value
                    }]
                }]);
            }

            // Pusher
            Pusher.logToConsole = true;

            var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
                cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}"
            });

            var channel = pusher.subscribe('sensor-data');
            channel.bind('SensorData', function(p) {
                const data = p.data;

                updateAreaChart('nitrogenChart', data.samples.Nitrogen, data.created_at);
                updateAreaChart('phosporusChart', data.samples.Phosporus, data.created_at);
                updateAreaChart('kaliumChart', data.samples.Kalium, data.created_at);
                updateAreaChart('ecChart', data.samples.Ec, data.created_at);
                updateAreaChart('phChart', data.samples.Ph, data.created_at);
            });

            document.addEventListener('DOMContentLoaded', function() {
                const countdownElements = document.querySelectorAll('.countdown');

                const updateCountdowns = () => {
                    const now = Date.now();

                    countdownElements.forEach(el => {
                        const startTime = new Date(el.dataset.start).getTime();
                        const diff = startTime - now;

                        if (diff <= 0) {
                            el.textContent = "Sedang berlangsung";
                            return;
                        }

                        const hours = Math.floor(diff / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                        el.textContent = `Berlangsung dalam ${hours} jam ${minutes} menit ${seconds} detik`;
                    });
                }

                // Jalankan update pertama kali dan setiap detik
                updateCountdowns();
                const interval = setInterval(() => {
                    updateCountdowns();

                    // Auto-stop jika semua sudah lewat (opsional)
                    const unfinished = [...countdownElements].filter(el => {
                        return new Date(el.dataset.start).getTime() > Date.now();
                    });
                    if (unfinished.length === 0) clearInterval(interval);
                }, 1000);

                renderAreaChart('#nitrogenChart', 'Grafik data parameter Nitrogen', 'Nitrogen',
                    @json($n), 'mg/kg')
                renderAreaChart('#phosporusChart', 'Grafik data parameter Phosporus', 'Phosporus',
                    @json($p), 'mg/kg')
                renderAreaChart('#kaliumChart', 'Grafik data parameter Kalium', 'Kalium', @json($k),
                    'mg/kg')
                renderAreaChart('#ecChart', 'Grafik data parameter Ec', 'Ec', @json($ec), 'uS/cm')
                renderAreaChart('#phChart', 'Grafik data parameter Ph', 'Ph', @json($ph))
            });
        </script>
    @endpush
</x-app-layout>
