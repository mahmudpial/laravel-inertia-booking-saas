<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $recipientType; // 'customer' or 'admin'

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, string $recipientType = 'customer')
    {
        $this->booking = $booking;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope including customized conditional subject lines.
     */
    public function envelope(): Envelope
    {
        $businessName = $this->booking->business->name;
        $subject = "Appointment Status Update - {$businessName}";

        if ($this->booking->status === 'pending' && $this->recipientType === 'admin') {
            $subject = "🚨 New Booking Request Received - Action Required";
        } elseif ($this->booking->status === 'pending') {
            $subject = "⏳ Your Appointment Request is Pending - {$businessName}";
        } elseif ($this->booking->status === 'confirmed') {
            $subject = "✅ Appointment Confirmed! - {$businessName}";
        } elseif ($this->booking->status === 'cancelled') {
            $subject = "❌ Appointment Cancelled - {$businessName}";
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition matching our clean markdown blade view.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-notification',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}