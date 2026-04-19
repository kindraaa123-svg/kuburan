<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityMapItem extends Model
{
    use HasFactory;

    protected $table = 'facility_map_items';

    protected $primaryKey = 'facility_map_itemid';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'facility_id',
        'map_x',
        'map_y',
        'is_fixed',
    ];
}
