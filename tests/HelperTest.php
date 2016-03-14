<?php

namespace SainsCrawler\Tests;

use SainsCrawler\Helper;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    /*
     * formatUnitPrice should strip away the pound sym and per unit string
     * and return a number with two decimal places
     */
    public function testShouldFormatUnitPrice()
    {
        $helper = new Helper();
        $result = $helper->formatUnitPrice('&pound3.56/unit');

        $this->assertEquals($result, '3.56');
    }

    /*
     * formatBytesKb should convert bytes into kbs and add "kb" to the end
     */
    public function testShouldFormaBytesToKB()
    {
        $helper = new Helper();
        $result = $helper->formatBytesKb(68608);

        $this->assertEquals($result, '67.00kb');
    }
}

?>