<?php
include "vendor/autoload.php";

$requester = new \Xtodx\Rozetka\RozetkaRequest("rozetka login", "rozetka password", __DIR__ . "/rozetka.txt", __DIR__ . "/cookie.txt");

//$login = $requester->login();
$orders = $requester->request('orders/search');

print_r($orders);
?>