<?php

/*
 *
 *  This will allow you to filter out the combinations you specifically want
 *  Note: This is updated every hour by executium.
 *
 */


## Keyword Match
$filter=''; // example: bitfinex

##/ How many to show per page
$per_page=100;

##/ Page Number
$page=1;

##/ Get JSON Data
$data=json_decode(file_get_contents('https://marketdata.executium.com/api/v2/public/spreads-list?limit='.$per_page.'&pagenumber='.$page.'&spread_includes='.$keyword));

##/ Output
echo '<pre>';
print_r($data);
echo '</pre>';
