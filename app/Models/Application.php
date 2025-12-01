<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id', 'job_id', 'cover_letter', 'cv_path', 'status', 'note'
    ];

    /**
     * Quan hệ nhiều-một với User (Ứng viên)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ nhiều-một với Job
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
