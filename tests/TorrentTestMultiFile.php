<?php
use Devristo\Torrent\Torrent;

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 5-12-13
 * Time: 19:49
 */

class TorrentTestMultiFile extends PHPUnit_Framework_TestCase {
    /**
     * @var Torrent
     */
    protected $torrent;

    public function setUp(){
        $this->torrent = Torrent::fromFile('multifile.torrent');
    }

    public function test_file(){
        $this->assertEquals('2010-08-10 - Krass 360° in Frankfurt (MULTICAM BD).mkv', $this->torrent->getFiles()[0]->getPath());
        $this->assertEquals(12262451114, $this->torrent->getFiles()[0]->getSize());
        $this->assertEquals(null, $this->torrent->getFiles()[0]->getMd5Sum());

        $this->assertEquals('info.txt', $this->torrent->getFiles()[1]->getPath());
        $this->assertEquals(1660, $this->torrent->getFiles()[1]->getSize());
        $this->assertEquals(null, $this->torrent->getFiles()[1]->getMd5Sum());
    }

    public function test_private(){
        $this->assertEquals(true, $this->torrent->isPrivate());
        $this->assertEquals("618b40bddf786d3af341f2bb00e441b355ecc953", $this->torrent->getInfoHash(false));
        $this->torrent->setPrivate(false);
        $this->assertEquals("d25e9ed17d4481488aac880bf5349413f76dbb67", $this->torrent->getInfoHash(false));
    }

    public function test_main_details(){
        $this->assertEquals('http://tracker.u2start.com/132/ffffffffffffffffffffffffffffffffffffffff/announce/', $this->torrent->getAnnounce());
        $this->assertEquals(array(
            array("udp://tracker.u2start.com:8080/132/ffffffffffffffffffffffffffffffffffffffff/announce/"),
            array("http://tracker.u2start.com/132/ffffffffffffffffffffffffffffffffffffffff/announce/")
        ), $this->torrent->getAnnounceList());


        $this->assertEquals(12262452774, $this->torrent->getSize());
        $this->assertEquals("Powered By:\nu2start.com - Share Your Passion", $this->torrent->getComment());
        $this->assertEquals("618b40bddf786d3af341f2bb00e441b355ecc953", $this->torrent->getInfoHash(false));
        $this->assertEquals(hex2bin("618b40bddf786d3af341f2bb00e441b355ecc953"), $this->torrent->getInfoHash());
    }
}
 