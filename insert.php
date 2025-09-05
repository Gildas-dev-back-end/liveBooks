<?php 
    require 'connection.php';
    
    $collection = $database->selectCollection('users');

    $insert = $collection->insertOne([
        'name' => 'jack',
        'age' => '20',
        'gender' => 'male',
    ]);

    if ($insert) {
        echo 'data added successfully';
    }
    
?>