<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\MyResetPassword;
use App\Notifications\MyEmailConfirmation;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verification_token'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin() {
        return $this->role === 'admin';
    }

    public function isClient() {
        return $this->role === NULL || $this->role === '';
    }

    /* --- */

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyResetPassword($token, $this->name));
    }

    /**
     * Execute the job.
     *
     */
    public function sendEmailConfirmationNotification($token)
    {
        $this->notify(new MyEmailConfirmation($token, $this->name));
    }
}
