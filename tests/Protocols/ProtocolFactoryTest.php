<?php

use SimpleMailReceiver\Protocol\ProtocolFactory;
use SimpleMailReceiver\Protocol\ProtocolInterface;

require_once 'PHPUnit/Autoload.php';

class ProtocolFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ProtocolInterface
     */
    private $protocol;

    protected function setUp()
    {

    }

    public function testImapWithFactory()
    {
        $this->protocol = new ProtocolFactory('imap');
        $this->assertEquals('IMAP', get_class($this->protocol));
    }

    public function testPop3WithFactory()
    {
        $this->protocol = new ProtocolFactory('pop3');
        $this->assertEquals('POP', get_class($this->protocol));
    }

    public function testNntpWithFactory()
    {
        $this->protocol = new ProtocolFactory('nntp');
        $this->assertEquals('NNTP', get_class($this->protocol));
    }
}
