<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'bio',
        'cv_path',
        'is_approved',
        'approved_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Kiểm tra người dùng có phải admin không
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Kiểm tra người dùng có phải nhà tuyển dụng không
     */
    public function isEmployer()
    {
        return $this->role === 'employer';
    }

    /**
     * Kiểm tra người dùng có phải ứng viên không
     */
    public function isCandidate()
    {
        return $this->role === 'candidate';
    }

    /**
     * Kiểm tra tài khoản đã được duyệt chưa
     */
    public function isApproved()
    {
        return $this->is_approved;
    }

    /**
     * Quan hệ một-một với Company (cho employer)
     */
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    /**
     * Quan hệ một-nhiều với Application (cho candidate)
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Quan hệ nhiều-nhiều với Job qua saved_jobs (cho candidate)
     */
    public function savedJobs()
    {
        return $this->belongsToMany(Job::class, 'saved_jobs')->withTimestamps();
    }

    /**
     * Quan hệ nhiều-nhiều với Skill (cho candidate)
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }
}
