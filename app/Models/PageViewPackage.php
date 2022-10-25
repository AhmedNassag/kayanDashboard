<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageViewPackage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'page_view_packages';

}
