<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected static function boot()
    {
        parent::boot();
    }


    /**
     * Filter by params
     * @param  $query
     * @param  array  $filterParams
     * @return  void
     */
    public function scopefilter($query, array $filterParams)
    {
        foreach ($filterParams as $key => $value) {
            if ($value !== null && $key != 'status') {
                $query->where($key, $value);
            }
        }
        if (array_key_exists('status', $filterParams) && $filterParams['status'] !== null) {
            $query->where('status', $filterParams['status'] == 'true');
        }
    }

    public function scopesort($query, string $column, string $ascending)
    {
        if (str_contains($column, '.')) {
            $arr = explode(".", $column);
            $model = "App\Models\\" . ucfirst($arr[0]);
            if($arr[1] == 'full_name'){
                $arr[1] = 'CONCAT(first_name, " ", last_name) AS full_name';
            }
            $table = $this->getTable();
            $query->orderBy($model::selectRaw($arr[1])->
                whereColumn("id", rtrim($table) . '.' . $arr[0].'_id'),
                $ascending
            );
        } else {
            $query->orderBy($column, $ascending);
        }
    }
}
