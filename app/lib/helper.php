<?php
namespace SainsCrawler;

class Helper {

  /*
   * Removes any currency code or extra wording from the
   * product price. Returns only the number that represents the
   * unit price
   *
   * @param $text - the text to be formatted
   * @return unit price
   */
  public function formatUnitPrice($text) {

    $price = trim($text);

    $price = str_replace('&pound', '', $price);
    $price = str_replace('/unit', '', $price);

    return $price;

  }

  /*
   * Formats a byte into kb. Divide by 1024 and add kb
   *
   * @param $bytes - the byte string to be formatted
   * @return <string> kb
   */
  public function formatBytesKb($bytes) {
    $kb = number_format(($bytes / 1024), 2);

    return $kb.'kb';
  }
}
?>