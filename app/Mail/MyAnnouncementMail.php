<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MyAnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fromEmail;
    public $announcement;

    /**
     * Constructor
     *
     * @param string|null $fromEmail The Gmail account to send from
     * @param \App\Models\Announcement|null $announcement The announcement data
     */
    public function __construct($fromEmail = null, $announcement = null)
    {
        $this->fromEmail = $fromEmail;
        $this->announcement = $announcement;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Pass the announcement data to the email view
        $mail = $this->view('emails.announcement')
                     ->with([
                         'title' => $this->announcement->title ?? '',
                         'message' => $this->announcement->message ?? '',
                         'created_at' => $this->announcement->created_at ?? now(),
                     ]);

        // Set the sender email if provided
        if ($this->fromEmail) {
            $mail->from($this->fromEmail, 'BAP Announcements');
        }

        return $mail;
    }
}
