<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfComplaint extends Model
{
    use HasFactory;

    protected $guarded = [ ];
    protected $appends = ['name'];
    public function getNameAttribute()
    {
        return request()->header('lang') == 'ar' ? $this->name_ar:$this->name_en;
    }
}
