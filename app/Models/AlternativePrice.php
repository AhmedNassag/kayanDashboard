<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlternativePrice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'pharmacyPrice'  => 'double',
        'publicPrice'    => 'double',
        'clientDiscount' => 'double',
        'kayanDiscount'  => 'double',
        'kayanProfit'    => 'double',
        'alternative_id' => 'integer',
        'supplier_id'    => 'integer',
    ];

    //start raletions
    public function alternative()
    {
        return $this->belongsTo(Alternative::class, 'alternative_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
