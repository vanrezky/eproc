<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eproc extends Model
{
    protected $fillable = ['nama_perusahaan_rekanan', 'link_website', 'keterangan', 'user_id', 'username', 'password'];
}
