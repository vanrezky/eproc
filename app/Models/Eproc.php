<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Eproc extends Model
{
    protected $fillable = ['nama_perusahaan_rekanan', 'link_website', 'keterangan', 'user_id', 'username', 'password', 'updated_user_id'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function updatedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
