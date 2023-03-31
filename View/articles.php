<?php
$role = 'guest';
$id = 0;
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['token'])) {
    echo "<a href='../login/'>Login</a>";
    
} else {
    echo "<a href='../login/'>Logout</a>";
    include_once(MODEL_PATH . "jwt_utils.php");
    $role = get_role($_SESSION['token']);
    // lowwer case
    $role = strtolower($role);
    $id = get_user($_SESSION['token']);
}
echo " id : " . $id . " role : " . $role . "<br>";
?>
<!-- simple table bootstrap -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Articles</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">
    <!-- Optional theme -->
    <style>
        .container{
            margin: 0 auto;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- form for create article -->
    <?php
    if ($role == "publisher") {
        echo "<div class=\"container\">";
        echo "<form action=\"../create\" method=\"post\">";
        echo "<div class=\"form-group\">";
        echo "<label for=\"title\">Title</label>";
        echo "<input type=\"text\" class=\"form-control\" id=\"title\" name=\"title\" placeholder=\"Title\">";
        echo "</div>";
        echo "<div class=\"form-group\">";
        echo "<label for=\"content\">Content</label>";
        echo "<input type=\"text\" class=\"form-control\" id=\"content\" name=\"content\" placeholder=\"Content\">";
        echo "</div>";
        echo "<button type=\"submit\" name=\"create\" class=\"btn btn-default\">Create</button>";
        echo "</form>";
        echo "</div>";
    }
    ?>
<!-- in a container -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Articles</h1>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Author</th>
                    <th>Date Created</th>
                    <th>Date Modified</th>
                    <?php
                    if ($role == "publisher" || $role == "moderator") {
                        echo "<th>Nb Likes</th>";
                        echo "<th>Nb Dislikes</th>";
                    }
                    if ($role == "moderator") {
                        echo "<th>Liste des likes</th>";
                        echo "<th>Liste des dislikes</th>";
                    }
                    if (isset($_SESSION['token'])) {
                        echo "<th>Actions</th>";
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($articles["data"] as $article) {
                    echo "<tr>";
                    echo "<td>" . $article['id'] . "</td>";
                    echo "<td>" . $article['title'] . "</td>";
                    echo "<td>" . $article['content'] . "</td>";
                    echo "<td>" . $article['author'] . "</td>";
                    echo "<td>" . $article['dateCreated'] . "</td>";
                    echo "<td>" . $article['dateModified'] . "</td>";
                    if ($role == "publisher" || $role == "moderator") {
                        echo "<td>" . $article['nblikes'] . "</td>";
                        echo "<td>" . $article['nbdislikes'] . "</td>";
                    }
                    if ($role == "moderator") {
                        echo "<td>";
                        foreach ($article['listlikes'] as $like) {
                            echo $like['IdUser'] . " ";
                        }
                        echo "</td>";
                        echo "<td>";
                        foreach ($article['listdislikes'] as $dislike) {
                            echo $dislike['IdUser'] . " ";
                        }
                        echo "</td>";
                    }
                    if (isset($_SESSION['token'])) {
                        echo "<td>";
                        if (($role == "publisher" && $article['author'] == $id)) {
                            echo "<a href='../articles/edit/" . $article['id'] . "'>Edit</a>";
                            echo "<a href='../articles/delete/" . $article['id'] . "'>Delete</a>";
                        }
                        if ($role == "moderator") {
                            echo "<a href='../articles/delete/" . $article['id'] . "'>Delete</a>";
                        }
                        if ($role == "publisher" && $article['author'] != $id) {
                            echo "<a href='../articles/like/" . $article['id'] . "'>Like</a>";
                            echo "<a href='../articles/dislike/" . $article['id'] . "'>Dislike</a>";
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>