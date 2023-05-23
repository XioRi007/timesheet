<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderByCreatedAtScope);
    }
}
