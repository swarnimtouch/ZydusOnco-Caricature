<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    //
    protected $fillable = [
        'employee_id',
        'name',
        'hospital_name',
        'city',
        'photo'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
