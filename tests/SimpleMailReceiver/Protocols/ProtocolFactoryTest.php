<?php

use SimpleMailReceiver\Protocols\ProtocolFactory;
use SimpleMailReceiver\Protocols\ProtocolInterface;

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
        $this->protocol = new ProtocolFactory();
        $this->assertEquals('SimpleMailReceiver\Protocols\IMAP', get_class($this->protocol->create('imap')));
    }

    public function testPop3WithFactory()
    {
        $this->protocol = new ProtocolFactory();
        $this->assertEquals('SimpleMailReceiver\Protocols\POP', get_class($this->protocol->create('pop3')));
    }

    public function testNntpWithFactory()
    {
        $this->protocol = new ProtocolFactory();
        $this->assertEquals('SimpleMailReceiver\Protocols\NNTP', get_class($this->protocol->create('nntp')));
    }
}
