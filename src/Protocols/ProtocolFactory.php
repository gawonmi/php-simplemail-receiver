<?php
/**
 * @package
 * @subpackage
 * @author    Michael Nowag<michael.nowag@maviance.com>
 * @copyright maviance GmbH 2014
 */

namespace SimpleMailReceiver\Protocol;

class ProtocolFactory
{

    /**
     * @var array
     */
    protected $protocolList;

    /**
     * Setup the different protocols
     */
    public function __construct()
    {
        $this->protocolList = array(
            'imap' => __NAMESPACE__ . '\IMAP',
            'pop3' => __NAMESPACE__ . '\POP',
            'nntp' => __NAMESPACE__ . '\NNTP',
        );
    }

    /**
     * Creates the instances
     *
     * @param $protocol
     *
     * @throws \InvalidArgumentException
     * @return ProtocolInterface a new instance of ProtocolInterface
     */
    public function create($protocol)
    {
        if (!array_key_exists($protocol, $this->$protocolList)) {
            throw new \InvalidArgumentException("$protocol is not valid protocol");
        }
        $className = $this->$protocolList[ $protocol ];

        return new $className();
    }
} 