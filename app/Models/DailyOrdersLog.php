<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyOrdersLog extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cart_items()
    {
        return $this->hasMany(CartItem::class,'product_id','product_id');
    }

    public function scopeCollectOrdersPerDay($q,$date)
    {
        $q->join('cart_items','cart_items.product_id','daily_orders_logs.product_id')
        ->join('orders','orders.id','cart_items.order_id')
        ->join('suppliers','suppliers.id','cart_items.supplier_id')
        ->join('products','products.id','daily_orders_logs.product_id')
        ->select([
            'products.product_code','products.id','products.nameAr','products.nameEn','products.image',
            'cart_items.*','orders.order_status',
            'daily_orders_logs.id as log_id','daily_orders_logs.quantity as total_quantity','daily_orders_logs.date','daily_orders_logs.deficit',
            'suppliers.id','suppliers.name as supplier_name'
        ])
        ->whereIn('order_status',['Pending','Processing','Shipping','Completed'])
        ->whereDate('date',$date)
        ->latest('daily_orders_logs.created_at');
    }

}
