<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // HasFactory enables Product::factory() in tests
    use HasFactory;

    // Allow these fields to be mass-assigned (used in create/update)
    protected $fillable = ['name', 'qty', 'price', 'description', 'image'];

    /**
     * Get the accessible public URL for the product image.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
