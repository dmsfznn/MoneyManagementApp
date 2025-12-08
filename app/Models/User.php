<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get incomes for the user
     */
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    /**
     * Get expenses for the user
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get budgets for the user
     */
    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * Get active budgets for the user
     */
    public function activeBudgets()
    {
        return $this->budgets()->where('is_active', true);
    }

    /**
     * Get total income for current month
     */
    public function getMonthlyIncomeAttribute()
    {
        return $this->incomes()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
    }

    /**
     * Get total expenses for current month
     */
    public function getMonthlyExpenseAttribute()
    {
        return $this->expenses()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
    }

    /**
     * Get total balance (all time income - all time expenses)
     */
    public function getTotalBalanceAttribute()
    {
        return $this->incomes()->sum('amount') - $this->expenses()->sum('amount');
    }
}
