<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 5-12-13
 * Time: 19:09
 */

namespace Devristo\Torrent;


class File {
    private $data;
    public function __construct(&$data){
        $this->data = $data;

        if(!array_key_exists('length', $data) || !array_key_exists('name', $data) &&! array_key_exists('path', $data))
            throw new \InvalidArgumentException("Invalid file data structure");
    }

    public function getPath(){
        if(array_key_exists('path', $this->data)){
            return join("/", $this->data['path']);
        } else {
            return $this->data['name'];
        }
    }

    public function getSize(){
        return $this->data['length'];
    }

    public function getMd5Sum(){
        return array_key_exists('md5sum', $this->data) ? $this->data['md5sum'] : null;
    }
} 