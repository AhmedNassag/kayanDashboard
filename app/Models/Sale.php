<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['quantity', 'total_price', 'is_received'];

    public function getIsReceivedAttribute()
    {
        if ($this->saleRecord != null || $this->saleReturns != null)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function getQuantityAttribute()
    {
        return  $this->saleProducts()->sum('quantity') ;
    }

    public function getTotalPriceAttribute()
    {
        return ($this->price + $this->transfer_price) - ($this->discount_value + $this->other_discounts);
    }

    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class,'store_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }

    public function saleRecord()
    {
        return $this->hasOne(SaleRecord::class,'sale_id');
    }

    public function saleReturns()
    {
        return $this->hasOne(SaleReturn::class,'sale_id');
    }
}
