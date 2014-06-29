<?php
require_once 'PHPUnit/Autoload.php';

use SimpleMailReceiver\Commons\Mailserver;

class MailServerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Mailserver
     */
    private $mailer;

    protected function setUp()
    {
        $imap_res = imap_open('{imap.gmail.com:993/imap/ssl}INBOX','username','password');
        $this->mailer = new Mailserver($imap_res);
    }

    public function testGetSizeMailBox()
    {
        $this->assertGreaterThan(0, $this->mailer->countAllMails());
        $this->mailer->close();
    }

    public function testGetSizeMailBoxUnread()
    {
        $this->assertEquals(0, $this->mailer->countUnreadMails());
    }

    public function testGetHeader()
    {
        $header = $this->mailer->retrieveHeaders(4);
        $this->assertEquals($header->getSubject(), 'Test');
    }

    public function testGetBody()
    {
        $body = $this->mailer->retrieveBody(4);
        $this->assertContains('Body Test', $body);
    }

    public function testGetAttachments()
    {
        $attachments = $this->mailer->retrieveAttachments(4);
        $this->assertEquals($attachments->getItem(0)->getName(), 'test1');
        $this->assertEquals($attachments->getItem(1)->getName(), 'test2');
        $this->assertEquals($attachments->getItem(2)->getName(), 'test3');
    }

    public function testGetMail()
    {
        $mail = $this->mailer->retrieveMail(4);
        $header = $mail->getMailHeader();
        $this->assertEquals($header->getSubject(), 'Test');
        $this->assertContains('Body Test', $mail->getBody());
        $attachments = $mail->getAttachments();
        $this->assertEquals($attachments->getItem(0)->getName(), 'test1');
        $this->assertEquals($attachments->getItem(1)->getName(), 'test2');
        $this->assertEquals($attachments->getItem(2)->getName(), 'test3');
    }
}
