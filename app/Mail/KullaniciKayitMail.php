<?php

namespace App\Mail;

use App\Models\Kullanici;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;



class KullaniciKayitMail extends Mailable
{
    use Queueable, SerializesModels;

    public $kullanici;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Kullanici $kullanici)
    {
        $this->kullanici = $kullanici;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            /* ->form('reklamnot@gmail.com') */
            ->subject(config('app.name') . '- Kullanici kaydi')
            ->view('mails.kullanici_kayit');
    }
}
