<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'user_id', 'name', 'logo', 'website', 'size', 
        'address', 'description', 'map_embed'
    ];

    /**
     * Quan hệ nhiều-một với User (Nhà tuyển dụng)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ một-nhiều với Job
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
