<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Aplikasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="sm:max-w-7x xl:max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg px-4">
                <div class="p-6">
                    <h1 class="text-3xl font-extrabold mb-4">Pengaturan Aplikasi</h1>
                    <form action="{{ route('application-setting.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="image">{{ __('Foto') }}</x-input-label>
                                <input id="image" class="block mt-1 w-full border-2" type="file" name="image"
                                    accept="image/*">
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="name">{{ __('Nama Aplikasi') }}</x-input-label>
                                <x-text-input id="name" class="block mt-1 w-full rounded-xl" type="text"
                                    name="name" :value="old('name', $setting->name ?? '')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="version">{{ __('Versi Aplikasi') }}</x-input-label>
                                <x-text-input id="version" class="block mt-1 w-full rounded-xl" type="text"
                                    name="version" :value="old('version', $setting->version ?? '')" required autocomplete="version" />
                                <x-input-error :messages="$errors->get('version')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="copyright">{{ __('Copyright') }}</x-input-label>
                                <x-text-input id="copyright" class="block mt-1 w-full rounded-xl" type="text"
                                    name="copyright" :value="old('copyright', $setting->copyright ?? '')" required autocomplete="copyright" />
                                <x-input-error :messages="$errors->get('copyright')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="copyright_year">{{ __('Tahun Copyright') }}</x-input-label>
                                <x-text-input id="copyright_year" class="block mt-1 w-full rounded-xl" type="text"
                                    name="copyright_year" :value="old('copyright_year', $setting->copyright_year ?? '')" required autocomplete="copyright_year" />
                                <x-input-error :messages="$errors->get('copyright_year')" class="mt-2" />
                            </div>
                            <div class="col-span-2 text-end">
                                <div class="w-full flex justify-end mt-4">
                                    <x-primary-button>
                                        {{ __('Simpan') }}
                                    </x-primary-button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000,
                });
            @endif
        </script>
    @endpush
</x-app-layout>
