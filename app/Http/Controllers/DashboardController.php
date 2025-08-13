<?php

namespace App\Http\Controllers;

use App\Models\ActivitySchedule;
use App\Models\Device;
use App\Models\FilteredFixStation;
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
        $upcomingActivities = ActivitySchedule::forCurrentUser()->get()
            ->filter(function ($activity) use ($now) {
                $start = Carbon::parse($activity->date . ' ' . $activity->start_time);
                return $start >= $now;
            })->sortBy(function ($activity) {
                return Carbon::parse($activity->date . ' ' . $activity->start_time);
            })->values();

        $lecturers = Lecturer::count();
        $students = Student::count();
        $devices = Device::count();
        $activitySchedules = ActivitySchedule::forCurrentUser()->count();

        // Data Grafik Telemetri 24 Jam Terakhir
        $startTime = now()->subHours(24);
        $rawData = FixStation::where('created_at', '>=', $startTime)->orderBy('created_at')->get();
        $filteredData = FilteredFixStation::where('created_at', '>=', $startTime)->orderBy('created_at')->get();

        $n = $rawData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Nitrogen,
        ]);

        $nFiltered = $filteredData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Nitrogen,
        ]);

        $p = $rawData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Phosporus,
        ]);

        $pFiltered = $filteredData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Phosporus,
        ]);

        $k = $rawData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Kalium,
        ]);

        $kFiltered = $filteredData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Kalium,
        ]);

        $ec = $rawData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Ec,
        ]);

        $ecFiltered = $filteredData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Ec,
        ]);

        $ph = $rawData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Ph,
        ]);

        $phFiltered = $filteredData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Ph,
        ]);

        $temp = $rawData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Temperature,
        ]);

        $tempFiltered = $filteredData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Temperature,
        ]);

        $humid = $rawData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Humidity,
        ]);

        $humidFiltered = $filteredData->map(fn($d) => [
            'x' => Carbon::parse($d->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'y' => $d->samples->Humidity,
        ]);

        return view('pages.dashboard.index', compact('upcomingActivities', 'lecturers', 'students', 'devices', 'activitySchedules', 'n', 'nFiltered', 'p', 'pFiltered', 'k', 'kFiltered', 'ec', 'ecFiltered', 'ph', 'phFiltered', 'temp', 'tempFiltered', 'humid', 'humidFiltered'));
    }
}
