<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;

class EmployeeImport implements ToModel
{
    public function model(array $row)
    {
       

        return new Employee([
            'employee_code' => $row[0],
            'name'          => $row[1],
            'city'          => $row[2],
            'address'       => $row[3],
            'phone'         => $row[4],
            'email'         => $row[5],
        ]);
    }
}
