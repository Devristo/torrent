<?php
use Devristo\Torrent\Torrent;

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 5-12-13
 * Time: 19:36
 */

class TorrentTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Torrent
     */
    protected $torrent;

    public function test_without_announce(){
        $torrent = Torrent::fromFile(__DIR__.'/1992-06-15.torrent');
        $this->assertNull($torrent->getAnnounce());
    }

    public function test_file(){
        $this->torrent = Torrent::fromFile(__DIR__.'/ubuntu-13.10-desktop-amd64.iso.torrent');

        $this->assertEquals('ubuntu-13.10-desktop-amd64.iso', $this->torrent->getFiles()[0]->getPath());
        $this->assertEquals(925892608, $this->torrent->getFiles()[0]->getSize());
        $this->assertEquals(null, $this->torrent->getFiles()[0]->getMd5Sum());
    }

    public function test_main_details(){
        $this->torrent = Torrent::fromFile(__DIR__.'/ubuntu-13.10-desktop-amd64.iso.torrent');

        $this->assertEquals('http://torrent.ubuntu.com:6969/announce', $this->torrent->getAnnounce());
        $this->assertEquals(array(
                array('http://torrent.ubuntu.com:6969/announce'),
                array('http://ipv6.torrent.ubuntu.com:6969/announce')
            ), $this->torrent->getAnnounceList());


        $this->assertEquals(925892608, $this->torrent->getSize());
        $this->assertEquals("Ubuntu CD releases.ubuntu.com", $this->torrent->getComment());
        $this->assertEquals("e3811b9539cacff680e418124272177c47477157", $this->torrent->getInfoHash(false));
        $this->assertEquals(hex2bin("e3811b9539cacff680e418124272177c47477157"), $this->torrent->getInfoHash());
    }
}
 