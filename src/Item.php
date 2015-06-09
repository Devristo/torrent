<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 9-6-2015
 * Time: 19:47
 */

namespace Devristo\Torrent;


class Item
{
    protected $value;
    protected $isSet=false;

    public function push($value){
        $this->isSet = true;

        if($value instanceof Item){
            $this->value = &$value->getValue();
        } else {
            $this->value = &$value;
        }
    }

    public function isFull(){
        return $this->isSet;
    }

    public function &getValue(){
        return $this->value;
    }
}