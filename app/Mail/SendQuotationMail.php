<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendQuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;
    public array $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice, array $data)
    {
        $this->invoice = $invoice;
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(setting('email'), setting('site_name')),
            replyTo: [
                new Address(setting('email'), setting('site_name')),
            ],
            subject: 'A quotation from ' . setting('site_name')
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.send-quotation',
            with: [
                'url' => data_get($this->data, 'url'),
                'message' => data_get($this->data, 'message')
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachment = $this->invoice->getFirstMedia('quotation');
        if (blank($attachment)) {
            return [];
        }

        return [Attachment::fromPath($attachment->getPath())];

    }
}
