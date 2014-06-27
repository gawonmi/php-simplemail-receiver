<?php

require_once 'PHPUnit/Autoload.php';

use SimpleMailReceiver\Receiver;
use SimpleMailReceiver\Commons\Collection;

class ReceiverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Receiver
     */
    private $receiver;

    /**
     * @var array
     */
    private $config;

    protected function setUp()
    {
        $this->config = new Collection(array(
            'username' => 'username',
            'password' => 'password',
            'host' => 'imap.gmail.com',
            'port' => 993,
            'protocol' => 'imap',
            'folder' => 'INBOX',
            'ssl' => true
        ));
        $this->receiver = new Receiver($this->config);
        $this->receiver->connect();
    }

    public function testGetMailById()
    {
        $mail = $this->receiver->getMail(1);
        $this->assertContains('prueba', $mail->getBody());
        $this->receiver->close();
    }

    public function testGetAllMails()
    {
        $mails = $this->receiver->getMails();
        $this->assertEquals(7, iterator_count($mails));
        $this->receiver->close();
    }

    public function testSizeMailBox()
    {
        $this->assertEquals(7, $this->receiver->countAllMails());
        $this->receiver->close();
    }

    public function testCountUnreadBox()
    {
        $this->assertEquals(0, $this->receiver->countUnreadMails());
        $this->receiver->close();
    }
}
