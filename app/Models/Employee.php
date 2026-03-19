<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $fillable=['name','employee_id','city','address','phone','email'];
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
