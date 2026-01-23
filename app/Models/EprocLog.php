<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EprocLog extends Model
{
    protected $fillable = ['eproc_id', 'user_id', 'action', 'old_data', 'new_data'];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    /**
     * Get the eproc that owns the log.
     */
    public function eproc(): BelongsTo
    {
        return $this->belongsTo(Eproc::class);
    }

    /**
     * Get the user that performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
