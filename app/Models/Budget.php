<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'amount',
        'period',
        'month_year',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the budget.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the budget.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get expenses for this budget.
     */
    public function expenses()
    {
        return $this->hasManyThrough(
            Expense::class,
            Category::class,
            'id', // Foreign key on categories table (budgets.category_id)
            'category_id', // Foreign key on expenses table (expenses.category_id)
            'category_id', // Local key on budgets table (budgets.category_id)
            'id' // Local key on categories table (categories.id)
        )->where('expenses.user_id', $this->user_id)
          ->where('expenses.date', '>=', $this->getStartDate())
          ->where('expenses.date', '<=', $this->getEndDate());
    }

    /**
     * Get total spent for this budget.
     */
    public function getTotalSpentAttribute()
    {
        // Use direct query for more reliable calculation
        return \App\Models\Expense::where('category_id', $this->category_id)
            ->where('user_id', $this->user_id)
            ->where('date', '>=', $this->getStartDate())
            ->where('date', '<=', $this->getEndDate())
            ->sum('amount');
    }

    /**
     * Get remaining amount for this budget.
     */
    public function getRemainingAttribute()
    {
        return $this->amount - $this->total_spent;
    }

    /**
     * Get percentage used for this budget.
     */
    public function getPercentageUsedAttribute()
    {
        if ($this->amount == 0) return 0;
        return min(100, ($this->total_spent / $this->amount) * 100);
    }

    /**
     * Check if budget is exceeded.
     */
    public function isExceeded()
    {
        return $this->total_spent > $this->amount;
    }

    /**
     * Check if budget is almost exceeded (80% or more).
     */
    public function isAlmostExceeded()
    {
        return $this->percentage_used >= 80 && !$this->isExceeded();
    }

    /**
     * Get start date for the budget period.
     */
    public function getStartDate()
    {
        if ($this->period === 'monthly') {
            return Carbon::parse($this->month_year . '-01')->startOfMonth();
        }
        return Carbon::parse($this->month_year . '-01-01')->startOfYear();
    }

    /**
     * Get end date for the budget period.
     */
    public function getEndDate()
    {
        if ($this->period === 'monthly') {
            return Carbon::parse($this->month_year . '-01')->endOfMonth();
        }
        return Carbon::parse($this->month_year . '-01-01')->endOfYear();
    }

    /**
     * Get formatted period display.
     */
    public function getPeriodDisplayAttribute()
    {
        if ($this->period === 'monthly') {
            return Carbon::parse($this->month_year . '-01')->format('F Y');
        }
        return $this->month_year;
    }

    /**
     * Scope to get active budgets.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get budgets for a specific period.
     */
    public function scopeForPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    /**
     * Scope to get budgets for a specific month/year.
     */
    public function scopeForMonthYear($query, $monthYear)
    {
        return $query->where('month_year', $monthYear);
    }
}
