<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PatientController extends Controller
{
    // public function index($tableNumber = null)
    // {
    //     $today = Carbon::today()->toDateString();
    //     $userRole = Auth::user()->role;

    //     if (Gate::allows('admin-monitoring-all')) {
    //         $title = 'Data Pasien untuk Admin Monitoring All';
    //         $patients = Patient::whereDate('created_at', $today)->get();
    //     } else {
    //         if ($tableNumber === null) {
    //             abort(403, 'Forbidden');
    //         }

    //         $title = 'Data Pasien Meja ' . $tableNumber;
    //         $patients = Patient::where('table_number', $tableNumber)->whereDate('created_at', $today)->get();
    //     }

    //     return view('patient.index', compact('title', 'patients'));
    // }

    public function index()
    {
        $today = Carbon::today()->toDateString();
        $userRole = Auth::user()->role;

        if ($userRole === 'Admin Monitoring All') {
            $title = 'Data Pasien untuk Admin Monitoring All';
            $patient1 = Patient::where('table_number', 1)->whereDate('created_at', $today)->get();
            $patient2 = Patient::where('table_number', 2)->whereDate('created_at', $today)->get();
            $patient3 = Patient::where('table_number', 3)->whereDate('created_at', $today)->get();
            return view('patient.index', compact('title', 'patient1', 'patient2', 'patient3'));
        } else {
            $tableNumber = $this->getTableNumberFromRole($userRole);
            $title = 'Data Pasien Meja ' . $tableNumber;
            $patients = Patient::where('table_number', $tableNumber)->whereDate('created_at', $today)->get();
            return view('patient.index', compact('title', 'patients'));
        }
    }

    protected function getTableNumberFromRole($role)
    {
        $tableNumbers = [
            'Admin Table 1' => 1,
            'Admin Table 2' => 2,
            'Admin Table 3' => 3,
            // Tambahkan sesuai kebutuhan
        ];

        return $tableNumbers[$role] ?? null;
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

    // protected function getTableNumberFromRole($role)
    // {
    //     $tableNumbers = [
    //         'Admin Table 1' => 1,
    //         'Admin Table 2' => 2,
    //         'Admin Table 3' => 3,
    //         'Admin Table 4' => 4,
    //     ];

    //     return $tableNumbers[$role] ?? null;
    // }

    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patient.index', compact('patients'));
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

        return redirect()->route('patient.index', ['tableNumber' => $data['table_number']])->with('success', "Data pasien berhasil diperbarui");

    }

    public function delete($id)
    {
        $patient = Patient::findorFail($id);
        $patient->delete();

        return back()->with('success', "Data berhasil dihapus");
    }

    public function printPatient($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->is_printed = 'true';
        $patient->save();
        return response()->json(['success' => true]);

    }
    public function showPrintView($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patient.print', compact('patient'));
    }
    public function getPatient($id)
    {
        $patient = Patient::find($id);
        return response()->json($patient);
    }
}
