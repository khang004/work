<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'company_id', 'category_id', 'location_id', 'title', 'slug',
        'description', 'requirements', 'salary_min', 'salary_max',
        'deadline', 'positions', 'status', 'is_featured'
    ];

    protected $casts = [
        'deadline' => 'date',
        'is_featured' => 'boolean',
    ];

    /**
     * Quan hệ nhiều-một với Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Quan hệ nhiều-một với Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Quan hệ nhiều-một với Location
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Quan hệ nhiều-nhiều với Skill
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    /**
     * Quan hệ một-nhiều với Application
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Quan hệ nhiều-nhiều với User qua saved_jobs
     */
    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_jobs')->withTimestamps();
    }
}
