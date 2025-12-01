<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Quan hệ một-nhiều với Job
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
