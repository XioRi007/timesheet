<?php

namespace App\Models;

use App\Models\Contracts\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class WorkLog extends BaseModel
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
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean'
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

    public static function GetRate($developer_id, $project_id): float
    {
        if ($developer_id) {
            $developer = Developer::find($developer_id);

            if ($developer->rate != '0.00') {
                return floatval($developer->rate);
            }
        }
        if ($project_id) {
            $project = Project::find($project_id);
            if ($project->rate != '0.00') {
                return floatval($project->rate);
            }
        }
        if ($project_id) {
            $client = Project::find($project_id)->client;
            if ($client->rate != '0.00') {
                return floatval($client->rate);
            }
        }
        return 0;
    }

    public function scopeCheckMaxHoursToday($query, string $developer_id, float $hrs, string $id='')
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $totalHours =$query->where('developer_id', $developer_id)
            ->whereDate('created_at', $currentDate)
            ->where('id', '<>', $id)
            ->sum('hrs');

        if($totalHours+$hrs > 24){
            throw ValidationException::withMessages(['hrs' => 'Developer must not work more than 24 hours/day']);
        }
        return $totalHours;
    }


    /**
     *
     */
    public function scopeTotalPayed($query, \DateTimeInterface $start, \DateTimeInterface $end)
    {
        return $query->where('status', true)
            ->whereBetween('created_at', [$start, $end])
            ->sum('total');
    }

    /**
     *
     */
    public function scopeTotalUnpayed($query, \DateTimeInterface $start, \DateTimeInterface $end)
    {
        return $query->where('status', false)
            ->whereBetween('created_at', [$start, $end])
            ->sum('total');
    }
}
