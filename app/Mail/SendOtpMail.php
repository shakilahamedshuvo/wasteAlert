<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    /**
     * Create a new message instance.
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
        Log::info('📧 SendOtpMail initialized with OTP: ' . $otp);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        Log::info('📧 Building OTP email...', ['otp' => $this->otp]);

        return $this->from(config('mail.from.address'), config('mail.from.name')) // Explicit sender
                    ->subject('Your OTP Code')
                    ->view('Emails.otp') // HTML view
                    
                    ->with([
                        'otp' => $this->otp,
                    ]);
    }
}
