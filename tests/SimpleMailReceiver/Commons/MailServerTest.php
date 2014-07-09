<?php
require_once 'PHPUnit/Autoload.php';

use SimpleMailReceiver\Commons\Mailserver;
use Symfony\Component\Yaml\Yaml;

class MailServerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Mailserver
     */
    private $mailer;

    protected function setUp()
    {
        $yaml         = new Yaml();
        $config = $yaml->parse(file_get_contents('tests/SimpleMailReceiver/test_config.yml'));//tests/SimpleMailReceiver/test_config.yml
        $imap_res = imap_open('{'.$config['host'].':'.$config['port'].'/imap/ssl}INBOX',$config['username'],$config['password']);
        $this->mailer = new Mailserver($imap_res, new \SimpleMailReceiver\Exceptions\ExceptionThrower());
    }

    public function testPing()
    {
        $this->assertTrue($this->mailer->ping());
        $this->mailer->close();
    }

    public function testGetSizeMailBox()
    {
        $this->assertGreaterThan(0, $this->mailer->countAllMails());
        $this->mailer->close();
    }

    public function testGetSizeMailBoxUnread()
    {
        $this->assertEquals(0, $this->mailer->countUnreadMails());
        $this->mailer->close();
    }

    public function testGetHeader()
    {
        $header = $this->mailer->retrieveHeaders(4);
        $this->assertEquals($header->getSubject(), 'Test');
        $this->mailer->close();
    }


    public function testGetBody()
    {
        $body = $this->mailer->retrieveBody(4);
        $this->assertContains('Body Test', $body);
        $this->mailer->close();
    }

    public function testGetAttachments()
    {
        $attachments = $this->mailer->retrieveAttachments(4);
        $this->assertEquals($attachments->get(0)->getName(), 'test1');
        $this->assertEquals($attachments->get(1)->getName(), 'test2');
        $this->assertEquals($attachments->get(2)->getName(), 'test3');
        $this->mailer->close();
    }

    public function testSearchMails()
    {
        $string = 'ALL';
        $mails = $this->mailer->searchMails($string);
        $this->assertEquals(8, sizeof($mails));
        $this->mailer->close();
    }

    public function testSearchMails2()
    {
        $string = 'SUBJECT "Test"';
        $mails = $this->mailer->searchMails($string);
        $this->assertEquals(2, sizeof($mails));
        $this->mailer->close();
    }

    public function testGetMail()
    {
        $mail = $this->mailer->retrieveMail(4);
        $header = $mail->getMailHeader();
        $this->assertEquals($header->getSubject(), 'Test');
        $this->assertContains('Body Test', $mail->getBody());
        $attachments = $mail->getAttachments();
        $this->assertEquals($attachments->get(0)->getName(), 'test1');
        $this->assertEquals($attachments->get(1)->getName(), 'test2');
        $this->assertEquals($attachments->get(2)->getName(), 'test3');
        $this->mailer->close();
    }

    /**
     * @group tests
     */

    /**
     * @expectedException SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @expectedExceptionMessage Error retrieving header no
     */
    public function testGetHeaderException()
    {
        $this->mailer->retrieveHeaders(15);
    }

    /**
     * @expectedException SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @expectedExceptionMessage Error retrieving body no
     */
    public function testGetBodyException()
    {
        $this->mailer->retrieveBody(15);
    }

    /**
     * @expectedException SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @expectedExceptionMessage Error retrieving attachments no
     */
    public function testGetAttachmentException()
    {
        $this->mailer->retrieveAttachments(15);
    }

    /**
     * @expectedException SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @expectedExceptionMessage Error getting the number of unread mails
     */
    public function testGetUnreadMailsException()
    {
        $this->mailer = new Mailserver(null, new \SimpleMailReceiver\Exceptions\ExceptionThrower());
        $this->mailer->countUnreadMails();
    }

    /**
     * @expectedException SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @expectedExceptionMessage Error in the search
     */
    public function testSearchException()
    {
        $this->mailer = new Mailserver(null, new \SimpleMailReceiver\Exceptions\ExceptionThrower());
        $this->mailer->searchMails('bad string');
    }

    /**
     * @expectedException SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @expectedExceptionMessage Error getting the number of mails
     */
    public function testCountAllMailsException()
    {
        $this->mailer = new Mailserver(null, new \SimpleMailReceiver\Exceptions\ExceptionThrower());
        $this->mailer->countAllMails();
    }

    /**
     * @expectedException SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @expectedExceptionMessage Error deleting
     */
    public function testDeleteException()
    {
        $this->mailer = new Mailserver(null, new \SimpleMailReceiver\Exceptions\ExceptionThrower());
        $this->mailer->delete(15);
    }

    /**
     * @expectedException SimpleMailReceiver\Exceptions\SimpleMailReceiverException
     * @expectedExceptionMessage Error closing
     */
    public function testCloseException()
    {
        $this->mailer = new Mailserver(null, new \SimpleMailReceiver\Exceptions\ExceptionThrower());
        $this->mailer->close();
    }
}
