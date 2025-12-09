<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'token',
        'status',
        'admin_notes',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the password reset request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Status options
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for processing requests
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    /**
     * Scope for completed requests
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted($adminNotes = null)
    {
        $this->status = self::STATUS_COMPLETED;
        $this->admin_notes = $adminNotes;
        $this->completed_at = now();
        $this->updated_at = now();
        $result = $this->save();

        // Debug logging
        \Log::info('markAsCompleted called - save result: ' . ($result ? 'SUCCESS' : 'FAILED'));
        \Log::info('Model after save - Status: ' . $this->status . ', Completed at: ' . $this->completed_at);
        \Log::info('Admin notes: ' . $adminNotes);

        return $result;
    }

    /**
     * Mark as processing
     */
    public function markAsProcessing()
    {
        $this->status = self::STATUS_PROCESSING;
        $this->updated_at = now();
        $result = $this->save();

        // Debug logging
        \Log::info('markAsProcessing called - save result: ' . ($result ? 'SUCCESS' : 'FAILED'));
        \Log::info('Model after save - Status: ' . $this->status . ', Updated at: ' . $this->updated_at);

        return $result;
    }

    /**
     * Cancel the request
     */
    public function cancel()
    {
        $this->status = self::STATUS_CANCELLED;
        $this->save();
    }

    /**
     * Check if request is pending
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if request is completed
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}