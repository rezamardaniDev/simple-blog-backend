<?php
# connection to database with PDO Connector

global $pdo;

try {
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ);
    $pdo = new PDO("mysql:host=localhost;dbname=php_project", 'root', '', $options);
    return $pdo;
} catch (PDOException $e) {
    echo "ERORR! : " . $e->getMessage();
    exit;
}
