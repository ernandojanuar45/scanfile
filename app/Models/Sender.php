<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sender extends Model
{
    protected $fillable = ['nama', 'alamat', 'jabatan', 'email', 'phone'];
}
