<?php

namespace App\Models;

use App\Models\Contracts\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Developer extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'rate',
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
     * Get the work logs of the developer.
     */
    public function workLogs(): HasMany
    {
        return $this->hasMany(WorkLog::class);
    }


    /**
     * Get the full name of the developer.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Returns developer name and array of hours worked in month
     * @param  $startOfMonth
     * @param  $endOfMonth
     * @return  array
     */
    public static function getDevelopersWorkLogHoursByMonth($startOfMonth, $endOfMonth): array
    {
        $developers = Developer::where('status', true)
            ->select('id', 'first_name', 'last_name')
            ->with('workLogs:hrs,created_at,id,developer_id')
            ->whereHas('workLogs', function ($query) use ($startOfMonth, $endOfMonth) {
                return $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            })->get();

        $data = [];
        foreach ($developers as $developer) {
            $hours = [];
            for ($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay()) {
                $totalHours = collect($developer->workLogs)
                    ->filter(function ($workLog) use ($date) {
                        return $workLog['created_at']->toDateString() === $date->toDateString();
                    })
                    ->sum('hrs');
                $hours[] = $totalHours;
            }

            $data[] = [
                'id' => $developer->id,
                'name' => $developer->full_name,
                'hours' => $hours,
            ];
        }
        return $data;
    }

}
