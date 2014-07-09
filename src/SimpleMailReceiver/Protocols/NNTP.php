<?php
/**
 * @package 
 * @subpackage 
 * @author Michael Nowag<michael.nowag@maviance.com>
 * @copyright maviance GmbH 2014 
 */

namespace SimpleMailReceiver\Protocols;


use SimpleMailReceiver\Commons\AbstractMailTransport;
use SimpleMailReceiver\Exceptions\SimpleMailReceiverException;

class NNTP extends AbstractMailTransport implements ProtocolInterface
{

    /**
     * Create the string for connection and connect to the mail Server
     *
     * @param string $username
     * @param string $password
     * @throws SimpleMailReceiverException
     * @return resource
     */
    function connect($username, $password)
    {
        try
        {
            $this->ssl = (($this->ssl == false) ? "/novalidate-cert" : "/ssl");
            $string = "{" . $this->mailserver . ":" . $this->port . "/nntp" . $this->ssl ."}" . $this->folder;
            return imap_open($string, $username, $password);
        }catch (\Exception $e)
        {
            throw new SimpleMailReceiverException("Error trying to set a connection by NNTP! " . $e->getMessage());
        }
    }
} 