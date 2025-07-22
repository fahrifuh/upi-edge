<?php

namespace App\Http\Controllers;

use App\Models\ActivitySchedule;
use App\Models\Device;
use App\Models\FixStation;
use App\Models\Lecturer;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Jadwal Kegiatan Mendatang
        $upcomingActivities = ActivitySchedule::get()
            ->filter(function ($activity) use ($now) {
                $start = Carbon::parse($activity->date . ' ' . $activity->start_time);
                return $start >= $now;
            })->sortBy(function ($activity) {
                return Carbon::parse($activity->date . ' ' . $activity->start_time);
            })->values();

        $lecturers = Lecturer::count();
        $students = Student::count();
        $devices = Device::count();
        $activitySchedules = ActivitySchedule::count();

        // Data Grafik Telemetri 24 Jam Terakhir
        $startTime = now()->subHours(24);
        $telemetryData = FixStation::where('created_at', '>=', $startTime)->orderBy('created_at')->get();

        $n = $telemetryData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Nitrogen,
        ]);

        $p = $telemetryData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Phosporus,
        ]);

        $k = $telemetryData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Kalium,
        ]);

        $ec = $telemetryData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Ec,
        ]);

        $ph = $telemetryData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Ph,
        ]);

        return view('pages.dashboard.index', compact('upcomingActivities', 'lecturers', 'students', 'devices', 'activitySchedules', 'n', 'p', 'k', 'ec', 'ph'));
    }
}
