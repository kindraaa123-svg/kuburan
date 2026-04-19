<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $table = 'facility';

    protected $primaryKey = 'facilityid';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'facility_name',
        'facility_key',
        'icon_emoji',
    ];
}
