<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'status_new',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Handle the status column name issue
    public function getStatusAttribute()
    {
        return $this->attributes['status_new'] ?? 'active';
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status_new'] = $value;
    }

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('status_new', 'active');
    }

    public function toasks()
    {
        return $this->hasMany(Toask::class, 'categories_id');
    }
}
