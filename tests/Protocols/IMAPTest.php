<?php

require_once 'PHPUnit/Autoload.php';

use SimpleMailReceiver\Protocol\IMAP;
use SimpleMailReceiver\Protocol\POP;
use SimpleMailReceiver\Protocol\ProtocolInterface;
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
        $this->protocol->setMailserver('imap.google.com')
            ->setPort(993)
            ->setFolder('INBOX')
            ->setSsl(true);
        $this->assertNotNull($this->protocol->connect($this->config['username'], $this->config['password']));
    }

    public function testPOP()
    {
        $this->protocol = new POP();
        $this->protocol->setMailserver('pop.google.com')
            ->setPort(995)
            ->setFolder('INBOX')
            ->setSsl(false);
        $this->assertNotNull($this->protocol->connect($this->config['username'], $this->config['password']));
    }

    public function testNNTP()
    {
        //TODO
    }
}
