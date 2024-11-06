<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, InteractsWithMedia, HasRoles, SoftDeletes, HasFactory;

    protected $appends = ['image'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'address', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'datetime',
        'joining_date' => 'datetime',
    ];

    public function getImageAttribute(): string
    {
        $imageUrl = $this->getFirstMediaUrl('user');

        return !empty($imageUrl) ? asset($imageUrl) : asset('img/avatar-1.png');
    }

    public function getCustomDateOfBirthAttribute(): string
    {
        return $this->date_of_birth ? $this->date_of_birth->format('d-m-Y') : '';
    }

    public function getCustomJoiningDateAttribute(): string
    {
        return $this->joining_date ? $this->joining_date->format('d-m-Y') : '';
    }

    public function getLabelDateOfBirthAttribute(): string
    {
        return $this->date_of_birth ? $this->date_of_birth->format('d M Y') : '';
    }

    public function getLabelJoiningDateAttribute(): string
    {
        return $this->joining_date ? $this->joining_date->format('d M Y') : '';
    }

    public function getRoleAttribute()
    {
        return $this->roles->first();
    }

    public function getRoleIdAttribute()
    {
        return optional($this->role)->id;
    }

    public function getRoleNameAttribute()
    {
        return optional($this->role)->name;
    }
}
