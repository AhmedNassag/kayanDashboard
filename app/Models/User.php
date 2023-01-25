<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use  HasFactory, Notifiable,HasRoles;

    protected $appends = ['text'];
    public function getTextAttribute()
    {
        return $this->name;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id','image'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role_name' => 'array'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }

    // set hash password

    public function setPasswordAttribute($value){
        $this->attributes['password'] = bcrypt($value);
    }

    // start Relation

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function bank()
    {
        return $this->hasOne(Bank::class,'user_id');
    }

    public function schedule()
    {
        return $this->hasOne(AdvertiseSchedule::class, 'user_id');
    }

    public function media()
    {
        return $this->morphOne(Media::class,'mediable');
    }



    //
    public function getImageAttribute()
    {
        return $this->media ? $this->media->file_name:null;
    }

    public function representative()
    {
        return $this->hasOne(Representative::class);
    }

    public function banks()
    {
        return $this->hasOne(Bank::class, 'user_id');
    }

    public function examinationRecords()
    {
        return $this->hasMany(ExaminationRecord::class, 'user_id');
    }

    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class, 'user_id');
    }

    public function clientPurchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class, 'client_id');
    }

    public function pricingHistories()
    {
        return $this->hasMany(PricingHistory::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function upcomingPayment()
    {
        return $this->hasMany(UpcomingPayment::class, 'user_id');
    }

    public function suggestionUser()
    {
        return $this->hasMany(SuggestionUser::class);
    }

    public function clientAccounts()
    {
        return $this->hasMany(ClientAccount::class, 'user_id', 'id');
    }

    public function purchases()
    {

        return $this->hasMany(Purchase::class);
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.Models.User.' . $this->id;
    }

    public function addSupplierExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierExpense::class, 'user_id');
    }

    public function addClientExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientExpense::class, 'user_id');
    }

    public function clientExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientExpense::class, 'client_id');
    }

    public function supplierIncome(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierIncome::class);
    }

    public function addClientIncome(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientExpense::class, 'user_id');
    }

    public function clientIncome(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientExpense::class, 'client_id');
    }

    public function clientOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DirectOrders::class, 'user_id');
    }

    public function representativeOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DirectOrders::class, 'representative_id');
    }


    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function cart_items()
    {
        return $this->hasManyThrough(CartItem::class,Order::class);
    }



}
