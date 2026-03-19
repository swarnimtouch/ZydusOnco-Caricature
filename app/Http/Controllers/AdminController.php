<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\EmployeeImport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    //
    public function importEmployeesForm(Request $request)
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
