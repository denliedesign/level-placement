<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FavoritesEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $selectedClasses)
    {
    }

    public function build()
    {
        return $this
            ->subject('Your MDU class favorites')
            ->view('emails.favorites');
    }
}
