<?php 
require 'connection.php';

$collection = $database->selectCollection('users');

$findAll = $collection->find(
    ['age' => '20']
);

echo '<table>'; // Déplacez l'ouverture de la table en dehors de la boucle

foreach ($findAll as $f) {
    echo '<tr>
            <td>' . $f->name . '</td>
            <td>' . $f->age . '</td>
            <td>' . $f->gender . '</td>
            <td><a href="delete.php?id=' . $f->_id . '">Delete</a></td>
          </tr>';
}

echo '</table>'; // Fermez la table après la boucle
?>
