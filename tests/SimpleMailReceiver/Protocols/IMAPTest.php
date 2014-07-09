<?php

require_once 'PHPUnit/Autoload.php';

use SimpleMailReceiver\Protocols\IMAP;
use SimpleMailReceiver\Protocols\POP;
use SimpleMailReceiver\Protocols\NNTP;
use SimpleMailReceiver\Protocols\ProtocolInterface;
use SimpleMailReceiver\Commons\Collection;
use Symfony\Component\Yaml\Yaml;

class IMAPTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Collection
     *
     */
    private $config;

    /**
     * @var ProtocolInterface
     */
    private $protocol;

    protected function setUp()
    {
        $yaml         = new Yaml();
        $config = $yaml->parse(file_get_contents('tests/SimpleMailReceiver/test_config.yml'));//tests/SimpleMailReceiver/test_config.yml
        $this->config = new Collection(array(
            'username' => $config['username'],
            'password' => $config['password']
        ));
    }

    public function testIMAP()
    {
        $this->protocol = new IMAP();
        $this->protocol->setMailserver('imap.gmail.com')
            ->setPort(993)
            ->setFolder('INBOX')
            ->setSsl(true);
        $this->assertNotNull($this->protocol->connect($this->config->get('username'), $this->config->get('password')));
    }

    public function testPOP()
    {
        $this->protocol = new POP();
        $this->protocol->setMailserver('pop.gmail.com')
            ->setPort(995)
            ->setFolder('INBOX')
            ->setSsl(true);
        $this->assertNotNull($this->protocol->connect($this->config->get('username'), $this->config->get('password')));
    }

    /**
     * @expectedException SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @expectedExceptionMessage Error trying to set a connection by POP3!
     */
    public function testPOPException()
    {
        $this->protocol = new POP();
        $this->protocol->setMailserver('nowork.gmail.com')
            ->setPort(995)
            ->setFolder('INBOX')
            ->setSsl(true);
        $this->protocol->connect($this->config->get('username'), $this->config->get('password'));
    }

    /**
     * @expectedException SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @expectedExceptionMessage Error trying to set a connection by IMAP!
     */
    public function testIMAPException()
    {
        $this->protocol = new IMAP();
        $this->protocol->setMailserver('nowork.gmail.com')
            ->setPort(995)
            ->setFolder('INBOX')
            ->setSsl(true);
        $this->protocol->connect($this->config->get('username'), $this->config->get('password'));
    }

    /**
     * @expectedException SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @expectedExceptionMessage Error trying to set a connection by NNTP!
     */
    public function testNNTPException()
    {
        $this->protocol = new NNTP();
        $this->protocol->setMailserver('pop.gmail.com')
            ->setPort(995)
            ->setFolder('INBOX')
            ->setSsl(true);
        $this->protocol->connect($this->config->get('username'), $this->config->get('password'));
    }

    public function testGetterAndSetter()
    {
        $this->protocol = new IMAP();
        $this->protocol->setMailserver('imap.gmail.com')
            ->setPort(993)
            ->setFolder('INBOX')
            ->setSsl(true);
        $this->assertEquals('INBOX', $this->protocol->getFolder());
        $this->assertEquals(993, $this->protocol->getPort());
        $this->assertEquals('imap.gmail.com', $this->protocol->getMailserver());
        $this->assertTrue($this->protocol->isSsl());
    }
}
