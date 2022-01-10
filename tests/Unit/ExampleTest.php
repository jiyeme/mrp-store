<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {

        $url = "mrpApp/网络时钟_6471e51e64e5d0780844b9601bfc61e7.mrp?e=1641824246&token=:=";
        $options = array(
            'http' => array(
                  'method' => 'HEAD',
                  'follow_location' => 0,
                  'header' => [
                    'referer: http://',
                    'host: '
                  ]
             )
        );

        $context = stream_context_create($options);
        $ret = get_headers($url, true, $context);
        print_r($ret);


        $options['http']['method'] = 'GET';
        $context = stream_context_create($options);
        echo file_get_contents($url, false, $context);
    }
}
