<?php

namespace App\Http\Controllers;

use App\Exports\DoctorExport;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Imports\EmployeeImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.doctors.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function dashboard()
    {
        $totalDoctors  = Doctor::count();
        $recentDoctors = Doctor::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalDoctors', 'recentDoctors'));
    }

    public function index(Request $request)
    {
        $doctors = Doctor::with('employee')
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('employee', function ($q2) use ($request) {
                        $q2->where('employee_code', 'like', '%' . $request->search . '%');
                    });
            })
            ->paginate(10);

        return view('admin.doctors', compact('doctors'));
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor deleted successfully');
    }

    public function export(Request $request)
    {
        return Excel::download(
            new DoctorExport($request->search),
            'doctors.xlsx'
        );
    }

    public function importEmployeesForm()
    {
        return view('import-employees-form');
    }

    public function importEmployees(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new EmployeeImport, $request->file('file'));

        return back()->with('success', 'Employees Imported Successfully');
    }
}
