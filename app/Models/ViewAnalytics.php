<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ViewAnalytics extends Model
{
    use HasFactory;

    protected $table = 'view_analytics';

    protected $fillable = [
        'viewable_type',
        'viewable_id',
        'date',
        'period',
        'total_views',
        'unique_views',
        'returning_views',
        'country_breakdown',
        'device_breakdown',
    ];

    protected $casts = [
        'date' => 'date',
        'total_views' => 'integer',
        'unique_views' => 'integer',
        'returning_views' => 'integer',
        'country_breakdown' => 'array',
        'device_breakdown' => 'array',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeByViewable($query, $viewableType, $viewableId)
    {
        return $query->where('viewable_type', $viewableType)
                    ->where('viewable_id', $viewableId);
    }

    public function scopeByPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeToday($query)
    {
        return $query->where('date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]);
    }

    public function scopeLastDays($query, $days)
    {
        return $query->where('date', '>=', now()->subDays($days));
    }

    public function scopeOrderedByDate($query)
    {
        return $query->orderBy('date', 'desc');
    }

    public function scopeOrderedByViews($query)
    {
        return $query->orderBy('total_views', 'desc');
    }

    // Accessors
    public function getEngagementRateAttribute()
    {
        if ($this->unique_views === 0) return 0;
        return round(($this->returning_views / $this->unique_views) * 100, 2);
    }

    public function getAverageViewsPerVisitorAttribute()
    {
        if ($this->unique_views === 0) return 0;
        return round($this->total_views / $this->unique_views, 2);
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('Y-m-d');
    }

    public function getPeriodTextAttribute()
    {
        $periods = [
            'day' => 'Daily',
            'week' => 'Weekly',
            'month' => 'Monthly',
        ];

        return $periods[$this->period] ?? ucfirst($this->period);
    }

    public function getTopCountryAttribute()
    {
        if (!$this->country_breakdown || !is_array($this->country_breakdown)) {
            return null;
        }

        arsort($this->country_breakdown);
        return array_key_first($this->country_breakdown);
    }

    public function getTopDeviceAttribute()
    {
        if (!$this->device_breakdown || !is_array($this->device_breakdown)) {
            return null;
        }

        arsort($this->device_breakdown);
        return array_key_first($this->device_breakdown);
    }

    public function getCountryBreakdownPercentageAttribute()
    {
        if (!$this->country_breakdown || !is_array($this->country_breakdown)) {
            return [];
        }

        $total = array_sum($this->country_breakdown);
        if ($total === 0) return [];

        $percentages = [];
        foreach ($this->country_breakdown as $country => $count) {
            $percentages[$country] = round(($count / $total) * 100, 2);
        }

        arsort($percentages);
        return $percentages;
    }

    public function getDeviceBreakdownPercentageAttribute()
    {
        if (!$this->device_breakdown || !is_array($this->device_breakdown)) {
            return [];
        }

        $total = array_sum($this->device_breakdown);
        if ($total === 0) return [];

        $percentages = [];
        foreach ($this->device_breakdown as $device => $count) {
            $percentages[$device] = round(($count / $total) * 100, 2);
        }

        arsort($percentages);
        return $percentages;
    }

    // Methods
    public static function aggregateViews($viewable, $date, $period = 'day')
    {
        $query = View::where('viewable_type', get_class($viewable))
                    ->where('viewable_id', $viewable->id);

        // Filter by date based on period
        switch ($period) {
            case 'day':
                $query->whereDate('viewed_at', $date);
                break;
            case 'week':
                $query->whereBetween('viewed_at', [
                    $date->startOfWeek(),
                    $date->endOfWeek()
                ]);
                break;
            case 'month':
                $query->whereBetween('viewed_at', [
                    $date->startOfMonth(),
                    $date->endOfMonth()
                ]);
                break;
        }

        $views = $query->get();

        // Calculate metrics
        $totalViews = $views->count();
        $uniqueVisitors = $views->pluck('visitor_id')->filter()->unique()->count();
        $returningViews = $totalViews - $uniqueVisitors;

        // Country breakdown
        $countryBreakdown = $views->pluck('country')
                                 ->filter()
                                 ->countBy()
                                 ->toArray();

        // Device breakdown
        $deviceBreakdown = $views->pluck('device_type')
                                ->filter()
                                ->countBy()
                                ->toArray();

        // Create or update analytics record
        return static::updateOrCreate(
            [
                'viewable_type' => get_class($viewable),
                'viewable_id' => $viewable->id,
                'date' => $date,
                'period' => $period,
            ],
            [
                'total_views' => $totalViews,
                'unique_views' => $uniqueVisitors,
                'returning_views' => $returningViews,
                'country_breakdown' => $countryBreakdown,
                'device_breakdown' => $deviceBreakdown,
            ]
        );
    }

    public function getGrowthRate($previousPeriod)
    {
        if ($previousPeriod->total_views === 0) return 0;
        
        $growth = (($this->total_views - $previousPeriod->total_views) / $previousPeriod->total_views) * 100;
        return round($growth, 2);
    }

    public function getTrendDirection($previousPeriod)
    {
        $growth = $this->getGrowthRate($previousPeriod);
        
        if ($growth > 0) return 'up';
        if ($growth < 0) return 'down';
        return 'stable';
    }
}
