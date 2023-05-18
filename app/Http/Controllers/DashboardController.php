<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use App\Models\WorkLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a dashboard.
     */
    public function index(Request $request)
    {
        $month = null;
        $year = null;
        $now = Carbon::now();
        if ($request->query->has('month')) {
            $month = $request->query->get('month');
        } else {
            $month = $now->month;
        }
        if ($request->query->has('year')) {
            $year = $request->query->get('year');
        } else {
            $year = $now->year;
        }
        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month)->endOfMonth();
        $statusTrueCount = WorkLog::where('status', true)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        $statusFalseCount = WorkLog::where('status', false)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        $developers = Developer::where('status', true)->whereHas('workLogs', function ($query) use ($startOfMonth, $endOfMonth) {
            return $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        })->get();

        return ['statusTrueCount' => $statusTrueCount, 'statusFalseCount' => $statusFalseCount, 'developers' => $developers];
    }
}
