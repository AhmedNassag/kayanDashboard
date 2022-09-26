<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends=['text'];

    public function getTextAttribute()
    {
        return $this->name;
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
