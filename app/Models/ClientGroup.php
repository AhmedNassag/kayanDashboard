<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientGroup extends Model
{
    protected $guarded = ["clients_ids"];
    public function clients()
    {
        return $this->belongsToMany(Client::class);
    }

}
