<?php
/**
 * @package
 * @subpackage
 * @author    Michael Nowag<michael.nowag@maviance.com>
 * @copyright maviance GmbH 2014
 */

namespace SimpleMailReceiver\Protocol;

use SimpleMailReceiver\Commons\Collection;
use SimpleMailReceiver\Mail\Mail;

abstract class Mailserver implements ProtocolInterfac
{

    protected $mailbox;

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
     * Get total Number of unread emails
     *
     * @return int
     */
    public function getCountUnreadMails()
    {
        $headers = imap_headers($this->mailbox);

        return count($headers);
    }

    /**
     * Get total number of all emails
     *
     * @return int
     */
    public function getCountAllMails()
    {
        return imap_num_msg($this->mailbox);
    }



    /**
     * Return selected mail
     *
     * @param string $id email identifier
     *
     * @return null|Mail
     */
    public function getMail($id)
    {
        $mail = new Mail();
        $mail->setBody($this->getBody($id));
        $mail->setAttachments($this->retrieveAttachments($id));
        return $mail;
    }

    /**
     * Return all unread mails
     *
     * @return null|Collection
     */
    public function getAllMails()
    {
        foreach($this->getCountUnreadMails() as $mail)
        {
            $this->get
        }
    }

    /**
     * Get attachments from email
     *
     * @param string $id   email identifier
     * @param string $path path to email
     *
     * @return Collection
     */
    protected function retrieveAttachments($id, $path = null)
    {
        $attachments = new Collection();

        $structure = imap_fetchstructure($this->mailbox, $id);

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
                    $attachments->addItem($message);
                }
            }
        }

        return $attachments;
    }

    /**
     * Get email message body
     *
     * @param string $id email identifier
     *
     * @return string
     */
    public function getBody($id)
    {
        $body = $this->get_part($this->mailbox, $id, "TEXT/HTML");
        // fallback to plain text
        if ($body == "") {
            $body = $this->get_part($this->mailbox, $id, "TEXT/PLAIN");
        }

        return $body;
    }

    /**
     * Connect to mailbox
     */
    public function connect()
    {
        $this->mailbox = imap_open($this->server, $this->username, $this->password);
        if (!$this->mailbox) {
            throw new \RuntimeException("Could not connect to mailserver!");
        }
    }

    /**
     * Retrieve headers
     *
     * @param $id
     *
     * @return object
     */
    protected function getHeaders($id) // Get Header info
    {
        $mail_header    = imap_header($this->mailbox, $id);
        $sender         = $mail_header->from[ 0 ];
        $sender_replyto = $mail_header->reply_to[ 0 ];
        if (strtolower($sender->mailbox) != 'mailer-daemon' && strtolower($sender->mailbox) != 'postmaster') {
            $mail_details = array(
                'from'      => strtolower($sender->mailbox) . '@' . $sender->host,
                'fromName'  => $sender->personal,
                'toOth'     => strtolower($sender_replyto->mailbox) . '@' . $sender_replyto->host,
                'toNameOth' => $sender_replyto->personal,
                'subject'   => $mail_header->subject,
                'to'        => strtolower($mail_header->toaddress)
            );
            // more details must be added
            $mail_details2 = array(
                'to'      => $mail_header->toaddress,
                'from'    => $mail_header->fromaddress,
                'reply'   => $mail_header->reply_toaddress,
                'subject' => $mail_header->subject,
                'udate'   => $mail_header->udate,
                'unseen'  => $mail_header->Unseen,
                'size'    => $mail_header->Size
            );
        }

        return $mail_header;
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