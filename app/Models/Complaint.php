<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['type_name'];

    function responser()
    {
        return $this->belongsTo(User::class,'responser_id');
    }

    public function type()
    {
        return $this->belongsTo(TypeOfComplaint::class,'type');
    }

    public function getTypeNameAttribute()
    {
        return $this->type?$this->type()->first()->name:'';
    }
}
