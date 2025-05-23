<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeAffiliateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cliente;
    public $email;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct($cliente, $email, $password)
    {
        $this->cliente = $cliente;
        $this->email = $email;
        $this->password = $password;
    }



    public function build()
    {
        return $this->subject('Dflix Control:: Bem-vindo ao nosso sistema!')
            ->view('emails.welcomeAffiliate');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
