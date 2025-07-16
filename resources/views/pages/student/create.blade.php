<x-app-layout>
    <x-slot name="header">
        <h2 class="leading-tight">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('student.index') }}">Data Mahasiswa</a>
                </li>
                <li class="breadcrumb-item breadcrumb-active">{{ __('Tambah Data Mahasiswa ') }}</li>
            </ol>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg px-4">
                <div class="p-6">
                    <h1 class="text-3xl font-extrabold mb-4">Tambah Data Mahasiswa</h1>
                    <form action="{{ route('student.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="name">{{ __('Nama') }}</x-input-label>
                                <x-text-input id="name" class="block mt-1 w-full rounded-xl" type="text"
                                    name="name" :value="old('name')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nim">{{ __('NIM') }}</x-input-label>
                                <x-text-input id="nim" class="block mt-1 w-full rounded-xl" type="text"
                                    name="nim" :value="old('nim')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('nim')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="email">{{ __('Email') }}</x-input-label>
                                <x-text-input id="email" class="block mt-1 w-full rounded-xl" type="email"
                                    name="email" :value="old('email')" required autofocus autocomplete="email" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="phone">{{ __('Nomor Telepon') }}</x-input-label>
                                <x-text-input id="phone" class="block mt-1 w-full rounded-xl" type="tel"
                                    name="phone" :value="old('phone')" required autofocus autocomplete="phone" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="birth_date">{{ __('Tanggal Lahir') }}</x-input-label>
                                <x-text-input id="birth_date" class="block mt-1 w-full rounded-xl" type="date"
                                    name="birth_date" :value="old('birth_date')" required autofocus autocomplete="birth_date" />
                                <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="gender">{{ __('Jenis Kelamin') }}</x-input-label>
                                <label class="flex items-center gap-2 mt-1">
                                    <x-text-input id="gender" class="block mt-1 rounded-xl" type="radio"
                                        name="gender" value="l" :checked="old('gender') == 'l'" required />
                                    <span>Pria</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <x-text-input id="gender" class="block mt-1 rounded-xl" type="radio"
                                        name="gender" value="p" :checked="old('gender') == 'p'" required />
                                    <span>Wanita</span>
                                </label>

                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>
                            <div class="col-span-2">
                                <x-input-label for="address">{{ __('Alamat') }}</x-input-label>
                                <textarea id="address" class="block mt-1 w-full rounded-xl" name="address" rows="3" required>{{ old('address') }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                            <div class="col-span-2 text-right">
                                <x-primary-button class="mt-3 rounded-xl">
                                    {{ __('Simpan') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
