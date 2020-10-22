<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Container extends Model
{
    protected $casts = [
        'image_download' => 'array',
        'image_upload' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($container) {
            $container->creator = Auth::user()->first_name.' '.Auth::user()->last_name;
            $container->updater = Auth::user()->first_name.' '.Auth::user()->last_name;
        });
        static::updating(function ($container) {
            $container->updater = Auth::user()->first_name.' '.Auth::user()->last_name;
        });
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('name');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw('lower(name) like lower(?)', ["%{$search}%"]);
            });
        });
    }

    public function scopeSearch($query, $search)
    {
        $query->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw('lower(name) like lower(?)', ["%{$search}%"]);
            });
        });
    }
}
