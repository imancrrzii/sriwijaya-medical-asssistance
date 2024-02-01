<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Data Pasien';
        $role = Auth::user()->role;

        $tableNumber = $this->getTableNumberFromRole($role);

        $patients = Patient::where('table_number', $tableNumber)->get();

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
    public function getPatient($id)
    {
        $patient = Patient::find($id);
        return response()->json($patient);
    }
    
}
