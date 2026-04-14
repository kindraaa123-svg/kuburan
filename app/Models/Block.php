<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Block extends Model
{
    protected $table = 'blocks';

    protected $primaryKey = 'blockid';

    public $timestamps = true;

    protected $fillable = [
        'block_name',
        'description',
        'map_color',
        'max_plots',
        'map_x',
        'map_y',
    ];

    public function gravePlots(): HasMany
    {
        return $this->hasMany(GravePlot::class, 'block_id', 'blockid');
    }
}
