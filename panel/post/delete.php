<?php

require_once '../../functions/helpers.php';
require_once '../../functions/pdo_connection.php';


if (isset($_GET['id']) && $_GET['id'] != '') {
    global $pdo;

    $query = "SELECT * FROM `posts` WHERE `id` = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$_GET['id']]);
    $post = $statement->fetch();

    if ($post !== false) {
        $basePath = dirname(dirname(__DIR__));
        if (file_exists($basePath . $post->image))  unlink($basePath . $post->image);
        $query = "DELETE FROM `posts` WHERE `id` = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$_GET['id']]);
    }
}

redirect('panel/post');
