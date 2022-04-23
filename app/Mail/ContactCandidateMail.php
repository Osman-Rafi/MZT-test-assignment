<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactCandidateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate_name;
    public $company_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($candidate_name, $company_name)
    {
        $this->candidate_name = $candidate_name;
        $this->company_name = $company_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Hello from ' . $this->company_name)
            ->from('example@example.com')
            ->view('emails.contact-candidate');
    }
}
