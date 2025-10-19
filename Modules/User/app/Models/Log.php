<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\App\Jobs\SaveLog;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'key',
        'attributes_id',
        'data',
    ];
    public function Log($user_id, $key, $attributes_id, $data)
    {
        SaveLog::dispatch($user_id, $key, $attributes_id, $data);
    }
}
