<?php

namespace SainsCrawler\Tests;

use SainsCrawler\Scraper;

class ScraperTest extends \PHPUnit_Framework_TestCase
{

  /*
   * scrapeToJson should return a valid json string
   */
  public function testShouldReturnJsonString() {
    $result = array(
          'result' => array(
                        '0' => array(
                                    'title'       => 'Apricot Ripe & Ready x5',
                                    'unit_price'  => '3.50',
                                    'description' => 'Apricots',
                                    'size'        => '14.76kb'
                                ),
                        '1' => array(
                                    'title'       => 'Avocado Ripe & Ready XL Loose 300g',
                                    'unit_price'  => '1.50',
                                    'description' => 'Avocados',
                                    'size'        => '14.80kb'
                                )

                      ),
          'total'  => 5.00
    );

    $mock = $this->getMockBuilder('SainsCrawler\Scraper')
                 ->setMethods(array('parserList'))
                 ->getMock();

    $mock->method('parserList')
         ->willReturn($result);

    $this->assertJson($mock->scrapeToJson());
  }

  /*
   * Should return an array with the product list and the total unit price
   */
  public function testShouldReturnProductList()
  {
    $mockReturn = array(
      '0' => array(
                  'title'       => 'Apricot Ripe & Ready x5',
                  'unit_price'  => '3.50',
                  'description' => 'Apricots',
                  'size'        => '14.76kb'
              ),
      '1' => array(
                  'title'       => 'Avocado Ripe & Ready XL Loose 300g',
                  'unit_price'  => '1.50',
                  'description' => 'Avocados',
                  'size'        => '14.80kb'
              )

    );

    $mock = $this->getMockBuilder('SainsCrawler\Scraper')
                 ->setMethods(array('getItems'))
                 ->getMock();

    $mock->method('getItems')
         ->willReturn($mockReturn);

    $expectedReturn = array(
                          'result' => $mockReturn,
                          'total'  => 5.00
    );

    $this->assertEquals($expectedReturn, $mock->parserList());

  }

  /*
   * An array with the product title and unit price should be returned
   */
  public function testShouldReturnFormattedProductInfo() {
    $mockNode = $this->getMockBuilder('Symfony\Component\DomCrawler\Crawler')
                        ->getMock();

    $mockNode->expects($this->any())
             ->method('filter')
             ->will($this->returnSelf());

    $mockNode->expects($this->any())
             ->method('first')
             ->will($this->returnSelf());

    $mockNode->expects($this->any())
             ->method('text')
             ->will($this->onConsecutiveCalls('Strawberry 500g', '&pound5.60/unit') );

    $expectedResult = array(
                        'title'     => 'Strawberry 500g',
                        'unit_price'=> '5.60'
    );

    $scraper = new Scraper();
    $this->assertEquals($expectedResult, $scraper->formatProductInfo($mockNode));
  }

  /*
   * An array of product description and page size should be returned
   */
  public function testShouldReturnFormattedProductDetails() {
    $class = 'Symfony\Component\DomCrawler\Crawler';
    $mockCrawler = $this->getMockBuilder($class)
                        ->getMock();

    $mockCrawler->method('filter')
                ->will($this->returnSelf());
    $mockCrawler->method('text')
                ->willReturn('Delicious summer strawberries');

    $expectedResult = array(
                        'description' => 'Delicious summer strawberries',
                        'size'        => '55.00kb'
    );

    $scraper = new Scraper();
    $this->assertEquals($expectedResult, $scraper->formatProductDetails($mockCrawler, 56320));

  }
}

?>