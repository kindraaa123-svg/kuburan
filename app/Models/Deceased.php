<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deceased extends Model
{
    protected $table = 'deceased';

    protected $primaryKey = 'deceasedid';

    public $timestamps = true;

    protected $fillable = [
        'plotid',
        'full_name',
        'gender',
        'birth_date',
        'death_date',
        'burial_date',
        'religion',
        'identity_number',
        'photo_url',
    ];
}
