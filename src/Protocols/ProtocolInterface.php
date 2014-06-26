<?php
/**
 * @package 
 * @subpackage 
 * @author Michael Nowag<michael.nowag@maviance.com>
 * @copyright maviance GmbH 2014 
 */

namespace SimpleMailReceiver\Protocol;


interface ProtocolInterface {

    /**
     * Create the string for connection and connect to the mail Server
     *
     * @param $username
     * @param $password
     * @return resource
     */
    public function connect($username, $password);
}