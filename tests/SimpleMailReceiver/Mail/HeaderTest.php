<?php

require_once 'PHPUnit/Autoload.php';

use SimpleMailReceiver\Mail\Headers;

class HeaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Headers
     */
    private $mailHeader;

    public function setUp()
    {
        $mailHeader = array(
            'msgno'   => '125545d4f5d',
            'to'      => 'mavianceTest@gmail.com',
            'from'    => 'Jose Luis Cardosa Manzano <jlcardosa@gmail.com>',
            'reply'   => 'Jose Luis Cardosa Manzano <jlcardosa@gmail.com>',
            'subject' => 'EEE',
            'udate'   => '19586575518',
            'unseen'  => 'U',
            'size'    => '0'
        );
        $this->mailHeader = new Headers($mailHeader);
        $this->mailHeader->setCc('mavianceTest@gmail.com');
        $this->mailHeader->setCco('mavianceTest@gmail.com');
        $this->mailHeader->setSubject('Pruuueba');
        $this->mailHeader->setDate(new \DateTime('@19586575518'));
        $this->mailHeader->setSize('1024');
    }

    public function testSearchFilename()
    {
        $this->assertTrue($this->mailHeader->search('Jose Luis Cardosa'));
        $this->assertTrue($this->mailHeader->search('Not found ') == false);
    }

    public function testToArray()
    {
        $this->assertEquals(
            $this->mailHeader->toArray(),
            array(
                'msgno'   => $this->mailHeader->getMsgNo(),
                'to'      => $this->mailHeader->getTo(),
                'cc'      => $this->mailHeader->getCc(),
                'cco'     => $this->mailHeader->getCco(),
                'from'    => $this->mailHeader->getFrom(),
                'reply'   => $this->mailHeader->getReply(),
                'subject' => $this->mailHeader->getSubject(),
                'unseen'  => $this->mailHeader->getUnseen(),
                'date'    => $this->mailHeader->getDate(),
                'size'    => $this->mailHeader->getSize()
            )
        );
    }
}
