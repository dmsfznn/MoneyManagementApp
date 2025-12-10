<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class DateFilterService
{
    const FILTER_DAILY = 'daily';
    const FILTER_WEEKLY = 'weekly';
    const FILTER_MONTHLY = 'monthly';
    const FILTER_YEARLY = 'yearly';

    /**
     * Get available date filter options
     */
    public static function getFilterOptions()
    {
        return [
            self::FILTER_DAILY => 'Harian',
            self::FILTER_WEEKLY => 'Mingguan',
            self::FILTER_MONTHLY => 'Bulanan',
            self::FILTER_YEARLY => 'Tahunan',
        ];
    }

    /**
     * Set date filter in session
     */
    public static function setFilter($filter, $customDate = null)
    {
        Session::put('date_filter', $filter);

        // Set custom date if provided
        if ($customDate) {
            Session::put('custom_date', $customDate->format('Y-m-d'));
        }
    }

    /**
     * Get current date filter
     */
    public static function getCurrentFilter()
    {
        return Session::get('date_filter', self::FILTER_MONTHLY);
    }

    /**
     * Get custom date from session
     */
    public static function getCustomDate()
    {
        $date = Session::get('custom_date');
        return $date ? Carbon::parse($date) : null;
    }

    /**
     * Clear date filter
     */
    public static function clearFilter()
    {
        Session::forget('date_filter');
        Session::forget('custom_date');
    }

    /**
     * Get date range based on filter type
     */
    public static function getDateRange($filter = null, $customDate = null)
    {
        // If filter is provided, update session
        if ($filter) {
            self::setFilter($filter, $customDate);
        }

        $filter = $filter ?: self::getCurrentFilter();
        $customDate = $customDate ?: self::getCustomDate();

        $startDate = null;
        $endDate = null;

        switch ($filter) {
            case self::FILTER_DAILY:
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;

            case self::FILTER_WEEKLY:
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;

            case self::FILTER_MONTHLY:
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;

            case self::FILTER_YEARLY:
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;

            case 'custom':
                if ($customDate) {
                    $startDate = $customDate->copy()->startOfDay();
                    $endDate = $customDate->copy()->endOfDay();
                }
                break;

            default:
                // Default to monthly
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
        }

        return [
            'start' => $startDate,
            'end' => $endDate,
            'label' => self::getFilterLabel($filter, $customDate)
        ];
    }

    /**
     * Get filter label with date range
     */
    public static function getFilterLabel($filter, $customDate = null)
    {
        $options = self::getFilterOptions();

        if (isset($options[$filter])) {
            return $options[$filter];
        } elseif ($filter === 'custom' && $customDate) {
            return 'Custom: ' . $customDate->format('d M Y');
        }

        return self::FILTER_MONTHLY;
    }

    /**
     * Format date for display
     */
    public static function formatDate($date)
    {
        return $date->format('d F Y');
    }

    /**
     * Format date with time for display
     */
    public static function formatDateTime($date)
    {
        return $date->format('d F Y H:i');
    }

    /**
     * Get readable date range text
     */
    public static function getDateRangeText($filter = null, $customDate = null)
    {
        $range = self::getDateRange($filter, $customDate);

        if ($range['start']->format('Y-m-d') === $range['end']->format('Y-m-d')) {
            return self::formatDate($range['start']);
        }

        return self::formatDate($range['start']) . ' - ' . self::formatDate($range['end']);
    }

    /**
     * Apply date filter to query
     */
    public static function applyToQuery($query, $filter = null, $customDate = null)
    {
        $range = self::getDateRange($filter, $customDate);

        return $query->whereBetween('date', [$range['start'], $range['end']]);
    }

    /**
     * Get previous period date range for comparison
     */
    public static function getPreviousPeriod($filter = null, $customDate = null)
    {
        $filter = $filter ?: self::getCurrentFilter();
        $customDate = $customDate ?: self::getCustomDate();

        switch ($filter) {
            case self::FILTER_DAILY:
                return [
                    'start' => now()->subDay(1)->startOfDay(),
                    'end' => now()->subDay(1)->endOfDay()
                ];

            case self::FILTER_WEEKLY:
                return [
                    'start' => now()->subWeek(1)->startOfWeek(),
                    'end' => now()->subWeek(1)->endOfWeek()
                ];

            case self::FILTER_MONTHLY:
                return [
                    'start' => now()->subMonth(1)->startOfMonth(),
                    'end' => now()->subMonth(1)->endOfMonth()
                ];

            case self::FILTER_YEARLY:
                return [
                    'start' => now()->subYear(1)->startOfYear(),
                    'end' => now()->subYear(1)->endOfYear()
                ];

            case 'custom':
                if ($customDate) {
                    return [
                        'start' => $customDate->copy()->subYear(1)->startOfDay(),
                        'end' => $customDate->copy()->subYear(1)->endOfDay()
                    ];
                }
                break;
        }

        return self::getPreviousPeriod(self::FILTER_MONTHLY);
    }

    /**
     * Get next period date range for comparison
     */
    public static function getNextPeriod($filter = null, $customDate = null)
    {
        $filter = $filter ?: self::getCurrentFilter();
        $customDate = $customDate ?: self::getCustomDate();

        switch ($filter) {
            case self::FILTER_DAILY:
                return [
                    'start' => now()->addDay(1)->startOfDay(),
                    'end' => now()->addDay(1)->endOfDay()
                ];

            case self::FILTER_WEEKLY:
                return [
                    'start' => now()->addWeek(1)->startOfWeek(),
                    'end' => now()->addWeek(1)->endOfWeek()
                ];

            case self::FILTER_MONTHLY:
                return [
                    'start' => now()->addMonth(1)->startOfMonth(),
                    'end' => now()->addMonth(1)->endOfMonth()
                ];

            case self::FILTER_YEARLY:
                return [
                    'start' => now()->addYear(1)->startOfYear(),
                    'end' => now()->addYear(1)->endOfYear()
                ];

            case 'custom':
                if ($customDate) {
                    return [
                        'start' => $customDate->copy()->addYear(1)->startOfDay(),
                        'end' => $customDate->copy()->addYear(1)->endOfDay()
                    ];
                }
                break;
        }

        return self::getNextPeriod(self::FILTER_MONTHLY);
    }

    /**
     * Check if custom filter is active
     */
    public static function isCustomFilterActive()
    {
        return Session::get('date_filter') === 'custom' || Session::has('custom_date');
    }

    /**
     * Get available quick date presets
     */
    public static function getQuickPresets()
    {
        return [
            'today' => [
                'label' => 'Hari Ini',
                'filter' => self::FILTER_DAILY,
                'date' => now()
            ],
            'this_week' => [
                'label' => 'Minggu Ini',
                'filter' => self::FILTER_WEEKLY,
                'date' => now()
            ],
            'this_month' => [
                'label' => 'Bulan Ini',
                'filter' => self::FILTER_MONTHLY,
                'date' => now()
            ],
            'this_year' => [
                'label' => 'Tahun Ini',
                'filter' => self::FILTER_YEARLY,
                'date' => now()
            ],
            'yesterday' => [
                'label' => 'Kemarin',
                'filter' => self::FILTER_DAILY,
                'date' => now()->subDay(1)
            ],
            'last_week' => [
                'label' => 'Minggu Lalu',
                'filter' => self::FILTER_WEEKLY,
                'date' => now()->subWeek(1)
            ],
            'last_month' => [
                'label' => 'Bulan Lalu',
                'filter' => self::FILTER_MONTHLY,
                'date' => now()->subMonth(1)
            ],
            'last_year' => [
                'label' => 'Tahun Lalu',
                'filter' => self::FILTER_YEARLY,
                'date' => now()->subYear(1)
            ]
        ];
    }
}