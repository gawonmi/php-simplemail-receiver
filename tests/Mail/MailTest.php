<?php
require_once 'PHPUnit/Autoload.php';

use SimpleMailReceiver\Mail\Mail;
use SimpleMailReceiver\Mail\Attachment;
use SimpleMailReceiver\Commons\Collection;

class MailTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Mail
     */
    private $mail;

    /**
     * @var Collection
     */
    private $mailHeader;

    /**
     * @var string
     */
    private $mailBody;

    /**
     * @var Collection
     */
    private $mailAttachments;

    public function setUp()
    {
        $this->mailHeader      = new Collection(array(
            'toaddress'       => 'mavianceTest@gmail.com',
            'fromaddress'     => 'Jose Luis Cardosa Manzano <jlcardosa@gmail.com>',
            'reply_toaddress' => 'Jose Luis Cardosa Manzano <jlcardosa@gmail.com>',
            'subject'         => 'Pruuueba',
            'Unseen'          => 'U',
            'udate'           => '19586575518',
            'Size'            => '1024'
        ));
        $this->mailBody        = 'Body with attachmentes';
        $this->mailAttachments = new Collection(array(
            new Attachment('prueba1', 'Content of attachment1', 'txt', '1024'),
            new Attachment('prueba2', 'Content of attachment2', 'txt', '2048'),
            new Attachment('prueba3', 'Content of attachment3', 'txt', '4096')
        ));
        $this->mail            = new Mail($this->mailHeader, $this->mailBody, $this->mailAttachments);
    }

    public function testGetterAndSetter()
    {
        $this->mail->setMailHeader($this->mailHeader);
        $this->mail->setBody($this->mailBody);
        $this->mail->setAttachments($this->mailAttachments);
        $this->assertEquals($this->mail->getMailHeader(), $this->mailHeader);
        $this->assertEquals($this->mail->getBody(), $this->mailBody);
        $this->assertEquals($this->mail->getAttachments(), $this->mailAttachments);
    }

    public function testSearch()
    {
        $this->assertTrue($this->mail->search('Jose Luis Cardosa'));
        $this->assertTrue($this->mail->search('Pruuueba'));
        $this->assertTrue($this->mail->search('Body with att'));
        $this->assertTrue($this->mail->search('prueba1.txt'));
        $this->assertTrue($this->mail->search('txt'));
        $this->assertTrue($this->mail->search('Not found ') == false);
    }

    public function testToArray()
    {
        $this->assertEquals(
            $this->mail->toArray(),
            array(
                "header"     => $this->mailHeader,
                "body"       => $this->mailBody,
                "attachment" => $this->mailAttachments
            )
        );
    }
}
