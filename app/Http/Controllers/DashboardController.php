<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $title = "Dashboard";
        $today = Carbon::today()->toDateString();
        $dataTable1s = Patient::where('table_number', 1)->whereDate('created_at', $today)->count();
        $dataTable2s = Patient::where('table_number', 2)->whereDate('created_at', $today)->count();
        $dataTable3s = Patient::where('table_number', 3)->whereDate('created_at', $today)->count();
        $dataTable4s = Patient::where('table_number', 4)->whereDate('created_at', $today)->count();
        return view('dashboard.index', compact('title', 'dataTable1s', 'dataTable2s', 'dataTable3s', 'dataTable4s'));
    }

}
