<x-app-layout>
    <x-slot name="header">
        <h2 class="leading-tight">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('lecturer.index') }}">Data Dosen</a>
                </li>
                <li class="breadcrumb-item breadcrumb-active">{{ __('Tambah Data Dosen') }}</li>
            </ol>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg px-4">
                <div class="p-6">
                    <h1 class="text-3xl font-extrabold mb-4">Tambah Data Dosen</h1>
                    <form action="{{ route('lecturer.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="photo">{{ __('Foto') }}</x-input-label>
                                <input id="photo" class="block mt-1 w-full border-2" type="file" name="photo"
                                    accept="image/*" required>
                                <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="name">{{ __('Nama') }}</x-input-label>
                                <x-text-input id="name" class="block mt-1 w-full rounded-xl" type="text"
                                    name="name" :value="old('name')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nip">{{ __('NIP') }}</x-input-label>
                                <x-text-input id="nip" class="block mt-1 w-full rounded-xl" type="text"
                                    name="nip" :value="old('nip')" required autofocus autocomplete="nip" />
                                <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="email">{{ __('Email') }}</x-input-label>
                                <x-text-input id="email" class="block mt-1 w-full rounded-xl" type="email"
                                    name="email" :value="old('email')" required autofocus autocomplete="email" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
                            <div>
                                <x-input-label for="department">{{ __('Jurusan') }}</x-input-label>
                                <x-text-input id="department" class="block mt-1 w-full rounded-xl" type="text"
                                    name="department" :value="old('department')" required autofocus autocomplete="department" />
                                <x-input-error :messages="$errors->get('department')" class="mt-2" />
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
