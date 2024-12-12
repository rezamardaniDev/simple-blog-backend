<?php
require_once 'functions/helpers.php';
require_once 'functions/pdo_connection.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP tutorial</title>
    <link rel="stylesheet" href="<?= asset('assets/css/bootstrap.min.css') ?>" media="all" type="text/css">
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>" media="all" type="text/css">
</head>

<body>
        <?php require_once "layouts/top-nav.php" ?>
        <section class="container my-5">

            <section class="row">
                <section class="col-12">
                    <?php
                    global $pdo;

                    $query = "SELECT * FROM categories WHERE `id` = ?";
                    $statement = $pdo->prepare($query);
                    $statement->execute([$_GET['id']]);
                    $category = $statement->fetch();

                    if ($category !== false) {
                    ?>
                        <h1><?= $category->name ?></h1>
                        <hr>
                    <?php } else { ?>
                        <section class="row">
                            <section class="col-12">
                                <h1>Category not found</h1>
                            </section>
                        </section>
                    <?php } ?>
                </section>
            </section>
            <?php
            global $pdo;

            // بررسی وجود id در خواست GET
            if (isset($_GET['id'])) {
                $query = "SELECT * FROM posts WHERE status = 1 AND cat_id = ?";
                $statement = $pdo->prepare($query);
                $statement->execute([$_GET['id']]);
                $posts = $statement->fetchAll(PDO::FETCH_OBJ); // اطمینان از اینکه نتایج به عنوان اشیاء برگردانده می‌شوند

                if ($posts) {
                    foreach ($posts as $post) {
            ?>
                        <section class="row">
                            <section class="col-md-4">
                                <section class="mb-2 overflow-hidden" style="max-width: 12rem;">
                                    <img class="img-fluid" src="<?= htmlspecialchars(asset($post->image)) ?>" alt="">
                                </section>
                                <h2 class="h5 text-truncate"><?= htmlspecialchars($post->title) ?></h2>
                                <p><?= htmlspecialchars($post->body) ?></p>
                                <p>
                                    <a class="btn btn-primary" href="<?= htmlspecialchars(url('detail.php?post=' . $post->id)) ?>" role="button">View details »</a>
                                </p>
                            </section>
                        </section>
                    <?php }
                } else { ?>
                    <h1>No post Here!</h1>
                <?php }
            } else { ?>
                <h1>Invalid request!</h1>
            <?php } ?>


        </section>

        </section>
        <script src="<?= asset('assets/js/jquery.min.js') ?>"></script>
        <script src="<?= asset('assets/js/bootstrap.min.js') ?>"></script>
</body>

</html>