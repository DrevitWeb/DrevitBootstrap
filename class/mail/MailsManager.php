<?php


namespace mail;

use basics\Database;
use basics\Utils;
use users\UsersManager;

class MailsManager
{
    public static function sendMail($from, $to, $subject, $content)
    {
        $token = Utils::generateRandomString(10);
        Database::query("INSERT INTO mails (mail_from, mail_to, subject, content, post_date, token) VALUES ('".$from."', '".$to."', '".addslashes($subject)."', '".addslashes($content)."', '".time()."', '".$token."')");
        return $token;
    }

    public static function replyMail($from, $content, $parent)
    {
        $token = Utils::generateRandomString(10);

        $parent = self::getMail($parent);

        if($parent->mail_from == $from)
        {
            $to = $parent->mail_to;
        }
        else
        {
            $to = $parent->mail_from;
        }

        $subject = $parent->subject;

        if($parent->parent != null)
        {
            $parent = self::getMail($parent->parent);
        }

        Database::query("INSERT INTO mails (mail_from, mail_to, subject, content, parent, post_date, token) VALUES ('".$from."', '".$to."', '".addslashes($subject)."', '".addslashes($content)."', '$parent->token' ,'".time()."', '".$token."')");
        return $token;
    }

    public static function  listMails($to)
    {
        $mails_to = Database::query("SELECT * FROM mails WHERE (mail_to = '".$to."' OR mail_from = '".$to."') AND parent IS NULL ORDER BY post_date DESC")->fetchAll();

        $mails = array();

        foreach ($mails_to as $mail)
        {
            $mail = self::getMail($mail->token);
            array_push($mails, $mail);
        }

        return $mails;
    }

    public static function getMail($token)
    {
        $mail = Database::query("SELECT * FROM mails WHERE token = '".$token."'")->fetch();
        if($mail != null)
        {
            $mail->childs = Database::query("SELECT * FROM mails WHERE parent = '".$mail->token."'")->fetchAll();

            return $mail;
        }
        else
        {
            return null;
        }
    }

    public static function deleteMail($token)
    {
        Database::query("DELETE FROM mails WHERE parent = '".$token."'");
        Database::query("DELETE FROM mails WHERE token = '".$token."'");
    }

    public static function setMailRead($token)
    {
        $mail = Database::query("SELECT * FROM mails WHERE token = '".$token."'")->fetch();
        $Inmail = new InMail($mail->mail_to, $mail->mail_from, $mail->subject, $mail->content, $mail->post_date, $mail->token);
        $Inmail->setRead(true);
        $childs = Database::query("SELECT * FROM mails WHERE parent = '".$mail->token."'")->fetchAll();
        foreach ($childs as $child)
        {
            self::setMailRead($child->token);
        }
    }

    public static function hasMailsUnread($user_token)
    {
        $mails_to = Database::query("SELECT * FROM mails WHERE (mail_to = '".$user_token."') ORDER BY post_date DESC")->fetchAll();

        foreach ($mails_to as $mail)
        {
            if($mail->mail_read == 0)
            {
                return true;
            }
        }
        return false;
    }
}