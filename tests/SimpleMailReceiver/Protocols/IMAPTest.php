<?php

require_once 'PHPUnit/Autoload.php';

use SimpleMailReceiver\Protocols\IMAP;
use SimpleMailReceiver\Protocols\POP;
use SimpleMailReceiver\Protocols\ProtocolInterface;
use SimpleMailReceiver\Commons\Collection;

class IMAPTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ProtocolInterface
     *
     */
    private $config;

    /**
     * @var ProtocolInterface
     */
    private $protocol;

    protected function setUp()
    {

        $this->config = new Collection(array(
            'username' => 'maviancetest@gmail.com',
            'password' => 'Mav1234567'
        ));
    }

    public function testIMAP()
    {
        $this->protocol = new IMAP();
        $this->protocol->setMailserver('imap.gmail.com')
            ->setPort(993)
            ->setFolder('INBOX')
            ->setSsl(true);
        $this->assertNotNull($this->protocol->connect($this->config->getItem('username'), $this->config->getItem('password')));
    }

    public function testPOP()
    {
        $this->protocol = new POP();
        $this->protocol->setMailserver('pop.gmail.com')
            ->setPort(995)
            ->setFolder('INBOX')
            ->setSsl(true);
        $this->assertNotNull($this->protocol->connect($this->config->getItem('username'), $this->config->getItem('password')));
    }

    public function testNNTP()
    {
        //TODO
    }
}
