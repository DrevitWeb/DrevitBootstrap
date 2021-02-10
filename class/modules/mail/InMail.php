<?php


namespace modules\mail;

use basics\Database;

class InMail
{
    public $mail_from;
    public $mail_to;

    public $subject;
    public $content;

    public $parent;
    public $post_date;
    public $mail_read;
    public $token;

    public function __construct($mail_to, $mail_from, $subject, $content, $post_date, $token)
    {
        $this->mail_to = $mail_to;
        $this->mail_from = $mail_from;

        $this->subject = $subject;
        $this->content = $content;

        $this->post_date = $post_date;
        $this->token = $token;
    }

    public function setParent($parent)
    {
        Database::modify("mails", "parent", $parent, $this->token);
        $this->parent = $parent;
    }

    public function  setRead($read)
    {
        $this->mail_read = $read;
        Database::modify("mails", "mail_read", $read, $this->token);
    }
}