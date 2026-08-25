<?php

require_once __DIR__ . '/vendor/autoload.php';

try {

    $client = new MongoDB\Client("mongodb://localhost:27017");

    $db = $client->streetlight_db;

    $db->test->insertOne([
        "name" => "Test Student",
        "message" => "MongoDB connection is working"
    ]);

    echo "<h2>MongoDB Test Successful!</h2>";

} catch (Exception $e) {

    echo "<h2>MongoDB Test Failed</h2>";
    echo $e->getMessage();

}

?>