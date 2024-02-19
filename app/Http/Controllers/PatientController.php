<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Events\NewPatientEvent;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{

    public function index()
    {
        $today = Carbon::today()->toDateString();
        $userRole = Auth::user()->role;

        if ($userRole === 'Admin Monitoring All') {
            $title = 'Data Pasien untuk Admin Monitoring All';
            $patient1 = Patient::where('table_number', 1)->whereDate('created_at', $today)->get();
            $patient2 = Patient::where('table_number', 2)->whereDate('created_at', $today)->get();
            $patient3 = Patient::where('table_number', 3)->whereDate('created_at', $today)->get();
            $patient4 = Patient::where('table_number', 4)->whereDate('created_at', $today)->get();
            return view('patient.index', compact('title', 'patient1', 'patient2', 'patient3', 'patient4'));
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
            'Admin Table 4' => 4,
        ];

        return $tableNumbers[$role] ?? null;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'address' => 'required|string|max:255',
            'gender' => 'required|string|in:Laki-laki,Perempuan',
            'systolic_blood_pressure' => 'nullable|numeric',
            'diastolic_blood_pressure' => 'nullable|numeric',
            'blood_glucose_type' => 'string|in:GDP,GDS',
            'blood_glucose' => 'nullable|numeric',
            'uric_acid' => 'nullable|numeric',
            'cholesterol' => 'nullable|numeric',
        ]);

        $role = Auth::user()->role;

        $data['table_number'] = $this->getTableNumberFromRole($role);

        $patient = Patient::create($data);

        $message = 'Pasien baru telah ditambahkan di meja ' . $data['table_number'];
        $dataSent = [
            'id' => $patient->id,
            'name' => $patient->name,
            'age' => $patient->age,
            'table_number' => $patient->table_number,
            'is_printed' => false
        ];

        event(new NewPatientEvent($message, $dataSent));

        return back()->with('success', "Data pasien berhasil ditambahkan");
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'address' => 'required|string|max:255',
            'gender' => 'required|string|in:Laki-laki,Perempuan',
            'systolic_blood_pressure' => 'nullable|numeric',
            'diastolic_blood_pressure' => 'nullable|numeric',
            'blood_glucose_type' => 'string|in:GDP,GDS',
            'blood_glucose' => 'nullable|numeric',
            'uric_acid' => 'nullable|numeric',
            'cholesterol' => 'nullable|numeric',
        ]);

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
        $patient = Patient::findOrFail($id);
        $patient->update(['is_printed' => true]);

        $this->normalizeIntegerProperties($patient);

        $patient->blood_pressure_level = $this->getBloodPressureLevel($patient->systolic_blood_pressure);
        $patient->blood_glucose_level = $this->getBloodGlucoseLevel($patient->blood_glucose, $patient->blood_glucose_type);
        $patient->uric_acid_level = $this->getUricAcidLevel($patient->uric_acid, $patient->gender);
        $patient->cholesterol_level = $this->getCholesterolLevel($patient->cholesterol);

        return view('patient.print')->with('patient', $patient);
    }

    public function getPatient($id)
    {
        $patient = Patient::find($id);

        $this->normalizeIntegerProperties($patient);

        return response()->json($patient);
    }

    private function normalizeIntegerProperties($patient)
    {
        $properties = ['systolic_blood_pressure', 'diastolic_blood_pressure', 'blood_glucose', 'uric_acid', 'cholesterol'];

        foreach ($properties as $property) {
            if ($patient->$property) {
                $patient->$property = ($patient->$property == intval($patient->$property)) ? intval($patient->$property) : floatval($patient->$property);
            }
        }
    }

    private function getBloodPressureLevel($systolicPressure)
    {
        return $systolicPressure > 120 ? 'Tinggi' : ($systolicPressure > 90 && $systolicPressure <= 120 ? 'Normal' : 'Rendah');
    }

    private function getBloodGlucoseLevel($bloodGlucose, $type)
    {
        $threshold = ($type == 'GDP') ? 126 : 200;
        return $bloodGlucose >= $threshold ? 'Tinggi' : ($bloodGlucose > 60 && $bloodGlucose < $threshold ? 'Normal' : 'Rendah');
    }

    private function getUricAcidLevel($uricAcid, $gender)
    {
        $thresholdHigh = ($gender == 'Perempuan') ? 6 : 7.2;
        $thresholdNormal = ($gender == 'Perempuan') ? 1.9 : 2.5;

        return $uricAcid > $thresholdHigh ? 'Tinggi' : ($uricAcid > $thresholdNormal && $uricAcid <= $thresholdHigh ? 'Normal' : 'Rendah');
    }

    private function getCholesterolLevel($cholesterol)
    {
        return $cholesterol >= 200 ? 'Tinggi' : 'Normal';
    }
}
