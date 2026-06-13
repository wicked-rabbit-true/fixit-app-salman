<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Helpers\Helpers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use App\Models\EmailTemplate;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->queue = 'invoiceNotification';
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $content = EmailTemplate::where('slug', 'booking-completed-invoice')->first();
        $currency = Helpers::getDefaultCurrency()?->symbol;

        $mailMessage = (new MailMessage)
            ->subject($content?->title ?? 'Invoice for Booking #' . $this->booking->booking_number)
            ->greeting('Hello ' . $notifiable->name . ',');

        if ($content) {
            $data = [
                '{{booking_number}}' => $this->booking->booking_number,
                '{{consumer_name}}' => $notifiable->name,
                '{{total_amount}}' => $currency . $this->booking->total,
            ];
            $messageBody = str_replace(array_keys($data), array_values($data), $content->content);
            $mailMessage->line($messageBody);
        } else {
            $mailMessage->line('Your booking #' . $this->booking->booking_number . ' has been completed.')
                ->line('Total amount: ' . $currency . $this->booking->total)
                ->line('Please find the invoice attached.');
        }

        $addonsChargeAmount = Helpers::getTotalAddonCharges($this->booking->id);
        $pdf = PDF::loadView('emails.invoice', [
            'booking' => $this->booking,
            'settings' => Helpers::getSettings(),
            'addonsChargeAmount' => $addonsChargeAmount,
        ]);

        $mailMessage->attachData($pdf->output(), 'invoice-' . $this->booking->booking_number . '.pdf', [
            'mime' => 'application/pdf',
        ]);

        return $mailMessage;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Invoice Generated',
            'body' => 'Invoice for booking #' . $this->booking->booking_number . ' is now available.',
            'booking_id' => $this->booking->id,
            'booking_number' => $this->booking->booking_number,
            'type' => 'invoice',
        ];
    }
}
