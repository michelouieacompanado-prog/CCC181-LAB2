<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Allow these fields to be mass-assigned (used in create/update)
    protected $fillable = ['name', 'qty', 'price', 'description'];
}
