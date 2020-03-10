<?php
include "vendor/autoload.php";

$requester = new \Xtodx\Rozetka\RozetkaRequest("AgrusRozetka", "5zt00g9xdwrl", __DIR__ . "/rozetka.txt", __DIR__ . "/cookie.txt");

//$login = $requester->login();
$orders = $requester->request('orders/search');

print_r($orders);
?>