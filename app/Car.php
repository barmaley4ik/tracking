<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Car extends Model
{
    protected $casts = [
        'image_auction' => 'array',
        'image_taken_auction' => 'array',
        'image_in_stock' => 'array',
        'image_delivered' => 'array',
        'image_left_to_client' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($car) {
            $car->creator = Auth::user()->first_name.' '.Auth::user()->last_name;
            $car->updater = Auth::user()->first_name.' '.Auth::user()->last_name;
        });
        static::updating(function ($car) {
            $car->updater = Auth::user()->first_name.' '.Auth::user()->last_name;
        });
    }

    public function scopeOrderByVin($query)
    {
        $query->orderBy('Vin');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw('lower(name) like lower(?)', ["%{$search}%"]);
            })
            ->orWhere(function ($query) use ($search) {
                $query->whereRaw('lower(vin) like lower(?)', ["%{$search}%"]);
            });
        });
    }
    
    public function scopeSearch($query, $search)
    {
        $query->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw('lower(vin) like lower(?)', ["%{$search}%"]);
            });
        });
    }

    public function scopeSearchFull($query, $search)
    {
        $query->where('vin', $search);
    }

}
