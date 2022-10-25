<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends=['text'];

    public function getTextAttribute()
    {
        return $this->user->name;
    }

    public function user()
     {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class,'job_id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }


    // CRM relations
    public function sellerCategories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(SellerCategory::class,'employee_categories','employee_id','seller_category_id','id','id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class,'employee_id');
    }

    public function comments()
    {
        return $this->hasMany(LeadComment::class);
    }

    public function targetAchieved()
    {
        return $this->hasMany(TargetAchieved::class);
    }

}
