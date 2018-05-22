<?php

require_once '../vendor/autoload.php';

use Asaas\Asaas;
use Asaas\Customer;

try {
    $token = 'c4d5f059e6995fd6e3a15c92ac7fda02470e4d2971ba7242f947d32409f58c4b';
    $asaas = new Asaas($token,'sandbox');

    $customers = Customer::asaas($asaas)->all();

    echo "<h1>Customers</h1>";
    echo "<ul>";
    foreach ($customers['data'] as $customer) {
        echo "<li>$customer->id / $customer->name / $customer->email / $customer->mobilePhone</li>";
    }
    echo "</ul>";

} catch (Exception $e) {
    echo "<pre>";
    var_dump($e);
    echo "<pre>";
}