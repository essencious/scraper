<?php
require_once __DIR__ . '/../bootstrap.php';

use SainsCrawler\Scraper;

$scraper = new Scraper();
echo $scraper->scrapeToJson();

?>