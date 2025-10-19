<?php

namespace Modules\Course\App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'user_id',
        'episode_id',
        'question',
    ];
}
