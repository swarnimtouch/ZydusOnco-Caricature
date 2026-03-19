<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function index()
    {
        return view('doctor.form');
    }

    public function getEmployee($code)
    {
        $employee = Employee::where('employee_code', $code)->first();

        if ($employee) {
            return response()->json([
                'status' => true,
                'name' => $employee->name,
                'id' => $employee->id
            ]);
        }

        return response()->json(['status' => false]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_code' => 'required',
            'employee_name' => 'required',
            'doctor_name' => 'required',
            'hospital_name' => 'required',
            'city' => 'required',
            'photo' => 'required|image'
        ]);

        $employee = Employee::firstOrCreate(
            ['employee_code' => $request->employee_code],
            ['name' => $request->employee_name]
        );

        $file = $request->file('photo');

        $cleanName = Str::slug($request->doctor_name);

        $fileName = 'ZydusOnco_Caricature/doctors/' . $cleanName . '_' . time() . '.' . $file->getClientOriginalExtension();

        Storage::disk('s3')->put($fileName, file_get_contents($file), 'public');

        $fileUrl = Storage::disk('s3')->url($fileName);

        Doctor::create([
            'employee_id' => $employee->id,
            'name' => $request->doctor_name,
            'hospital_name' => $request->hospital_name,
            'city' => $request->city,
            'photo' => $fileUrl
        ]);

        return back()->with('success', 'Doctor Saved Successfully ');
    }
}
