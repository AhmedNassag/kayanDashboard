<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleRecord extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function sale(){
        return $this->belongsTo(Sale::class,'sale_id');
    }

    public function storeProducts(){
        return $this->hasMany(ProductStore::class,'sale_record_id');
    }
}
