<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCustomerMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Customer $customer,
        public readonly string $secret,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('app.name')),
            subject: 'Seu acesso ao Freezer Control',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-customer',
            with: [
                'customer' => $this->customer,
                'secret' => $this->secret,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
