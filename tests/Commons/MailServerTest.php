<?php
require_once 'PHPUnit/Autoload.php';

use SimpleMailReceiver\Commons\MailServer;;

class MailTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var MailServer
     */
    private $mailer;

    protected function setUp()
    {
        $imap_res = imap_header('{imap.google.com:993/imap/ssl}','maviancetest@gmail.com','Mav1234567');
        $this->mailer = new MailServer($imap_res);
    }

    protected function tearDown()
    {
        $this->mailer->close();
    }

    public function testGetSizeMailBox()
    {
        $this->assertGreaterThan(0, $this->mailer->countAllMails());
    }

    public function testGetSizeMailBoxUnread()
    {
        $this->assertEquals(0, $this->mailer->countUnreadMails());
    }

    public function testGetHeader()
    {
        $header = $this->mailer->retriveHeaders(4);
        $this->assertEquals($header['subject'], 'Test');
    }

    public function testGetBody()
    {
        $body = $this->mailer->retriveBody(4);
        $this->assertContains('Body Test', $body);
    }

    public function testGetAttachments()
    {
        $attachments = $this->mailer->retrieveAttachments(4);
        $this->assertEquals($attachments[ 0 ]->getName(), 'test1');
        $this->assertEquals($attachments[ 1 ]->getName(), 'test2');
        $this->assertEquals($attachments[ 2 ]->getName(), 'test3');
    }

    public function testGetMail()
    {
        $mail = $this->mailer->retriveMail(4);
        $header = $mail->getMailHeader();
        $this->assertEquals($header['subject'], 'Test');
        $this->assertContains('Body Test', $mail->getBody());
        $attachments = $mail->getAttachments();
        $this->assertEquals($attachments[ 0 ]->getName(), 'test1');
        $this->assertEquals($attachments[ 1 ]->getName(), 'test2');
        $this->assertEquals($attachments[ 2 ]->getName(), 'test3');
    }
}
