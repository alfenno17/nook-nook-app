<?php

namespace App\Mail;

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $statusType; 

    public function __construct(Booking $booking, string $statusType)
    {
        $this->booking = $booking;
        $this->statusType = $statusType;
    }

    public function build()
    {
        $subject = match($this->statusType) {
            'menunggu_pembayaran' => 'Instruksi Pembayaran Booking - #' . $this->booking->kode,
            'menunggu_konfirmasi' => 'Pembayaran Diterima, Menunggu Konfirmasi - #' . $this->booking->kode,
            'berhasil'            => 'Booking Ruangan Berhasil Konfirmasi - #' . $this->booking->kode,
            default               => 'Update Status Booking - #' . $this->booking->kode,
        };

        return $this->subject($subject)
                    ->view('emails.booking_notification');
    }
}
