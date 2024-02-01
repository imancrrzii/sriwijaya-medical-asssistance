<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index($tableNumber)
    {
        $isAdminMonitoringAll = Auth::user()->role === 'Admin Monitoring All';

        if (!$isAdminMonitoringAll) {
            $userTableNumber = $this->getTableNumberFromRole(Auth::user()->role);

            if ($userTableNumber != $tableNumber) {
                abort(403, 'Forbidden');
            }
        }

        $title = 'Data Pasien Meja ' . $tableNumber;

        $today = Carbon::today()->toDateString();
        $patients = Patient::where('table_number', $tableNumber)->whereDate('created_at', $today)->get();

        return view('patient.index', compact('title', 'patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'address' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'blood_pressure' => 'required|string|max:255',
            'blood_glucose' => 'required|string|max:255',
            'uric_acid' => 'required|string|max:255',
            'cholesterol' => 'required|string|max:255',
        ]);

        $role = Auth::user()->role;

        $data['table_number'] = $this->getTableNumberFromRole($role);

        Patient::create($data);

        return back()->with('success', "Data pasien berhasil ditambahkan");
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

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'address' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'blood_pressure' => 'required|string|max:255',
            'blood_glucose' => 'required|string|max:255',
            'uric_acid' => 'required|string|max:255',
            'cholesterol' => 'required|string|max:255',
        ]);

        $role = Auth::user()->role;

        $data['table_number'] = $this->getTableNumberFromRole($role);

        $patient = Patient::findOrFail($id);
        $patient->update($data);

        return redirect()->route('patient.index')->with('success', "Data pasien berhasil diperbarui");
    }

    public function delete($id)
    {
        $patient = Patient::findorFail($id);
        $patient->delete();

        return back()->with('success', "Data berhasil dihapus");
    }

    public function printPatient($id)
    {
        $title = "print";
        $patient = Patient::findOrFail($id);

        return view('patient.print', compact('patient', 'title'));
    }
    public function getPatient($id)
    {
        $patient = Patient::find($id);
        return response()->json($patient);
    }
}
