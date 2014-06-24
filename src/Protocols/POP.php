<?php
/**
 * @package
 * @subpackage
 * @author    Michael Nowag<michael.nowag@maviance.com>
 * @copyright maviance GmbH 2014
 */

namespace SimpleMailReceiver\Protocol;

class POP implements ProtocolInterface
{
    function connect($host,$port,$user,$pass,$folder="INBOX",$ssl=false)
    {
        $ssl=($ssl==false)?"/novalidate-cert":"";
        return (imap_open("{"."$host:$port/pop3$ssl"."}$folder",$user,$pass));
    }
} 