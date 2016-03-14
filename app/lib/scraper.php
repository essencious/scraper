<?php

namespace SainsCrawler;

use Goutte\Client;
use SainsCrawler\Helper;

class Scraper {

  private $client;
  private $url = 'http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/5_products.html';
  private $helper;

  public function __construct() {
    $this->client = new Client();
    $this->helper = new Helper();
  }

  /*
   * Takes a parsered list and converts to pretty JSON
   *
   * @return <string> pretty json string of the product list
   */
  public function scrapeToJson() {
    $items = $this->parserList();
    return json_encode($items, JSON_PRETTY_PRINT);
  }

  /*
   * Crawls a given URL
   *
   * @return <object> the object from the crawler
   */
  private function crawlURL() {
    $crawler = $this->client->request('GET', $this->url);

    return $crawler;
  }

  /*
   * Parsers shopping list and adds the total price before
   * returning in a new array
   *
   * @return <array> result of from the web crawler and total unit price
   */
  public function parserList() {
    $result = $this->getItems();

    $total = 0;
    foreach ($result as $item) {
      $total += $item['unit_price'];
    }

    return array(
                'result' => $result,
                'total'  => $total
              );
  }

  /*
   * Gets all the product list from the web page that was crawled
   *
   * @return result of the closure which formats the product list into an array
   */
  public function getItems() {
    $crawler = $this->crawlURL();
    $result = array();

    return $crawler->filter('.productLister > li')
                   ->each($this->parseItems($result));
  }

  /*
   * Parses and format each of the item in the product list
   *
   * @return <array> relevent data from the web crawl
   */
  public function parseItems($result) {

    return function ($node) use ($result) {
      // extract the product information from the node
      $product = $this->formatProductInfo($node);

      // get link to product page
      $link = $node->selectLink($product['title'])->link();
      // get the crawler of the link
      $crawler = $this->client->click($link);
      // get the size of the html in bytes
      $size = strlen($crawler->html());
      // format the product details
      $details = $this->formatProductDetails($crawler, $size);

      // merge the product and product details array together
      $item = array_merge($product, $details);

      return $item;
    };

  }

  /*
   * Gets all the product list from the web page that was crawled
   *
   * @param <object> crawler object of a product
   * @return <array> title and unit price of a product
   */
  public function formatProductInfo($node) {
    $item = array(
                'title' => trim($node->filter('h3')->text()),
                'unit_price' => $this->helper->formatUnitPrice($node->filter('.pricePerUnit')->first()->text())
    );

    return $item;
  }

  /*
   * Gets all the product list from the web page that was crawled
   *
   * @param <object> crawler object
   * @param <float> the size of the page in bytes
   * @return <array> title and unit price of a product
   */
  public function formatProductDetails($crawler, $size) {
    $item = array(
              'description' => trim($crawler->filter('.productText')->text()),
              'size'        => $this->helper->formatBytesKb($size)
    );

    return $item;

  }

}

?>