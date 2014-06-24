<?php
/**
 * @package
 * @subpackage
 * @author    Michael Nowag<michael.nowag@maviance.com>
 * @copyright maviance GmbH 2014
 */

namespace SimpleMailReceiver;

use SimpleMailReceiver\Commons\Collection;
use SimpleMailReceiver\Protocol\ProtocolFactory;

class Receiver
{

    final public function getConfig($key = false)
    {
        return $key ? $this->config[ $key ] : $this->config;
    }

    final public function setConfig($config)
    {
        if ($config instanceof Collection) {
            $this->config = $config;
        } elseif (is_array($config)) {
            $this->config = new Collection($config);
        } else {
            throw new \InvalidArgumentException('Config must be an array or Collection');
        }

        return $this;
    }

    /**
     * Constructor
     *
     * @param array|Collection $configs The config of the app
     */
    public function __construct($config = null)
    {
        // is imap installed? Fail early!
        if (!in_array('imap', get_loaded_extensions())) {
            trigger_error(
                "It looks like you do not have imap installed.\n" .
                "IMAP is required to make request to the mail servers using the " .
                EmailConnectorConstants::USER_AGENT . " \n" . "library. For install instructions,
                visit the following page:\n" . "http://php.net/manual/en/imap.installation.php",
                E_USER_WARNING
            );
        }
        $this->setConfig($config ? : new Collection());
        $factory      = new ProtocolFactory();
        $this->mailer = $factory->createVehicle($this->getConfig('protocol'));
    }

    public function connect()
    {
        $this->mailer->connect();

        if ($servertype == 'imap') {
            if ($port == '') {
                $port = '143';
            }
            $strConnect = '{' . $mailserver . ':' . $port . '/imap/ssl}INBOX';
        } else {
            $strConnect = '{' . $mailserver . ':' . $port . '/pop3}INBOX';
        }
        $this->server   = $strConnect;
        $this->username = $username;
        $this->password = $password;
        $this->email    = $EmailAddress;
    }

    public function close()
    {
        $this->mailer->close();
    }

} 