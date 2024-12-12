<?php

require_once '../../functions/helpers.php';
require_once '../../functions/pdo_connection.php';


if (isset($_GET['id']) && $_GET['id'] != '') {
    global $pdo;

    $query = "DELETE FROM `categories` WHERE `id` = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$_GET['id']]);
}

redirect('panel/category');
