<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 5-12-13
 * Time: 17:20
 */

namespace Devristo\Torrent;

class Bee {

    private function encode_string($string){
        return sprintf("%d:%s", strlen($string), $string);
    }

    private function encode_int($int){
        return 'i'.$int.'e';
    }

    private function encode_list(array $list){
        $encoded = 'l';

        foreach($list as $item)
            $encoded .= $this->encode($item);

        $encoded .= 'e';

        return $encoded;
    }

    private function encode_dict(array $dict){
        $encoded = 'd';

        ksort($dict, SORT_STRING);

        foreach($dict as $key => $value){
            $encoded .= $this->encode_string($key);
            $encoded .= $this->encode($value);
        }

        $encoded .= 'e';
        return $encoded;
    }

    private function is_list(array $arr){
        for (reset($arr); is_int(key($arr)); next($arr));
        return is_null(key($arr));
    }

    private function is_dict(array $arr){
        for (reset($arr); is_int(key($arr)) || is_string(key($arr)); next($arr));
        return is_null(key($arr));
    }

    /**
     * @param $object
     * @return string
     */
    public function encode($object){
        if(is_int($object) || ctype_digit($object))
            return $this->encode_int($object);
        elseif(is_string($object))
            return $this->encode_string($object);
        elseif(is_array($object))
            if($this->is_list($object))
                return $this->encode_list($object);
            elseif($this->is_dict($object))
                return $this->encode_dict($object);
            else throw new \InvalidArgumentException("Input is not valid");
        else throw new \InvalidArgumentException("Input is not valid");
    }

    public function decode($string){
        $pos = 0;

        $root = new Item();
        $stack = [$root];

        while($pos < strlen($string)) {
            $item = $stack[count($stack) -1];

            if($item->isFull()){
                array_pop($stack);
            }

            if(count($stack) == 0)
                break;

            switch ($string[$pos]) {
                case 'i':
                    $posEnd = stripos($string, 'e', $pos);
                    $int = (int)substr($string, $pos + 1, $posEnd - $pos - 1);
                    $item->push($int);

                    $pos = $posEnd + 1;
                    break;
                case 'l':
                    $collection = new Collection();
                    $item->push($collection);
                    $stack[] = $collection;
                    $pos++;
                    break;

                case 'd':
                    $dict = new Dict();
                    $item->push($dict);
                    $stack[] = $dict;
                    $pos++;
                    break;

                case 'e':
                    array_pop($stack);
                    $stack[count($stack)-1];

                    $pos++;
                    break;

                default:
                    $posDoubleColon = stripos($string, ':', $pos);
                    if($posDoubleColon === false)
                        throw new \InvalidArgumentException("Invalid string format");

                    $length = (int)substr($string, $pos, $posDoubleColon-$pos);

                    if($length + $posDoubleColon >= strlen($string)){
                        throw new \InvalidArgumentException("Invalid string format");
                    }

                    $pos = $posDoubleColon + 1 + $length;
                    $value = substr($string, $posDoubleColon + 1, $length);
                    $item->push($value);
            }


        }

        return $root->getValue();
    }
} 