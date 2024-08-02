<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    // Define the order property to hold the order as object, its from the checkout page after the order has been placed
    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct($order)
    {
        $this->order =  $order; // Assign the order to the $order property
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Placed successfully!',
        );
    }

    /**
     * Get the message content definition.
     *  @return Content of the message from the markdown mailables file (resources/views/mail/orders/placed.blade.php)
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.orders.placed',
            with: [
                'url' => route('my-orders.show', $this->order), // route('my-orders.show') is the name of the route in routes/web.php
            ]
        );
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
