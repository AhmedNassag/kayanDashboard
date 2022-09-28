<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlternativeDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    //start raletions
    //start raletions
    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
