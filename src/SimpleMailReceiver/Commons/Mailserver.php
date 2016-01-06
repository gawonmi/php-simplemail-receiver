<?php
/**
 * @package
 * @subpackage
 * @author    Michael Nowag <michael.nowag@maviance.com>
 * @copyright maviance GmbH 2014
 */
namespace SimpleMailReceiver\Commons;

use SimpleMailReceiver\Exceptions\SimpleMailReceiverException;
use SimpleMailReceiver\Mail\Attachment;
use SimpleMailReceiver\Mail\Mail;
use SimpleMailReceiver\Mail\Headers;
use SimpleMailReceiver\Exceptions\ExceptionThrower;

/**
 * Common class that handle with all the protocols to realize options with the mail server
 *
 * @category SimpleMailReceiver
 * @package  SimpleMailReceiver\Commons
 * @author   Jose Luis Cardosa <joseluis.cardosamanzano@maviance.com>
 * @license  maviance GmbH 2014
 * @version  Release: dev-master
 * @link     Mailserver
 *
 */
class Mailserver
{

    /**
     * @var resource $imap_stream
     */
    protected $mailbox;

    /**
     * @var ExceptionThrower
     */
    protected $exceptionThrower;

    /**
     * Constructor of the class
     *
     * @param resource $imap_res The resource to use in the imap functions.
     * @param ExceptionThrower $eT
     */
    public function __construct($imap_res, $eT)
    {
        $this->mailbox = $imap_res;
        $this->exceptionThrower = $eT;
    }

    /**
     * Ping the connection
     *
     * @return bool
     */
    public function ping()
    {
        //init the catching
        $this->exceptionThrower->start();
        $success = imap_ping($this->mailbox);
        $this->exceptionThrower->stop();
        return $success;
    }

    /**
     * Return selected mail
     *
     * @param string $id email identifier
     *
     * @return null|Mail
     */
    public function retrieveMail($id)
    {
        $mail = new Mail();
        $mail->setMailHeader($this->retrieveHeaders($id));
        $mail->setBody($this->retrieveBody($id));
        $mail->setAttachments($this->retrieveAttachments($id));
        return $mail;
    }

    /**
     * Return all unread mails
     *
     * @return Collection
     */
    public function retrieveAllMails()
    {
        $mails = new Collection();
        for($i = 1; $i <= $this->countAllMails(); $i++)
        {
            $mails->add($this->retrieveMail($i));
        }
        return $mails;
    }

    /**
     * Retrieve headers
     *
     * @param $id
     *
     * @throws \SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @return Headers
     */
    public function retrieveHeaders($id) // Get Header info
    {
        try
        {
            //init the catching
            $this->exceptionThrower->start();
            $mail_header    = imap_header($this->mailbox, $id);
            $this->exceptionThrower->stop();

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
                $header = new Headers($data_header);
            }
            return $header;
        }catch (\Exception $e)
        {
            throw new SimpleMailReceiverException("Error retrieving header no: ". $id . " ." . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get email message body
     *
     * @param string $id email identifier
     *
     * @throws \SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @return string
     */
    public function retrieveBody($id)
    {
        try
        {
            //init the catching
            $this->exceptionThrower->start();
            $body = $this->get_part($this->mailbox, $id, "TEXT/HTML");
            // fallback to plain text
            if ($body == "") {
                $body = $this->get_part($this->mailbox, $id, "TEXT/PLAIN");
            }
            $this->exceptionThrower->stop();
            return $body;
        }catch (\Exception $e)
        {
            throw new SimpleMailReceiverException("Error retrieving body no: ". $id . " ." . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get attachments from email
     *
     * @param string $id email identifier
     *
     * @throws \SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @return Collection
     */
    public function retrieveAttachments($id)
    {
        try
        {
            $attachments = new Collection();
            //init the catching
            $this->exceptionThrower->start();
            $structure   = imap_fetchstructure($this->mailbox, $id);
            if(property_exists($structure,'parts'))
            {
                foreach ($structure->parts as $key => $value) {
                    $encoding = $structure->parts[ $key ]->encoding;
                    if ($structure->parts[ $key ]->ifdparameters) {
                        $param_merge = array_merge(
                            $structure->parts[ $key ]->dparameters,
                            $structure->parts[ $key ]->parameters
                        );
                        foreach ($param_merge as $param) {
                            if (
                                $param->attribute == 'FILENAME' ||
                                $param->attribute == 'NAME'
                            ) {
                                $name = $param->value;
                                break;
                            }
                        }

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
                        $this->exceptionThrower->stop();
                        $name_ext = pathinfo($name);
                        $attachment = new Attachment($name_ext[ 'filename' ], $message, $name_ext[ 'extension' ], sizeof($message));
                        $attachments->add($attachment);
                    }
                }
            }
            return $attachments;
        }catch (\Exception $e)
        {
            throw new SimpleMailReceiverException("Error retrieving attachments no: ". $id . " ." . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get total Number of unread emails
     *
     * @throws \SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @return int
     */
    public function countUnreadMails()
    {
        try
        {
            //init the catching
            $this->exceptionThrower->start();
            $info = imap_mailboxmsginfo($this->mailbox);
            $this->exceptionThrower->stop();
            return ( property_exists($info,'unread') ? (int) $info->unread : null);
        }catch (\Exception $e)
        {
            throw new SimpleMailReceiverException("Error getting the number of unread mails" . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Search mails by a determinate string according to imap_search
     *
     * @param $string
     * @return array
     * @throws \SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     */
    public function searchMails($string)
    {
        try
        {
            //init the catching
            $this->exceptionThrower->start();
            $search = imap_search($this->mailbox, $string);
            $this->exceptionThrower->stop();
            return $search;
        }catch (\Exception $e)
        {
            throw new SimpleMailReceiverException("Error in the search" . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get total number of all emails
     *
     * @throws \SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @return int
     */
    public function countAllMails()
    {
        try{
            //init the catching
            $this->exceptionThrower->start();
            $count = imap_num_msg($this->mailbox);
            $this->exceptionThrower->stop();
            return $count;
        }catch (\Exception $e)
        {
            throw new SimpleMailReceiverException("Error getting the number of mails" . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete selected mail by mail id
     *
     * @param string $id email identifier
     *
     * @throws \SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function delete($id)
    {
        try
        {
            //init the catching
            $this->exceptionThrower->start();
            $success = imap_delete($this->mailbox, $id);
            $this->exceptionThrower->stop();
            return $success;
        }catch (\Exception $e)
        {
            throw new SimpleMailReceiverException("Error deleting the mails no ". $id . " ." . $e->getMessage(), $e->getCode());
        }

    }

    /**
     * Close mailbox
     *
     * @throws \SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function close()
    {
        try{
            //init the catching
            $this->exceptionThrower->start();
            $success = imap_close($this->mailbox, CL_EXPUNGE);
            $this->exceptionThrower->stop();
            return $success;
        }catch (\Exception $e)
        {
            throw new SimpleMailReceiverException("Error closing the connection" . $e->getMessage(), $e->getCode());
        }
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
            if ($mime_type == $this->extractMimetype($structure)) {
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
