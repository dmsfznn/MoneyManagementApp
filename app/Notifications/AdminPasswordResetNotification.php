<?php

namespace App\Notifications;

use App\Models\PasswordResetRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class AdminPasswordResetNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public PasswordResetRequest $passwordResetRequest)
    {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = route('admin.password-resets.edit', $this->passwordResetRequest);
        $userEmail = $this->passwordResetRequest->email;
        $requestedAt = $this->passwordResetRequest->created_at->format('M d, Y H:i A');

        return (new MailMessage)
            ->subject('Password Reset Request - Money Management App')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A user has requested a password reset for their Money Management App account.')
            ->line('**Request Details:**')
            ->line('• Email: ' . $userEmail)
            ->line('• Requested at: ' . $requestedAt)
            ->line('• Status: Pending')
            ->action('Review Request', $url)
            ->line('Please review this request and take appropriate action.')
            ->line('You can either approve and reset the password, or cancel the request if it seems suspicious.')
            ->line('This request will expire if not processed within 24 hours.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'password_reset_request_id' => $this->passwordResetRequest->id,
            'user_email' => $this->passwordResetRequest->email,
            'status' => $this->passwordResetRequest->status,
            'requested_at' => $this->passwordResetRequest->created_at,
            'message' => 'New password reset request from: ' . $this->passwordResetRequest->email,
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'password_reset_request_id' => $this->passwordResetRequest->id,
            'user_email' => $this->passwordResetRequest->email,
            'status' => $this->passwordResetRequest->status,
            'requested_at' => $this->passwordResetRequest->created_at->toDateTimeString(),
            'message' => 'New password reset request from: ' . $this->passwordResetRequest->email,
        ];
    }
}