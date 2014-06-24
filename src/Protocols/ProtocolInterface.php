<?php
/**
 * @package 
 * @subpackage 
 * @author Michael Nowag<michael.nowag@maviance.com>
 * @copyright maviance GmbH 2014 
 */

namespace SimpleMailReceiver\Protocol;


interface ProtocolInterface {

    public function connect();
    public function close();
    public function delete($id);

    /**
     * @param $id
     *
     * @return Mail
     */
    public function get($id);
}