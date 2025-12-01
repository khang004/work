<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Quan hệ nhiều-nhiều với Job
     */
    public function jobs()
    {
        return $this->belongsToMany(Job::class);
    }

    /**
     * Quan hệ nhiều-nhiều với User
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
