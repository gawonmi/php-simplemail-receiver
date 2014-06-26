<?php
/**
 * @package 
 * @subpackage 
 * @author Michael Nowag<michael.nowag@maviance.com>
 * @copyright maviance GmbH 2014 
 */

namespace SimpleMailReceiver\Protocol;


use SimpleMailReceiver\Commons\AbstractMailTransport;

class IMAP extends AbstractMailTransport implements ProtocolInterface {

    /**
     * Create the string for connection and connect to the mail Server
     *
     * @param string $username
     * @param string $password
     * @return resource
     */
    function connect($username, $password)
    {
        $this->ssl = (($this->ssl == false) ? "/novalidate-cert" : "/ssl");
        $string = "{" . $this->mailserver . ":" . $this->port . "/imap" . $this->ssl ."}" . $this->folder;
        return imap_open($string, $username, $password);
    }
}
