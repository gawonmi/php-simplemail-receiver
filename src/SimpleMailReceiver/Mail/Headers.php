<?php
/**
 * Headers.php
 *
 * PHP version 5
 *
 * @category Mail
 * @package  SimpleMailReceiver\Mail
 * @author   jlcardosa <joseluis.cardosamanzano@maviance.com>
 * @license  maviance GmbH 2014
 * @version  SVN: $Id$
 * @link     Headers
 */
namespace SimpleMailReceiver\Mail;


/**
 * Class to handle the content of a eMail Header
 *
 * @category Mail
 * @package  SimpleMailReceiver\Mail
 * @author   jlcardosa <joseluis.cardosamanzano@maviance.com>
 * @license  maviance GmbH 2014
 * @version  Release: 1.01
 * @link     Headers
 *
 */
class Headers
{
    /**
     * Number of msg
     *
     * @var string
     */
    private $msgNo;

    /**
     * Where the mail has been sent
     *
     * @var string
     */
    private $to;

    /**
     * Where the mail has been sent with copy
     *
     * @var string
     */
    private $cc;

    /**
     * Where the mail has been sent with copy hidden
     *
     * @var string
     */
    private $cco;

    /**
     * From where the mail has been sent
     *
     * @var string
     */
    private $from;

    /**
     * From where the mail has been replied
     *
     * @var string
     */
    private $reply;

    /**
     * The subject of the mail
     *
     * @var string
     */
    private $subject;

    /**
     * The mail has been seen or not
     *
     * @var string
     */
    private $unseen;

    /**
     * Date of the mail
     *
     * @var \DateTime
     */
    private $date;

    /**
     * Size of the mail
     *
     * @var string
     */
    private $size;

    /**
     * Construct by default
     *
     * @param array $header_mail Generic class that come for the
     *                               representation of an array
     */
    public function __construct(array $header_mail)
    {
        $this->setMsgNo($header_mail['msgno']);
        $this->setTo($header_mail['to']);
        $this->setFrom($header_mail['from']);
        $this->setReply($header_mail['reply']);
        $this->subject = $header_mail['subject'];
        $this->setUnseen($header_mail['unseen']);
        $this->date = new \DateTime('@' . $header_mail['udate']);
        $this->size = $header_mail['size'];
    }

    /*********************/
    /* Getter and Setter */
    /*********************/

    /**
     * @param string $mso
     * @return $this
     */
    public function setMsgNo($mso)
    {
        $this->msgNo = $mso;
        return $this;
    }

    /**
     * @return string
     */
    public function getMsgNo()
    {
        return $this->msgNo;
    }

    /**
     * Get To
     *
     * @return the array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set the To
     *
     * @param array $to The To
     *
     * @return Headers
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Get the CC
     *
     * @return the array
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * Set the CC
     *
     * @param array $cc The CC
     *
     * @return Headers
     */
    public function setCc($cc)
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * Get the CCO
     *
     * @return the array The CCO
     */
    public function getCco()
    {
        return $this->cc;
    }

    /**
     * Set the CCO
     *
     * @param array $cco The cco
     *
     * @return Headers
     */
    public function setCco($cco)
    {
        $this->cco = $cco;
        return $this;
    }

    /**
     * Get the FROM
     *
     * @return the MailAddress
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set the FROM
     *
     * @param string|array $from The from
     *
     * @return Headers
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Get the REPLAY
     *
     * @return the array
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * Set the REPLAY
     *
     * @param array $reply The reply
     *
     * @return Headers
     */
    public function setReply($reply)
    {
        $this->reply = $reply;
        return $this;
    }

    /**
     * Get the SUBJECT
     *
     * @return the string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set the SUBJECT
     *
     * @param string $subject The subject
     *
     * @return Headers
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Get if it is UNREAD
     *
     * @return the boolean
     */
    public function getUnseen()
    {
        return $this->unseen;
    }

    /**
     * Set if it is UNREAD
     *
     * @param string $unseen The unseen parameter
     *
     * @return Headers
     */
    public function setUnseen($unseen)
    {
        $this->unseen = $unseen;
        return $this;
    }

    /**
     * Get the DATE
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the DATE
     *
     * @param \DateTime $date The date
     *
     * @return Headers
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get the SIZE
     *
     * @return the string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the SIZE
     *
     * @param int $size The size
     *
     * @return Headers
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Search in the header throught a pattern
     *
     * @param string $pattern The patter to search
     *
     * @return boolean
     */
    public function search($pattern)
    {
        if (is_int(strpos($this->__toString(), $pattern)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Convert the objet to an array
     *
     * @return multitype:multitype: string boolean DateTime
     */
    public function toArray()
    {
        return array(
            'msgno'   => $this->msgNo,
            'to'      => $this->to,
            'cc'      => $this->cc,
            'cco'     => $this->cco,
            'from'    => $this->from,
            'reply'   => $this->reply,
            'subject' => $this->subject,
            'unseen'  => $this->unseen,
            'date'    => $this->date,
            'size'    => $this->size
        );
    }

    /**
     * Convert the object to a string
     *
     * @return string
     */
    public function __toString()
    {
        $string = "";
        $string .= "To: $this->to";
        $string .= "\nFrom: $this->from";
        $string .= "\nReply: $this->reply";
        $string .= "\nSubject: $this->subject";
        $string .= "\nUnseen: $this->unseen";
        $string .= "\nDate: " . $this->date->format(\DateTime::RFC1123);
        return $string;
    }
}
