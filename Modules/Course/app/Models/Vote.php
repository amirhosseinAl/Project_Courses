<?php

namespace Modules\Course\App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $guarded = [];

    const TYPE_DISLIKE = 0;
    const TYPE_LIKE = 1;
}
