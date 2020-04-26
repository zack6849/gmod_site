<?php


namespace App\Helpers;


use Illuminate\Support\Arr;

class KeyValueReader
{
    private $content;
    private $convert;

    public function __construct($content, $convert = false)
    {
        $this->content = $content;
        $this->convert = $convert;
    }


    public function parse()
    {

        $json = $this->content;
        //encapsulate in braces
        $json = "{\n$json\n}";

        //replace open braces
        $pattern = '/"([^"]*)"(\s*){/';
        $replace = '"${1}": {';
        $json = preg_replace($pattern, $replace, $json);

        //replace values
        $pattern = '/"([^"]*)"\s*"([^"]*)"/';
        $replace = '"${1}": "${2}",';
        $json = preg_replace($pattern, $replace, $json);

        //remove trailing commas
        $pattern = '/,(\s*[}\]])/';
        $replace = '${1}';
        $json = preg_replace($pattern, $replace, $json);

        //add commas
        $pattern = '/([}\]])(\s*)("[^"]*":\s*)?([{\[])/';
        $replace = '${1},${2}${3}${4}';
        $json = preg_replace($pattern, $replace, $json);

        $pattern = "/^\s+(".+")$\n\s+}/";
        $replace = '{$1},';
        $json = preg_replace($pattern, $replace, $json);

        //object as value
        $pattern = '/}(\s*"[^"]*":)/';
        $replace = '},${1}';
        $json = preg_replace($pattern, $replace, $json);


        return $json;
    }
}
