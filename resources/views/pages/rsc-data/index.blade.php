<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Rapid Soil Checker (RSC)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('rsc-data.monitoring.index') }}" class="block">
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-row justify-between items-center">
                        <div>
                            <h5 class="text-md text-black font-bold">Monitoring</h5>
                        </div>
                        <div class="flex items-center">
                            <i class="fa-solid fa-gauge p-3 bg-primary text-white rounded-lg"></i>
                        </div>
                    </div>
                </a>
                <a href="{{ route('rsc-data.schedule.index') }}" class="block">
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-row justify-between items-center">
                        <div>
                            <h5 class="text-md text-black font-bold">Penjadwalan</h5>
                        </div>
                        <div class="flex items-center">
                            <i class="fa-solid fa-calendar p-3 bg-primary text-white rounded-lg"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
