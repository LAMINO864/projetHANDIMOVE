<?php
function connexionPDO(){
    $username = 'util';
    $password = 'util';

    $pdo = new PDO("mysql:host=localhost;dbname=projet", $username, $password);

    return $pdo;
}