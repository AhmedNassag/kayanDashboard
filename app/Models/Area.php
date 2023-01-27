<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $guarded = ["city_name"];

    protected $appends=['text'];

    public function getTextAttribute()
    {
        return $this->name;
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    //
    public function province()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function representatives(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Representative::class, 'representative_areas', 'area_id', 'representative_id', 'id', 'id');
    }

    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_areas', 'area_id', 'store_id', 'id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'area_id');
    }
}
