<?php

require_once 'PHPUnit/Autoload.php';

use SimpleMailReceiver\Receiver;

class ReceiverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Receiver
     */
    private $receiver;

    /**
     * @var array
     */
    private $configs;

    protected function setUp()
    {
        $this->config = new Collection(array(
            'username' => 'maviancetest@gmail.com',
            'password' => 'Mav1234567',
            'mailServer' => 'imap.google.com',
            'port' => 993,
            'protocol' => 'IMAP',
            'folder' => 'INBOX',
            'ssl' => true
        ));
        $this->receiver = new Receiver($this->configs);
        $this->receiver->connect();
    }

    protected function tearDown()
    {
        $this->mailer->close();
    }

    public function testGetMailById()
    {
        $mail = $this->receiver->getMail(1);
        $this->assertContains('prueba', $mail->getBody());
    }

    public function testGetAllMails()
    {
        $mails = $this->receiver->getMails();
        $this->assertEquals(7, iterator_count($mails));
    }

    public function testSizeMailBox()
    {
        $this->assertEquals(7, $this->receiver->countAllMails());
    }

    public function testCountUnreadBox()
    {
        $this->assertEquals(7, $this->receiver->countUnreadMails());
    }
}
