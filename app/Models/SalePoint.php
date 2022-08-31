<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalePoint extends Model
{
    protected $guarded = ["clients_groups_ids"];
    public function clients_groups()
    {
        return $this->belongsToMany(ClientGroup::class, "sale_point_client_groups","sale_point_id","client_group_id");
    }
}
