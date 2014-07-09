<?php
/**
 * AbstractMailTransport.php
 * PHP version 5
 *
 * @category Mail
 * @package  SimpleMailReceiver\Commons
 * @author   jlcardosa <joseluis.cardosamanzano@maviance.com>
 * @license  maviance GmbH 2014
 * @version  SVN: $Id$
 * @link     AbstractMailTransport
 */
namespace SimpleMailReceiver\Commons;

/**
 * Define kind of mail transport
 *
 * @category Mail
 * @package  SimpleMailReceiver\Commons
 * @author   jlcardosa <joseluis.cardosamanzano@maviance.com>
 * @license  maviance GmbH 2014
 * @version  Release: 1.01
 * @link     AbstractMailTransport
 * @abstract
 */
abstract class AbstractMailTransport
{

    /**
     * The mail server used in the request
     *
     * @var string
     */
    protected $mailserver;

    /**
     * The number of the port
     *
     * @var integer
     */
    protected $port;

    /**
     * The folder to access
     *
     * @var string
     */
    protected $folder;

    /**
     * SSL activated or not
     *
     * @var bool
     */
    protected $ssl;

    public function __construct()
    {
        $this->folder = "INBOX";
        $this->ssl = false;
    }

    /**
     * Get the mail server
     *
     * @return the string
     */
    public function getMailserver()
    {
        return $this->mailserver;
    }

    /**
     * Set the mail server
     *
     * @param string $mailserver The mail server
     *
     * @return AbstractMailTransport
     */
    public function setMailserver($mailserver)
    {
        $this->mailserver = $mailserver;
        return $this;
    }

    /**
     * Get the port
     *
     * @return the integer
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set the port
     *
     * @param int $port the port
     *
     * @throws \InvalidArgumentException
     * @return AbstractMailTransport
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }


    /**
     * Set the folder
     *
     * @param string $folder
     * @return AbstractMailTransport
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
        return $this;
    }

    /**
     * Get the folder
     *
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Set SSL true or false
     *
     * @param boolean $ssl
     * @return $this
     */
    public function setSsl($ssl)
    {
        $this->ssl = $ssl;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSsl()
    {
        return $this->ssl;
    }
}
