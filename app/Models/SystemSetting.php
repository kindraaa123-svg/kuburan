<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = 'system';

    protected $primaryKey = 'systemid';

    public $timestamps = false;

    protected $fillable = [
        'systemname',
        'systemlogo',
        'systemcontact',
        'systemmanager',
        'systemaddress',
    ];
}
