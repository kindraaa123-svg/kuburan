<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GravePlot extends Model
{
    protected $table = 'grave_plots';

    protected $primaryKey = 'plotid';

    public $timestamps = true;

    protected $fillable = [
        'block_id',
        'plot_number',
        'row_number',
        'position_x',
        'position_y',
        'width',
        'height',
        'status',
        'notes',
    ];

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class, 'block_id', 'blockid');
    }
}
