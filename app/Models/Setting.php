<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // KODE SAKTI PENJINAK EROR: Memberikan izin mass assignment gais
    protected $fillable = [
        'key',
        'value',
    ];
}