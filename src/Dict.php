<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 9-6-2015
 * Time: 19:49
 */

namespace Devristo\Torrent;


class Dict extends Item
{
    protected $value=[];
    protected $expectKey=true;
    protected $key;

    public function push($value){
        if($this->expectKey){
            $this->key = $value;
        } else {
            if($value instanceof Item){
                $this->value[$this->key] = &$value->getValue();
            }else {
                $this->value[$this->key] = $value;
            }

            $this->key=null;
        }

        $this->expectKey = !$this->expectKey;
    }

    public function isFull(){
        return false;
    }

    public function &getValue(){
        return $this->value;
    }
}