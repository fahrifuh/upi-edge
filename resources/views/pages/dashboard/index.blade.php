<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col space-y-2">
                        <div class=" flex justify-between">
                            <h1 class="text-3xl font-extrabold">Tabel Data Telemetri Fix Station</h1>
                        </div>
                        <div>
                            <h3>Terakhir diupdate: <span id="datetime-newest-data"></span></h3>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-scroll">
                    <table class="w-full align-middle border-slate-400 table mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>ID Lahan</th>
                                <th>Nitrogen</th>
                                <th>Fosfor</th>
                                <th>Kalium</th>
                                <th>EC</th>
                                <th>pH Tanah</th>
                                <th>Suhu Tanah</th>
                                <th>Kelembapan Tanah</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0 h-[500px]" id="fix-station-tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
