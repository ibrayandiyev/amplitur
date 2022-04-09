<?php

namespace App\Models;

use App\Enums\UserType;
use App\Models\Traits\HasProcessStatusLabels;
use App\Models\Traits\Permissions;
use App\Notifications\Backend\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory,
        Notifiable,
        Permissions,
        HasProcessStatusLabels;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'full_name',
        'username',
        'password',
        'language',
        'status',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * [isMaster description]
     *
     * @return  bool    [return description]
     */
    public function isMaster(): bool
    {
        return $this->type == UserType::MASTER;
    }

    /**
     * [isManager description]
     *
     * @return  bool    [return description]
     */
    public function isManager(): bool
    {
        return $this->type == UserType::MANAGER;
    }

    /**
     * [isAdmin description]
     *
     * @return  bool    [return description]
     */
    public function isAdmin(): bool
    {
        return $this->type == UserType::ADMIN;
    }

    /**
     * [isProvider description]
     *
     * @return  bool    [return description]
     */
    public function isProvider(): bool
    {
        return false;
    }
}
