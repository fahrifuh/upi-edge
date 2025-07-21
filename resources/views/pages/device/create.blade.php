<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('leaflet/leaflet.css') }}">
        <link rel="stylesheet" href="{{ asset('css/extend.css') }}">
        <style>
            #map {
                height: 80vh;
                z-index: 50;
            }
        </style>
    @endpush
    <x-slot name="header">
        <h2 class="leading-tight">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('device.index') }}">Data Perangkat</a>
                </li>
                <li class="breadcrumb-item breadcrumb-active">{{ __('Tambah Perangkat') }}</li>
            </ol>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="sm:max-w-7x xl:max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg px-4">
                <div class="p-6">
                    <h1 class="text-3xl font-extrabold mb-4">Tambah Data Perangkat</h1>
                    <form action="{{ route('device.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="w-full">
                                <x-input-label for="photo">{{ __('Foto') }}</x-input-label>
                                <input id="photo" class="block mt-1 w-full border-2" type="file" name="photo"
                                    accept="image/*" required>
                                <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                            </div>
                            <div class="w-full">
                                <x-input-label for="series">{{ __('Series') }}</x-input-label>
                                <x-text-input id="series" class="block mt-1 w-full rounded-xl" type="text"
                                    name="series" :value="old('series')" required autofocus autocomplete="series" />
                                <x-input-error :messages="$errors->get('series')" class="mt-2" />
                            </div>
                            <div class="w-full">
                                <x-input-label for="name">{{ __('Nama') }}</x-input-label>
                                <x-text-input id="name" class="block mt-1 w-full rounded-xl" type="text"
                                    name="name" :value="old('name')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="w-full">
                                <x-input-label for="installation_date">{{ __('Tanggal Pemasangan') }}</x-input-label>
                                <x-text-input id="installation_date" class="block mt-1 w-full rounded-xl" type="date"
                                    name="installation_date" :value="old('installation_date')" required autofocus
                                    autocomplete="installation_date" />
                                <x-input-error :messages="$errors->get('installation_date')" class="mt-2" />
                            </div>
                            <div class="w-full">
                                <x-input-label for="koneksi">{{ __('Tipe Koneksi') }}</x-input-label>
                                <select id="koneksi" class="block mt-1 w-full rounded-xl" name="koneksi">
                                    <option value="" data-type="">Pilih Tipe Koneksi</option>
                                    <option value="wifi" data-type="wifi">WiFi</option>
                                    <option value="lora" data-type="lora">LoRa</option>
                                    <option value="gsm" data-type="gsm">GSM</option>
                                </select>
                            </div>
                            <div class="w-full">
                                <x-input-label for="note">{{ __('Note') }}</x-input-label>
                                <textarea id="note" class="block mt-1 w-full rounded-xl" rows="3" name="note">{{ old('note') }}</textarea>
                                <x-input-error :messages="$errors->get('note')" class="mt-2" />
                            </div>
                        </div>
                        <p class="font-semibold mt-2" id="wifi-props-label">WiFi Properties</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="wifi-props-group">
                            <div class="w-full">
                                <x-input-label for="wifi_ssid">{{ __('SSID') }}</x-input-label>
                                <x-text-input id="wifi_ssid" class="block mt-1 w-full rounded-xl" type="text"
                                    name="wifi_ssid" :value="old('wifi_ssid')" required autofocus autocomplete="wifi_ssid" />
                                <x-input-error :messages="$errors->get('wifi_ssid')" class="mt-2" />
                            </div>
                            <div class="w-full">
                                <x-input-label for="wifi_password">{{ __('Pass') }}</x-input-label>
                                <x-text-input id="wifi_password" class="block mt-1 w-full rounded-xl" type="text"
                                    name="wifi_password" :value="old('wifi_password')" required autofocus
                                    autocomplete="wifi_password" />
                                <x-input-error :messages="$errors->get('wifi_password')" class="mt-2" />
                            </div>
                        </div>
                        <p class="font-semibold mt-2" id="gsm-props-label">GSM Properties</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="gsm-props-group">
                            <div class="w-full">
                                <x-input-label for="gsm_provider">{{ __('Provider') }}</x-input-label>
                                <x-text-input id="gsm_provider" class="block mt-1 w-full rounded-xl" type="text"
                                    name="gsm_provider" :value="old('gsm_provider')" required autofocus
                                    autocomplete="gsm_provider" />
                                <x-input-error :messages="$errors->get('gsm_provider')" class="mt-2" />
                            </div>
                            <div class="w-full">
                                <x-input-label for="gsm_nomor_kartu">{{ __('Nomor Kartu') }}</x-input-label>
                                <x-text-input id="gsm_nomor_kartu" class="block mt-1 w-full rounded-xl" type="text"
                                    name="gsm_nomor_kartu" :value="old('gsm_nomor_kartu')" required autofocus
                                    autocomplete="gsm_nomor_kartu" />
                                <x-input-error :messages="$errors->get('gsm_nomor_kartu')" class="mt-2" />
                            </div>
                        </div>
                        <p class="font-semibold mt-2" id="lora-props-label">LoRa Properties</p>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4" id="lora-props-group">
                            <div class="w-full">
                                <x-input-label for="lora_id">{{ __('ID') }}</x-input-label>
                                <x-text-input id="lora_id" class="block mt-1 w-full rounded-xl" type="text"
                                    name="lora_id" :value="old('lora_id')" required autofocus autocomplete="lora_id" />
                                <x-input-error :messages="$errors->get('lora_id')" class="mt-2" />
                            </div>
                            <div class="w-full">
                                <x-input-label for="lora_channel">{{ __('Channel') }}</x-input-label>
                                <x-text-input id="lora_channel" class="block mt-1 w-full rounded-xl" type="text"
                                    name="lora_channel" :value="old('lora_channel')" required autofocus
                                    autocomplete="lora_channel" />
                                <x-input-error :messages="$errors->get('lora_channel')" class="mt-2" />
                            </div>
                            <div class="w-full">
                                <x-input-label for="lora_net_id">{{ __('NET ID') }}</x-input-label>
                                <x-text-input id="lora_net_id" class="block mt-1 w-full rounded-xl" type="text"
                                    name="lora_net_id" :value="old('lora_net_id')" required autofocus
                                    autocomplete="lora_net_id" />
                                <x-input-error :messages="$errors->get('lora_net_id')" class="mt-2" />
                            </div>
                            <div class="w-full">
                                <x-input-label for="lora_key">{{ __('Key') }}</x-input-label>
                                <x-text-input id="lora_key" class="block mt-1 w-full rounded-xl" type="text"
                                    name="lora_key" :value="old('lora_key')" required autofocus autocomplete="lora_key" />
                                <x-input-error :messages="$errors->get('lora_key')" class="mt-2" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div class="col-span-2 text-end">
                                <div class="w-full flex justify-end">
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
            const toggleGroups = (groups, show) => {
                groups.forEach(el => {
                    el.classList.toggle('hidden', !show);
                });
            }

            const toggleFields = (fields, show) => {
                fields.forEach(el => {
                    el.toggleAttribute('disabled', !show);
                    el.toggleAttribute('required', show);
                });
            }

            const checkKoneksi = (koneksi) => {
                // WiFi Properties
                const wifiPropsGroup = document.getElementById('wifi-props-group')
                const wifiPropsLabel = document.getElementById('wifi-props-label')
                const wifiSsidElement = document.getElementById('wifi_ssid')
                const wifiPasswordElement = document.getElementById('wifi_password')

                // LoRa Properties
                const loraPropsGroup = document.getElementById('lora-props-group')
                const loraPropsLabel = document.getElementById('lora-props-label')
                const loraIdElement = document.getElementById('lora_id')
                const loraChannelElement = document.getElementById('lora_channel')
                const loraNetIdElement = document.getElementById('lora_net_id')
                const loraKeyElement = document.getElementById('lora_key')

                // GSM Properties
                const gsmPropsGroup = document.getElementById('gsm-props-group')
                const gsmPropsLabel = document.getElementById('gsm-props-label')
                const gsmProviderElement = document.getElementById('gsm_provider')
                const gsmNomorKartuElement = document.getElementById('gsm_nomor_kartu')

                toggleGroups([wifiPropsGroup, loraPropsGroup, gsmPropsGroup], false)
                toggleFields([wifiSsidElement, wifiPasswordElement, loraIdElement, loraChannelElement, loraNetIdElement,
                    loraKeyElement, gsmProviderElement, gsmNomorKartuElement
                ], false)
                loraPropsLabel.classList.add('hidden')
                gsmPropsLabel.classList.add('hidden')
                wifiPropsLabel.classList.add('hidden')

                if (koneksi == 'wifi') {
                    toggleGroups([wifiPropsGroup], true)
                    toggleFields([wifiSsidElement, wifiPasswordElement], true)
                    wifiPropsLabel.classList.remove('hidden')
                } else if (koneksi == 'gsm') {
                    toggleGroups([gsmPropsGroup], true)
                    toggleFields([gsmProviderElement, gsmNomorKartuElement], true)
                    gsmPropsLabel.classList.remove('hidden')
                } else if (koneksi == 'lora') {
                    toggleGroups([loraPropsGroup], true)
                    toggleFields([loraIdElement, loraChannelElement, loraNetIdElement, loraKeyElement], true)
                    loraPropsLabel.classList.remove('hidden')
                }
            }

            // function checkType(type) {
            //     const debitElement = document.getElementById('debit')
            //     const debitGroup = document.getElementById('debitGroup')
            //     const mapGroup = document.getElementById('mapGroup')
            //     const latElement = document.getElementById('latitude')
            //     const lngElement = document.getElementById('longitude')


            //     toggleGroups([debitGroup, mapGroup], false)
            //     toggleFields([debitElement, latElement, lngElement], false)

            //     if (type == 'head_unit') {
            //         toggleGroups([debitGroup, mapGroup], true)
            //         toggleFields([debitElement, latElement, lngElement], true)
            //     }
            // }

            document.addEventListener("DOMContentLoaded", () => {
                document.getElementById('koneksi').addEventListener('change', e => {
                    const selectElement = document.getElementById('koneksi')
                    checkKoneksi(selectElement.options[selectElement.selectedIndex].dataset.type);
                })

                // checkType("")
                checkKoneksi("")
            })
        </script>
    @endpush
</x-app-layout>
