<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 5-12-13
 * Time: 18:22
 */

namespace Devristo\Torrent;

class Torrent {
    private $data;

    /**
     * @var File[]
     */
    private $files;

    protected function __construct($data){
        $this->data = $data;

        if(!$this->isValid())
            throw new \InvalidArgumentException("Invalid torrent data structure");

        if(!array_key_exists('files', $this->data['info'])){
            $this->files = array(new File($this->data['info']));
        } else {
            $this->files = array();

            foreach($this->data['info']['files'] as &$data)
                $this->files[] = new File($data);
        }
    }

    public function getInfoHash($rawOutput=true){
        $bee = new Bee();
        return sha1($bee->encode($this->data['info']), $rawOutput);
    }

    protected function isValid(){
        $hasKeys = function(array $keys, array $data){
            return count(array_diff($keys, array_keys($data))) === 0;
        };

        if(!$hasKeys(array('info', 'announce'), $this->data))
            return false;

        if(!$hasKeys(array('piece length', 'pieces'), $this->data['info']))
            return false;

        return true;
    }

    public function getAnnounce(){
        return $this->data['announce'];
    }

    public function setAnnounce($url){
        $this->data['announce'] = $url;
    }

    public function setAnnounceList(array $urls){
        foreach($urls as $url)
            if(!is_array($url))
                throw new \InvalidArgumentException("Announce list should be an array of arrays");


        $this->data['announce-list'] = $urls;
    }

    public function getAnnounceList(){
        return array_key_exists('announce-list', $this->data) ? $this->data['announce-list'] : array();
    }

    public function getCreationDate(){
        if(!array_key_exists('creation date', $this->data))
            return null;

        $dt = new \DateTime();
        $dt->setTimestamp($this->data['creation date']);

        return $dt;
    }

    public function getComment(){
        if(!array_key_exists('comment', $this->data))
            return null;

        return $this->data['comment'];
    }

    public function setComment($comment){
        $this->data['comment'] = $comment;
    }

    public function getCreatedBy(){
        if(!array_key_exists('created by', $this->data))
            return null;

        return $this->data['created by'];
    }

    public function getName(){
        return $this->data['info']['name'];
    }

    public function setName($name){
        $this->data['info']['name'] = $name;
    }

    public function setPrivate($val){
        $this->data['info']['private'] = $val ? 1 : 0;
    }

    public function getNumPieces(){
        return ceil($this->getSize() / $this->getPieceSize());
    }

    public function getPieces(){
        return str_split($this->data['info']['pieces'], 20);
    }

    public function getPieceSize(){
        return $this->data['info']['piece length'];
    }

    public function isPrivate(){
        return array_key_exists('private', $this->data['info']);
    }

    public function getSize(){
        $length = 0;
        foreach($this->files as $file)
            $length += $file->getSize();

        return $length;
    }

    public function getFiles(){
        return $this->files;
    }

    public function toArray(){
        return $this->data;
    }

    public function serialize(){
        $bee = new Bee();
        return $bee->encode($this->data);
    }

    public static function fromFile($filename){
        $contents = file_get_contents($filename);

        $bee = new Bee();
        $decoded = $bee->decode($contents);
        $torrent = new Torrent($decoded);

        return $torrent;
    }
} 