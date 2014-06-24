<?php
/**
 * AbstractMailTransport.php
 * PHP version 5
 *
 * @category Mail
 * @package  EmailConnector\mailTransport
 * @author   jlcardosa <joseluis.cardosamanzano@maviance.com>
 * @license  maviance GmbH 2014
 * @version  SVN: $Id$
 * @link     AbstractMailTransport
 */
namespace EmailConnector\mailTransport;

/**
 * Define kind of mail transport
 *
 * @category Mail
 * @package  EmailConnector\mailTransport
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
     * The protocol used
     *
     * @var string
     */
    protected $servertype;

    /**
     * The number of the port
     *
     * @var integer
     */
    protected $port;

    /**
     * Constructor of the class
     *
     * @param string  $mailserver The mail server
     * @param integer $port       The port
     */
    public function __construct($mailserver, $port)
    {
        $this->setMailserver($mailserver);
        $this->setPort($port);
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
     * @return \EmailConnector\mailTransport\AbstractMailTransport
     */
    public function setMailserver($mailserver)
    {
        $this->mailserver = $mailserver;

        return $this;
    }

    /**
     * Get the type of server
     *
     * @return the string
     */
    public function getServertype()
    {
        return $this->servertype;
    }

    /**
     * Set the type of server
     *
     * @param string $servertype The server type
     *
     * @return \EmailConnector\mailTransport\AbstractMailTransport
     */
    public function setServertype($servertype)
    {
        $this->servertype = $servertype;

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
     * @return \EmailConnector\mailTransport\AbstractMailTransport
     */
    public function setPort($port)
    {
        if (!is_numeric($port)) {
            throw new \InvalidArgumentException("Port must be a number: " . $port);
        }
        $this->port = $port;

        return $this;
    }
}
