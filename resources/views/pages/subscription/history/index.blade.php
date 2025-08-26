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
                    <a href="{{ route('subscription.index') }}">Manajemen Langganan</a>
                </li>
                <li class="breadcrumb-item breadcrumb-active">{{ __('Riwayat Transaksi') }}</li>
            </ol>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col space-y-4">
                        <div class="flex justify-between">
                            <h1 class="text-3xl font-extrabold">Tabel Riwayat Transaksi</h1>
                        </div>
                        {{-- <div>
                            <h3>Terakhir diupdate: <span
                                    id="datetime-newest-data">{{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated->created_at)->translatedFormat('d F Y H:i:s') : '-' }}</span>
                            </h3>
                        </div> --}}
                        <!-- Filter Section -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold mb-3">Filter Data</h4>
                            <form id="filterForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                        Mulai</label>
                                    <input type="date" id="start_date" name="start_date"
                                        value="{{ request('start_date') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                        Akhir</label>
                                    <input type="date" id="end_date" name="end_date"
                                        value="{{ request('end_date') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                                <div class="flex items-end">
                                    <button type="submit"
                                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                                        Filter
                                    </button>
                                    <button type="button" id="resetFilter"
                                        class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                        Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-scroll">
                    <table class="w-full align-middle border-slate-400 table mb-0 px-2" id="histories-table">
                        <thead>
                            <tr>
                                <th class="dt-center">Order ID</th>
                                <th class="dt-center">User</th>
                                <th class="dt-center">Plan</th>
                                <th class="dt-center">Metode Pembayaran</th>
                                <th class="dt-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0" id="histories-tbody">
                            @foreach ($histories as $history)
                                <tr>
                                    <td>{{ $history->order_id }}</td>
                                    <td>{{ $history->user->name }}</td>
                                    <td>{{ $history->plan->name }}</td>
                                    <td>{{ $history->payment_type }}</td>
                                    <td>{{ $history->transaction_status }}</td>
                                    {{-- <td>
                                        <form
                                            action="{{ route('rsc-data.destroy', ['id' => $item->id, 'page' => 'rm']) }}"
                                            method="POST" class="delete-form" data-series="{{ $item->created_at }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">
                                                <i class="fa fa-trash text-red-500"></i>
                                            </button>
                                        </form>
                                    </td> --}}
                                </tr>
                            @endforeach
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
            let table;
            $(document).ready(function() {
                table = $('#histories-table').DataTable({
                    responsive: true,
                    ordering: false,
                    dom: '<"ms-5 mb-2"B>rtp',
                    buttons: [{
                        extend: 'excel',
                        text: 'Export Excel',
                        title: 'Data Riwayat Transaksi',
                        className: 'bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600',
                        filename: function() {
                            return `riwayat_transaksi_${timestamp()}`;
                        }
                    }],
                    columnDefs: [{
                        className: "text-center",
                        targets: "_all"
                    }],
                    language: {
                        emptyTable: "Tidak ada riwayat transaksi yang tersedia",
                        paginate: {
                            previous: "<",
                            next: ">"
                        }
                    }
                });

                // // Handle filter form submission
                // $('#filterForm').on('submit', function(e) {
                //     e.preventDefault();
                //     const formData = new FormData(this);
                //     const params = new URLSearchParams(formData);
                //     window.location.href = '{{ route('rsc-data.monitoring.index') }}?' + params.toString();
                // });

                // // Handle reset filter
                // $('#resetFilter').on('click', function() {
                //     window.location.href = '{{ route('rsc-data.monitoring.index') }}';
                // });

                // // Update device ID options when date range changes
                // $('#start_date, #end_date').on('change', function() {
                //     updateDeviceIdOptions();
                // });

                // // Initialize device options if date range is already set
                // if ($('#start_date').val() && $('#end_date').val()) {
                //     updateDeviceIdOptions();
                // }

                
            });


            // // Enable pusher logging - don't include this in production
            // const formatTimestamp = (timestamp) => {
            //     return new Date(timestamp).toLocaleString('sv-SE');
            // }

            // Pusher.logToConsole = true;

            // var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
            //     cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}"
            // });

            // var channel = pusher.subscribe('sensor-data');
            // channel.bind('SensorData', function(p) {
            //     const data = p.raw;
            //     const newRow = table.row.add([
            //         formatTimestamp(data.created_at),
            //         data.device_id,
            //         `${data.samples.Nitrogen} mg/kg`,
            //         `${data.samples.Phosporus} mg/kg`,
            //         `${data.samples.Kalium} mg/kg`,
            //         `${data.samples.Ec} uS/cm`,
            //         `${data.samples.Ph}`,
            //         `${data.samples.Temperature} &deg;C`,
            //         `${data.samples.Humidity} %`,
            //     ]).draw(false);

            //     let newIndex = table.rows().count() - 1;
            //     let newNode = table.row(newIndex).node();
            //     $(newNode).prependTo('#fix-station-tbody')
            // });

            // @if (session('success'))
            //     Swal.fire({
            //         icon: 'success',
            //         title: 'Berhasil!',
            //         text: '{{ session('success') }}',
            //         showConfirmButton: false,
            //         timer: 2000,
            //     });
            // @endif

            // document.querySelectorAll('.delete-form').forEach(form => {
            //     form.addEventListener('submit', function(event) {
            //         event.preventDefault();

            //         const dataTimestamp = this.getAttribute('data-series');
            //         Swal.fire({
            //             title: 'Konfirmasi',
            //             text: `Apakah Anda yakin ingin menghapus data RSC dengan timestamp ${dataTimestamp}?`,
            //             icon: 'warning',
            //             showCancelButton: true,
            //             confirmButtonText: 'Ya, Hapus!',
            //             cancelButtonText: 'Batal'
            //         }).then((result) => {
            //             if (result.isConfirmed) {
            //                 this.submit();
            //             }
            //         });
            //     })
            // });
        </script>
    @endpush
</x-app-layout>
