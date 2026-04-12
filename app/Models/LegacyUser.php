<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegacyUser extends Model
{
    protected $table = 'user';

    protected $primaryKey = 'userid';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'full_name',
        'phone_number',
        'email',
        'password',
        'levelid',
        'reset_password_token',
        'reset_password_token_expired',
    ];

    protected $hidden = [
        'password',
        'reset_password_token',
    ];
}
