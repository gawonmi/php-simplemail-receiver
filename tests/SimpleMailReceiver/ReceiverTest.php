<?php

require_once 'PHPUnit/Autoload.php';

use SimpleMailReceiver\Receiver;
use SimpleMailReceiver\Commons\Collection;
use Symfony\Component\Yaml\Yaml;

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
        $yaml         = new Yaml();
        $config = $yaml->parse(file_get_contents('tests/SimpleMailReceiver/test_config.yml'));//tests/SimpleMailReceiver/test_config.yml
        $this->config = new Collection(array(
            'username' => $config['username'],
            'password' => $config['password'],
            'host' => $config['host'],
            'port' => $config['port'],
            'protocol' => $config['protocol'],
            'folder' => $config['folder'],
            'ssl' => $config['ssl']
        ));
        $this->receiver = new Receiver($this->config);
        $this->receiver->connect();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Config must be an array or Collection
     */
    public function testSetConfig()
    {
        $arr = array(
            'username' => 'username',
            'password' => 'password',
            'host'     => 'host'
        );
        $this->receiver->setConfig($arr);
        $this->receiver->setConfig(5699);
    }

    public function testPing()
    {
        $this->assertTrue($this->receiver->ping());
        $this->receiver->close();
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
        $this->assertEquals(8, iterator_count($mails));
        $this->receiver->close();
    }

    public function testSizeMailBox()
    {
        $this->assertEquals(8, $this->receiver->countAllMails());
        $this->receiver->close();
    }

    public function testCountUnreadBox()
    {
        $this->assertEquals(0, $this->receiver->countUnreadMails());
        $this->receiver->close();
    }

    public function testSearchMails()
    {
        $this->assertEquals(array(5,6,7), $this->receiver->searchMails('FROM "jlcardosa@gmail.com"'));
        $this->receiver->close();
    }

    public function testDeleteMail()
    {
        $this->receiver->deleteMail(15);
        $this->receiver->close();
    }


}
