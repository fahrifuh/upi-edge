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

    public function indexMonitoring()
    {
        $data = FixStation::latest()->take(20)->get();
        $lastUpdated = FixStation::latest()->first('created_at');
        return view('pages.rsc-data.monitoring.index', compact('data', 'lastUpdated'));
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
