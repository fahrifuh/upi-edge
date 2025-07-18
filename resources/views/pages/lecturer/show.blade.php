<x-app-layout>
    <x-slot name="header">
        <h2 class="leading-tight">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('lecturer.index') }}">Data Dosen</a>
                </li>
                <li class="breadcrumb-item breadcrumb-active">{{ __('Lihat Detail Dosen') }}</li>
            </ol>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg px-4">
                <div class="p-6">
                    <h1 class="text-3xl font-extrabold mb-4">Detail Dosen</h1>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <img src="{{ asset($lecturer->photo) }}" alt="Foto Dosen"
                                class="w-full h-auto rounded-lg shadow-md">
                        </div>
                        <div class="col-span-2">
                            <table class="min-w-full bg-white">
                                <tbody>
                                    <tr>
                                        <td class="px-6 py-4 border-b">Nama</td>
                                        <td class="px-6 py-4 border-b">{{ $lecturer->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 border-b">NIP</td>
                                        <td class="px-6 py-4 border-b">{{ $lecturer->nip }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 border-b">Email</td>
                                        <td class="px-6 py-4 border-b">{{ $lecturer->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 border-b">Jurusan</td>
                                        <td class="px-6 py-4 border-b">{{ $lecturer->department }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 border-b">Alamat</td>
                                        <td class="px-6 py-4 border-b">{{ $lecturer->address }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 border-b">Riwayat Kegiatan</td>
                                        <td class="px-6 py-4 border-b">
                                            <ul class="list-disc pl-5">
                                                @foreach ($lecturer->activitySchedules as $activity)
                                                    <li>{{ $activity->agenda }}
                                                        ({{ \Carbon\Carbon::parse($activity->date)->translatedFormat('d F Y') }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
