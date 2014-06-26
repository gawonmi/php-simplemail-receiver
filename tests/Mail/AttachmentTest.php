<?php

require_once 'PHPUnit/Autoload.php';

use SimpleMailReceiver\Mail\Attachment;

class AttachmentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Attachment
     */
    private $mailAttachment;

    public function setUp()
    {
        $this->mailAttachment = new Attachment('prueba', 'Content of attachment', 'txt', '1024');
    }

    public function testGetterAndSetter()
    {
        $this->mailAttachment->setName('prueba2');
        $this->mailAttachment->setContent('Content of attachment 2');
        $this->mailAttachment->setExtension('txt');
        $this->mailAttachment->setSize('2048');
        $this->assertEquals($this->mailAttachment->getName(), 'prueba2');
        $this->assertEquals($this->mailAttachment->getContent(), 'Content of attachment 2');
        $this->assertEquals($this->mailAttachment->getExtension(), 'txt');
        $this->assertEquals($this->mailAttachment->getSize(), '2048');
    }

    public function testSearchFilename()
    {
        $this->assertTrue($this->mailAttachment->searchFilename('pru'));
        $this->assertTrue($this->mailAttachment->searchFilename('txt'));
        $this->assertTrue($this->mailAttachment->searchFilename('Not found ') == false);
    }

    public function testSearchContent()
    {
        $this->assertTrue($this->mailAttachment->searchContent('Content'));
        $this->assertTrue($this->mailAttachment->searchContent('Content of '));
        $this->assertTrue($this->mailAttachment->searchContent('Not found ') == false);
    }

    public function testToArray()
    {
        $this->assertEquals(
            $this->mailAttachment->toArray(),
            array(
                "name"      => 'prueba',
                "extension" => 'txt',
                "content"   => 'Content of attachment',
                "size"      => '1024'
            )
        );
    }

    public function testToString()
    {
        $this->assertEquals(
            $this->mailAttachment->__toString(),
            'prueba.txt'
        );
    }
}
