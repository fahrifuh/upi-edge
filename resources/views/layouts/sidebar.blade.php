<div>
    <div :class="{ 'block': sideopen, 'hidden': !sideopen }"
        class="flex flex-col bg-white w-64 lg:fixed lg:top-0 lg:bottom-0 lg:left-0 lg:ml-0 lg:mr-0 max-md:hidden overflow-y-scroll styled-scrollbars"
        id="sidebar">
        <div id="app-brand" class="w-full h-16 mt-3 px-8">
            <a href="#" class="flex items-center" id="app-logo">
                <img src="{{ asset('images/logo-upi-horizontal.png') }}" alt="" srcset=""
                    class="object-cover">
            </a>
        </div>
        <div class="flex-grow">
            <div class="w-full mt-2 px-8">
                <h5 class="text-2xl font-bold" id="time-current"></h5>
                <h6 class="text-sm font-medium" id="date-current"></h6>
            </div>
            <ul id="menu-inner"
                class="flex flex-col flex-auto items-start justify-start m-0 p-0 pt-6 relative overflow-hidden touch-auto pb-6">
                <li class="menu-item">
                    <a href="{{ route('dashboard') }}" class="menu-link">
                        <i @class([
                            'menu-icon',
                            'active-icon' => request()->routeIs('dashboard'),
                            'fa-solid',
                            'fa-house',
                        ])></i>
                        <div class="text-slate-500">Dashboard</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('lecturer.index') }}" class="menu-link">
                        <i @class([
                            'menu-icon',
                            'active-icon' => request()->routeIs('lecturer.*'),
                            'fa-solid',
                            'fa-chalkboard-user',
                        ])></i>
                        <div class="text-slate-500">Data Dosen</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('profile.edit') }}" class="menu-link">
                        <i @class([
                            'menu-icon',
                            'active-icon' => request()->routeIs('profile.*'),
                            'fa-solid',
                            'fa-id-badge',
                        ])></i>
                        <div class="text-slate-500">Pengaturan Akun</div>
                    </a>
                </li>
                {{-- <li class="menu-item">
                    <a href="#" class="menu-link">
                        <i @class([
                            'menu-icon',
                            'active-icon' => request()->routeIs('bug-report.*'),
                            'fa-solid',
                            'fa-triangle-exclamation',
                        ])></i>
                        <div class="text-slate-500">Bug Report</div>
                    </a>
                </li> --}}
                {{-- <li class="menu-item">
                    <a href="#" class="menu-link">
                        <i @class([
                            'menu-icon',
                            'active-icon' => request()->routeIs('activity-log.*'),
                            'fa-solid',
                            'fa-clock-rotate-left',
                        ])></i>
                        <div class="text-slate-500">Log Aktivitas</div>
                    </a>
                </li> --}}
            </ul>
        </div>
        <div id="menu-footer" class="mb-3 text-center font-normal text-sm text-slate-500"></div>
    </div>
</div>
