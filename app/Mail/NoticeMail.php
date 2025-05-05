<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoticeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $description;
    public $status;
    public $departments;
    public $notice_date;

    /**
     * Create a new message instance.
     */
    public function __construct($title, $description, $departments, $notice_date)
    {
        $this->title = $title;
        $this->description = $description;
        $this->departments = $departments;
        $this->notice_date = $notice_date;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->title)
            ->markdown('notice_board.notice_mail');
    }

}
