<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Defibrillator extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'defibrillators';

    protected $fillable = [
        'id',
        'city',
        'province',
        'osm_id',
        'latitude',
        'longitude',
        'access',
        'indoor',
        'operator',
        'operator_website',
        'phone',
        'location',
        'opening_hours',
        'manufacturer',
        'model',
        'level',
        'image',
        'cabinet',
        'cabinet_manufacturer',
        'note'
    ];
}