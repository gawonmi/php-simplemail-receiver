<?php
/**
 * @package
 * @subpackage
 * @author    Michael Nowag<michael.nowag@maviance.com>
 * @copyright maviance GmbH 2014
 */

namespace SimpleMailReceiver\Commons;

use SimpleMailReceiver\Mail\Attachment;
use SimpleMailReceiver\Mail\Mail;

class MailServer
{

    /**
     * @var resource $imap_stream
     */
    protected $mailbox;

    /**
     * Constructor of the class
     *
     * @param resource $imap_res The resource to use in the imap functions.
     */
    public function __constructor($imap_res)
    {
        $this->mailbox = $imap_res;
    }

    /**
     * Return selected mail
     *
     * @param string $id email identifier
     *
     * @return null|Mail
     */
    public function retriveMail($id)
    {
        $mail = new Mail();
        $mail->setMailHeader($this->retriveHeaders($id));
        $mail->setBody($this->retriveBody($id));
        $mail->setAttachments($this->retrieveAttachments($id));
        return $mail;
    }

    /**
     * Return all unread mails
     *
     * @return Collection
     */
    public function retriveAllMails()
    {
        $mails = new Collection();
        for($i = 0; $i < $this->countAllMails(); $i++)
        {
            $mails->addItem($this->retriveMail($i));
        }
        return $mails;
    }

    /**
     * Retrieve headers
     *
     * @param $id
     *
     * @return Collection
     */
    public function retriveHeaders($id) // Get Header info
    {
        $mail_header    = imap_header($this->mailbox, $id);
        if (!is_null($mail_header)) {
            // more details must be added
            $data_header = array(
                'msgno'   => ( property_exists($mail_header,'msgno') ? $mail_header->msgno : null),
                'to'      => ( property_exists($mail_header,'toaddress') ? $mail_header->toaddress : null),
                'from'    => ( property_exists($mail_header,'fromaddress') ? $mail_header->fromaddress : null),
                'reply'   => ( property_exists($mail_header,'reply_toaddress') ? $mail_header->reply_toaddress : null),
                'subject' => ( property_exists($mail_header,'subject') ? $mail_header->subject : null),
                'udate'   => ( property_exists($mail_header,'udate') ? $mail_header->udate : null),
                'unseen'  => ( property_exists($mail_header,'Unseen') ? $mail_header->Unseen : null),
                'size'    => ( property_exists($mail_header,'Size') ? $mail_header->Size : null)
            );
        }
        return new Collection($data_header);
    }

    /**
     * Get email message body
     *
     * @param string $id email identifier
     *
     * @return string
     */
    public function retriveBody($id)
    {
        $body = $this->get_part($this->mailbox, $id, "TEXT/HTML");
        // fallback to plain text
        if ($body == "") {
            $body = $this->get_part($this->mailbox, $id, "TEXT/PLAIN");
        }

        return $body;
    }

    /**
     * Get attachments from email
     *
     * @param string $id email identifier
     *
     * @return Collection
     */
    public function retrieveAttachments($id)
    {
        $attachments = new Collection();
        $structure   = imap_fetchstructure($this->mailbox, $id);

        if (is_array($structure)) {
            foreach ($structure->parts as $key => $value) {
                $encoding = $structure->parts[ $key ]->encoding;
                if ($structure->parts[ $key ]->ifdparameters) {
                    $name    = $structure->parts[ $key ]->dparameters[ 0 ]->value;
                    $message = imap_fetchbody($this->mailbox, $id, $key + 1);
                    if ($encoding == 0) {
                        $message = imap_8bit($message);
                    }
                    if ($encoding == 1) {
                        $message = imap_8bit($message);
                    }
                    if ($encoding == 2) {
                        $message = imap_binary($message);
                    }
                    if ($encoding == 3) {
                        $message = imap_base64($message);
                    }
                    if ($encoding == 4) {
                        $message = quoted_printable_decode($message);
                    }
                    if ($encoding == 5) {
                        $message = $message;
                    }
                    $name_ext = pathinfo($name);
                    $attachment = new Attachment($name_ext[ 'filename' ], $message, $name_ext[ 'extension' ], sizeof($message));
                    $attachments->addItem($attachment);
                }
            }
        }
        return $attachments;
    }

    /**
     * Get total Number of unread emails
     *
     * @return int
     */
    public function countUnreadMails()
    {
        $info = imap_mailboxmsginfo($this->mailbox);
        return ( property_exists($info,'unread') ? (int) $info->unread : null);
    }

    /**
     * Get total number of all emails
     *
     * @return int
     */
    public function countAllMails()
    {
        return imap_num_msg($this->mailbox);
    }

    /**
     * Delete selected mail by mail id
     *
     * @param string $id email identifier
     *
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function delete($id)
    {
        return imap_delete($this->mailbox, $id);
    }

    /**
     * Close mailbox
     *
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function close()
    {
        return imap_close($this->mailbox, CL_EXPUNGE);
    }

    /**
     * Extract MIME information
     *
     * @param $structure
     *
     * @return string
     */
    protected function extractMimetype($structure)
    {
        $primary_mime_type = array(
            "TEXT",
            "MULTIPART",
            "MESSAGE",
            "APPLICATION",
            "AUDIO",
            "IMAGE",
            "VIDEO",
            "OTHER"
        );

        if ($structure->subtype) {
            return $primary_mime_type[ (int) $structure->type ] . '/' . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }

    /**
     * @param $stream
     * @param $msg_number
     * @param $mime_type
     * @param bool $structure
     * @param bool $part_number
     * @return bool|string
     */
    protected function get_part(
        $stream, $msg_number, $mime_type, $structure = false, $part_number = false
    ) // Get Part Of Message Internal Private Use
    {
        if (!$structure) {
            $structure = imap_fetchstructure($stream, $msg_number);
        }
        if ($structure) {
            if ($mime_type == $this->get_mime_type($structure)) {
                if (!$part_number) {
                    $part_number = "1";
                }
                $text = imap_fetchbody($stream, $msg_number, $part_number);
                if ($structure->encoding == 3) {
                    return imap_base64($text);
                } else if ($structure->encoding == 4) {
                    return imap_qprint($text);
                } else {
                    return $text;
                }
            }
            if ($structure->type == 1) /* multipart */ {
                while (list ($index, $sub_structure) = each($structure->parts)) {
                    $prefix = '';
                    if ($part_number) {
                        $prefix = $part_number . '.';
                    }
                    $data = $this->get_part($stream, $msg_number, $mime_type, $sub_structure, $prefix . ($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }
}
