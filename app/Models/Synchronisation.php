<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Synchronisation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'synchronisations';

    protected $fillable = [
        'start',
        'end',
        'status',
        'modified',
        'full'
    ];
}