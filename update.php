<?php 
require 'connection.php';

$collection = $database->selectCollection('users');

$filter = ['name' => 'jack'];

$update = [
        '$set' => [
            'age' => '25',
            'gender' => 'undefine',
        ]
];

$result = $collection->updateOne($filter, $update);

if ($result) {
    echo 'data updated :)';
}else{
    echo'failed to updated ';
}

?>