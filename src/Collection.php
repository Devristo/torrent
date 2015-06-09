<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 9-6-2015
 * Time: 19:49
 */

namespace Devristo\Torrent;


class Collection extends Item
{
    protected $value=[];

    public function push($value){
        if($value instanceof Item){
            $this->value[] = &$value->getValue();
        }else {
            $this->value[] = $value;
        }
    }

    public function isFull(){
        return false;
    }

    public function &getValue(){
        return $this->value;
    }
}