<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'developer_id',
        'project_id',
        'rate',
        'hrs',
        'total',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];


    /**
     * Get the developer that works.
     */
    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    /**
     * Get the project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getRateAttribute()
    {
        if ($this->attributes['rate']) {
            return $this->attributes['rate'];
        }
        if ($this->developer_id) {
            $developer = Developer::find($this->developer_id);
            if ($developer->rate) {
                return $developer->rate;
            }
        }

        if ($this->project_id) {
            $project = Project::find($this->project_id);
            if ($project->rate) {
                return $project->rate;
            }
        }

        if ($this->project_id) {
            $client = Project::find($this->project_id)->client;
            if ($client->rate) {
                return $client->rate;
            }
        }

        return null;
    }
}
