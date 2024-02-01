<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $title = "Dashboard";
        $dataTable1s = Patient::where('table_number', 1)->count();

        $dataTable2s = Patient::where('table_number', 2)->count();

        $dataTable3s = Patient::where('table_number', 3)->count();

        $dataTable4s = Patient::where('table_number', 4)->count();
        return view('dashboard.index', compact('title', 'dataTable1s', 'dataTable2s', 'dataTable3s', 'dataTable4s'));
    }
    public function showTableData($tableNumber)
    {
        $isAdminMonitoringAll = Auth::user()->role === 'Admin Monitoring All';

        if (!$isAdminMonitoringAll) {
            $userTableNumber = $this->getTableNumberFromRole(Auth::user()->role);

            if ($userTableNumber != $tableNumber) {
                abort(403, 'Forbidden');
            }
        }

        $title = 'Data Pasien Meja ' . $tableNumber;

        $patients = Patient::where('table_number', $tableNumber)->get();

        return view('patient.index', compact('title', 'patients'));
    }

    protected function getTableNumberFromRole($role)
    {
        $tableNumbers = [
            'Admin Table 1' => 1,
            'Admin Table 2' => 2,
            'Admin Table 3' => 3,
            'Admin Table 4' => 4,
        ];

        return $tableNumbers[$role] ?? null;
    }

}
