<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlacklistedIP extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blacklisted_ips';

    protected $fillable = [
        'ip',
        'reason',
        'created_at'
    ];
}