<?php

namespace App\Http\Controllers;

use App\Events\SensorData;
use App\Models\ActivitySchedule;
use App\Models\FilteredFixStation;
use App\Models\FixStation;
use App\Models\SensorThreshold;
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

        return view('pages.rsc-data.monitoring.raw', compact('data', 'lastUpdated', 'uniqueDeviceIds'));
    }

    public function indexFilteredMonitoring(Request $request)
    {
        $query = FilteredFixStation::query();

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
        $lastUpdated = FilteredFixStation::latest()->first('created_at');

        // Get unique device IDs from the filtered data
        $uniqueDeviceIds = $data->pluck('device_id')->unique()->sort()->values();

        return view('pages.rsc-data.monitoring.filtered', compact('data', 'lastUpdated', 'uniqueDeviceIds'));
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

    public function getFilteredUniqueDeviceIds(Request $request)
    {
        $query = FilteredFixStation::query();

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

    public function indexPenjadwalan(Request $request)
    {
        $type = $request->query('type', 'raw');
        $schedules = ActivitySchedule::get();
        return view('pages.rsc-data.schedule.index', compact('schedules', 'type'));
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
                ->take(100)
                ->get();
        }

        return view('pages.rsc-data.schedule.raw', compact('data', 'status', 'start', 'end', 'schedule'));
    }

    public function showFilteredPenjadwalan($id)
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
            $data = FilteredFixStation::whereBetween('created_at', [$start, $end])
                ->orderBy('created_at', 'desc')
                ->take(100)
                ->get();
        }

        return view('pages.rsc-data.schedule.filtered', compact('data', 'status', 'start', 'end', 'schedule'));
    }

    public function handleSensorData(Request $request)
    {
        $request->validate([
            'device_id' => 'required',
            'soilrs485.Nitrogen' => 'nullable',
            'soilrs485.Phosporus' => 'nullable',
            'soilrs485.Kalium' => 'nullable',
            'soilrs485.Ec' => 'nullable',
            'soilrs485.Ph' => 'nullable',
            'soilrs485.Temperature' => 'nullable',
            'soilrs485.Humidity' => 'nullable',
        ]);

        $data = [
            'device_id' => $request->device_id,
            'samples' => $request->soilrs485,
        ];
        
        $fixStation = FixStation::create($data);
        
        $rawSamples = $fixStation->samples; // sudah object (stdClass)
        $filteredSamples = clone $rawSamples; // clone agar tidak mengubah aslinya
        
        foreach ($filteredSamples as $key => $value) {
            $threshold = SensorThreshold::where('parameter', $key)->first();

            if ($threshold) {
                $min = $threshold->min;
                $max = $threshold->max;
                $filteredValue = min(max($value, $min), $max);
            } else {
                $filteredValue = $value; // tidak ada threshold = biarkan
            }

            $filteredSamples->{$key} = $filteredValue; // set nilai baru
        }

        

        $filteredFixStation = FilteredFixStation::create([
            'device_id' => $request->device_id,
            'samples' => $filteredSamples,
        ]);

        event(new SensorData($fixStation, $filteredFixStation));

        return response()->json([
            'status' => true,
            'message' => 'Data RSC berhasil diterima dan disimpan!',
            'data' => $data
        ], 201);
    }
}
