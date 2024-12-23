<?php
require_once '../../functions/helpers.php';
require_once '../../functions/pdo_connection.php';



if (!isset($_GET['id'])) {
    redirect('panel/post');
}

$query = "SELECT * FROM posts WHERE `id` = ?";
$statement = $pdo->prepare($query);
$statement->execute([$_GET['id']]);
$post = $statement->fetch();

if ($post === false) {
    redirect('panel/post');
}

if (
    isset($_POST['title']) && $_POST['title'] !== '' &&
    isset($_POST['cat_id']) && $_POST['cat_id'] !== '' &&
    isset($_POST['body']) && $_POST['body'] !== ''
) {

    $query = "SELECT * FROM `categories` WHERE `id` = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$_POST['cat_id']]);
    $category = $statement->fetch();


    if (isset($_FILES['image']) && $_FILES['image']['name'] !== '') {

        $allowMimes = ['png', 'jpeg', 'jpg', 'gif'];
        $imageMime = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        if (!in_array($imageMime, $allowMimes)) {
            redirect('panel/post');
        }

        $basePath = dirname(dirname(__DIR__));
        if (file_exists($basePath . $post->image)) {
            unlink($basePath . $post->image);
        }
        $image = '/assets/images/posts/' . date('Y-m-d-H-i-s') . '.' . $imageMime;
        $image_upload = move_uploaded_file($_FILES['image']['tmp_name'], $basePath . $image);

        if ($category !== false && $image_upload !== false) {
            $query = "UPDATE `posts` SET `title` = ?, `cat_id` = ?, `body` = ?, `image` = ?, `updated_at` = NOW() WHERE `id` = ?";
            $statement = $pdo->prepare($query);
            $statement->execute([$_POST['title'], $_POST['cat_id'], $_POST['body'], $image, $_GET['id']]);
        }
    } else {
        if ($category !== false) {
            $query = "UPDATE `posts` SET `title` = ?, `cat_id` = ?, `body` = ?, `updated_at` = NOW() WHERE `id` = ?";
            $statement = $pdo->prepare($query);
            $statement->execute([$_POST['title'], $_POST['cat_id'], $_POST['body'], $_GET['id']]);
        }
    }
    redirect('panel/post');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP panel</title>
    <link rel="stylesheet" href="<?= asset('assets/css/bootstrap.min.css') ?>" media="all" type="text/css">
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>" media="all" type="text/css">
</head>

<body>
    <section id="app">

        <?php require_once '../layouts/top-nav.php'; ?>
        <section class="container-fluid">
            <section class="row">
                <section class="col-md-2 p-0">
                    <?php require_once '../layouts/sidebar.php'; ?>
                </section>
                <section class="col-md-10 pt-3">

                    <form action="<?= url('panel/post/edit.php?id=' . $_GET['id']) ?>" method="post" enctype="multipart/form-data">
                        <section class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" value="<?= $post->title ?>">
                        </section>
                        <section class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" name="image" id="image">
                            <img src="<?= asset($post->image) ?>" alt="" width="200px">
                        </section>
                        <section class="form-group">
                            <label for="cat_id">Category</label>
                            <select class="form-control" name="cat_id" id="cat_id">
                                <?php
                                global $pdo;
                                $query = "SELECT * FROM `categories`";
                                $statement = $pdo->prepare($query);
                                $statement->execute();
                                $categories = $statement->fetchAll();

                                foreach ($categories as $category) {
                                ?>
                                    <option <?php if ($category->id == $post->cat_id) echo 'selected'; ?> value="<?= $category->id ?>"><?= $category->name ?></option>
                                <?php } ?>
                            </select>
                        </section>
                        <section class="form-group">
                            <label for="body">Body</label>
                            <textarea class="form-control" name="body" id="body" rows="5"><?= $post->body ?></textarea>
                        </section>
                        <section class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </section>
                    </form>

                </section>
            </section>
        </section>

    </section>

    <script src="<?= asset('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= asset('assets/js/bootstrap.min.js') ?>"></script>
</body>

</html>