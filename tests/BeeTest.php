<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 5-12-13
 * Time: 18:02
 */

require(__DIR__."/../vendor/autoload.php");

class BeeTest extends PHPUnit_Framework_TestCase {
    public function test_encode_string(){
        $input = "hello world";
        $expected = strlen($input).":$input";

        $bee = new \Devristo\Torrent\Bee();
        $result = $bee->encode($input);

        $this->assertEquals($expected, $result);
    }

    public function test_encode_int(){
        $input = "4154548412";
        $expected = "i{$input}e";

        $bee = new \Devristo\Torrent\Bee();
        $result = $bee->encode($input);

        $this->assertEquals($expected, $result);
    }

    public function test_decode_int(){
        $expected = 41545482;
        $input = "i{$expected}e";

        $bee = new \Devristo\Torrent\Bee();
        $result = $bee->decode($input);

        $this->assertEquals($expected, $result);
    }

    public function test_encode_list(){
        $input = array(1,2,3,4);
        $expected = "li1ei2ei3ei4ee";

        $bee = new \Devristo\Torrent\Bee();
        $result = $bee->encode($input);

        $this->assertEquals($expected, $result);
    }

    public function test_decode_list(){
        $expected = array(1,2,3,4);
        $input = "li1ei2ei3ei4ee";

        $bee = new \Devristo\Torrent\Bee();
        $result = $bee->decode($input);

        $this->assertEquals($expected, $result);
    }

    public function test_decode_string(){
        $expected = "hello world";
        $input = strlen($expected).":$expected";

        $bee = new \Devristo\Torrent\Bee();
        $result = $bee->decode($input);

        $this->assertEquals($expected, $result);
    }

    public function test_decode_dict_strings_1(){
        $input = "d3:cow3:moo4:spam4:eggse";
        $expected = array(
            'cow' =>  'moo',
            'spam' =>  'eggs',
        );

        $bee = new \Devristo\Torrent\Bee();
        $result = $bee->decode($input);

        $this->assertEquals($expected, $result);
    }

    public function test_decode_dict_list(){
        $input = "d4:spaml1:a1:bee";
        $expected = array(
            'spam' =>  array('a', 'b')
        );

        $bee = new \Devristo\Torrent\Bee();
        $result = $bee->decode($input);

        $this->assertEquals($expected, $result);
    }

    public function test_decode_dict_strings_2(){
        $input = "d9:publisher3:bob17:publisher-webpage15:www.example.com18:publisher.location4:homee";
        $expected = array(
            "publisher" => "bob", "publisher-webpage" => "www.example.com", "publisher.location" => "home"
        );

        $bee = new \Devristo\Torrent\Bee();
        $result = $bee->decode($input);

        $this->assertEquals($expected, $result);
    }
}
 