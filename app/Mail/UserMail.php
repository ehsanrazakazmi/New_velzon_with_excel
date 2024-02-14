<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;
    public $post;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $date = Carbon::now();
        $invoiceNumber = $date->format('dmY');
        $nerdflow = public_path('images/nerdflow.jfif');
        return $this->view('mails.sendmail', ['post'=> $this->post, 'invoiceNumber'=>$invoiceNumber , 'nerdflow'=>$nerdflow ]);
    }
}
