<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Verify extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'expire_at',
    ];
}
