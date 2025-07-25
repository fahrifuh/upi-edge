<?php

namespace App\Http\Controllers;

use App\Events\SensorData;
use App\Models\ActivitySchedule;
use App\Models\FixStation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RSCDataController extends Controller
{
    public function index()
    {
        return view('pages.rsc-data.index');
    }

    public function indexMonitoring(Request $request)
    {
        $query = FixStation::query();

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter by device ID
        if ($request->filled('device_id')) {
            $query->where('device_id', $request->device_id);
        }

        $data = $query->latest()->take(100)->get();
        $lastUpdated = FixStation::latest()->first('created_at');

        // Get unique device IDs from the filtered data
        $uniqueDeviceIds = $data->pluck('device_id')->unique()->sort()->values();

        return view('pages.rsc-data.monitoring.index', compact('data', 'lastUpdated', 'uniqueDeviceIds'));
    }

    public function getUniqueDeviceIds(Request $request)
    {
        $query = FixStation::query();

        // Apply same filters as the main query
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $uniqueDeviceIds = $query->distinct()->pluck('device_id')->sort()->values();

        return response()->json($uniqueDeviceIds);
    }

    public function indexPenjadwalan()
    {
        $schedules = ActivitySchedule::get();
        return view('pages.rsc-data.schedule.index', compact('schedules'));
    }

    public function showPenjadwalan($id)
    {
        $schedule = ActivitySchedule::findOrFail($id);

        $start = Carbon::parse($schedule->date . ' ' . $schedule->start_time);
        $end = Carbon::parse($schedule->date . ' ' . $schedule->end_time);
        $now = now();

        $status = match (true) {
            $now < $start => 'belum',
            $now >= $start && $now <= $end => 'berlangsung',
            default => 'selesai'
        };

        $data = [];

        if ($status !== 'belum') {
            $data = FixStation::whereBetween('created_at', [$start, $end])
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get();
        }

        return view('pages.rsc-data.schedule.show', compact('data', 'status', 'start', 'end', 'schedule'));
    }

    public function handleSensorData(Request $request)
    {
        $request->validate([
            'device_id' => 'required',
            'samples.Nitrogen' => 'nullable',
            'samples.Phosporus' => 'nullable',
            'samples.Kalium' => 'nullable',
            'samples.Ec' => 'nullable',
            'samples.Ph' => 'nullable',
            'samples.Temperature' => 'nullable',
            'samples.Humidity' => 'nullable',
        ]);

        $data = [
            'device_id' => $request->device_id,
            'samples' => $request->samples,
        ];

        $fixStation = FixStation::create($data);

        event(new SensorData($fixStation));

        return response()->json([
            'status' => true,
            'message' => 'Data RSC berhasil diterima dan disimpan!',
            'data' => $data
        ], 201);
    }
}
