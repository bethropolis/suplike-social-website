<?php

require_once 'dbh.inc.php';
require_once 'Auth/auth.php';
require_once 'errors/error.inc.php';
require_once 'extra/xss-clean.func.php';
require_once 'extra/ratelimit.class.php';
include_once '../plugins/load.php';

use Bethropolis\PluginSystem\System;

header('content-type: application/json');

@session_start();

// Auth check
$un_ravel->_isAuth();

/**
 * Validates the input values for the post.
 *
 * @param string $type The type of the post ('img' or 'txt')
 * @param string $image_text The text of the post
 * @param int $file_size The size of the image file (for 'img' type)
 * @param string $file_ext The extension of the image file (for 'img' type)
 * @return string|true The validation error message, or true if all checks pass
 */
function validate_input($type, $image_text, $file_size, $file_ext)
{
    if ($type !== "img" && $type !== "txt") {
        return 'Invalid post type';
    }

    if ($type === "txt" && empty($image_text)) {
        return 'Post text cannot be empty';
    }

    if ($type === "img" && !in_array($file_ext, FILE_EXTENSIONS)) {
        return 'Unsupported file format';
    }

    if ($type === "img" && $file_size > FILE_SIZE_LIMIT) {
        return 'File too large';
    }

    return true;
}

/**
 * Generates a unique post ID.
 *
 * @return string The generated post ID
 */
function generate_post_id()
{
    return bin2hex(openssl_random_pseudo_bytes(RANDOM_BYTES_LENGTH));
}

/**
 * Inserts a post into the database.
 *
 * @param mysqli $conn The database connection object
 * @param string $image_text The text of the post
 * @param string $image The image filename (for 'img' type)
 * @param string $type The type of the post ('img' or 'txt')
 * @param int $user The user ID
 * @param DateTime $d The current DateTime object
 * @return string The generated post ID
 */
function insert_post($conn, $image_text, $image, $type, $user, $d)
{
    $sql = "INSERT INTO posts (`post_id`, `image_text`, `image`, `type`, `userid`, `date_posted`, `day`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $day = $d->format('l');
    $date = $d->format('j M');
    $post_id = generate_post_id();

    $stmt->bind_param("sssssss", $post_id, $image_text, $image, $type, $user, $date, $day);
    $stmt->execute();
    $stmt->close();

    return $post_id;
}

/**
 * Inserts data into the stories table.
 *
 * @param mysqli $conn The database connection.
 * @param string $image_text The text of the story.
 * @param string $image The image URL of the story.
 * @param string $type The type of the story.
 * @param string $user The user ID of the story.
 * @return bool
 */
function insert_story($conn, $image_text, $image, $type, $user)
{
    // Prepare the SQL statement with named parameters
    $sql = "INSERT INTO stories (`post_id`, `text`,`image`, `type`, `userid`) VALUES (?, ?,?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind the parameters with the values
    $post_id = generate_post_id();
    $stmt->bind_param("sssss", $post_id, $image_text, $image, $type, $user);

    // Execute the statement
    $stmt->execute();
    $stmt->close();

    return;
}

/**
 * Retrieves the ID from the posts table based on the post_id.
 *
 * @param mysqli $conn The database connection object
 * @param string $post_id The post_id to retrieve the ID for
 * @return int|false The ID of the post if found, false otherwise
 */
function getPostId($conn, $post_id)
{
    $post_id = mysqli_real_escape_string($conn, $post_id);
    $sql = "SELECT id FROM posts WHERE post_id = '$post_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    }

    return false;
}

/**
 * Retrieves the ID from the tags table based on the tag name.
 *
 * @param mysqli $conn The database connection object
 * @param string $tag_name The name of the tag to retrieve the ID for
 * @return int|false The ID of the tag if found, false otherwise
 */
function getTagIdByName($conn, $tag_name)
{
    $tag_name = mysqli_real_escape_string($conn, $tag_name);
    $sql = "SELECT id FROM tags WHERE name = '$tag_name'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    }

    return false;
}

/**
 * Inserts tags of a post into the post_tags and tags tables.
 *
 * @param mysqli $conn The database connection object
 * @param int $postId The ID of the post
 * @param string $tags The tags separated by commas
 */
function insertPostTags($conn, $postId, $tags)
{
    $id = getPostId($conn, $postId);
    $tagArray = array_map('trim', explode(',', $tags));

    foreach ($tagArray as $tagName) {
        if (!empty($tagName)) {
            $tagName = mysqli_real_escape_string($conn, $tagName);

            $sql = "INSERT IGNORE INTO tags (name) VALUES ('$tagName')";
            mysqli_query($conn, $sql);

            $tagId = getTagIdByName($conn, $tagName);
            $sql = "INSERT INTO post_tags (post_id, tag_id) VALUES ($id, $tagId)";
            mysqli_query($conn, $sql);
        }
    }
}

// Check if upload is set
if (isset($_POST['upload'])) {
    // Get the type, user, and tags from POST
    $type = $_POST['type'];
    $user = $_SESSION['userId'];
    $tags = $_POST['tags'];

    if (!defined("USER_POST") || !USER_POST) {
        if ($_POST['upload'] === 'post') {
            $error->err("POST disabled", 33, "Creating posts has been disabled");
        } else {
            header("Location: ../home.php?error=postoff");
        }
        die();
    }

    // Create a DateTime object with timezone
    $d = new DateTime("now", $timeZone);

    // Sanitize and trim the image text from POST
    $image_text = mysqli_real_escape_string($conn, $_POST['postText']);
    $image_text = xss_clean($image_text);
    $image_text = htmlspecialchars($image_text);
    $image_text = trim($image_text);
    $image_text  = preg_replace('~[\r\n]+~', '', $image_text);

    // Remove newlines from image text
    $image_text = preg_replace('~[\r\n]+~', '', $image_text);

    // Get the check value from POST or false if not set
    $check = isset($_POST['community']) && $_POST['community'] === 'story';

    // Check if the type is image
    if ($type === 'img') {
        // Get the file size, tmp, type, and extension from FILES
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $dot = explode('.', $_FILES['image']['name']);
        $file_ext = strtolower(end($dot));
        $image = rand(12, 2000) . '_' . generate_post_id() . "." . $file_ext;

        // Validate the input
        $validate = validate_input($type, $image_text, $file_size, $file_ext);
        if ($validate !== true) {
            if ($_POST['upload'] === 'post') {
                $error->err("Post failed", 32, $validate);
            } else {
                header("Location: ../home.php?upload=invalid");
            }
            die();
        }

        // Move the uploaded file to the target directory
        $target = "../img/" . $image;
        move_uploaded_file($file_tmp, $target);

        if (!$check) {
            // Insert data into the posts table with image
            $id = insert_post($conn, $image_text, $image, $type, $user, $d);
            insertPostTags($conn, $id, $tags);
        } else {
            // Insert data into the stories table with image
            $id = insert_story($conn, $image_text, $image, $type, $user);
        }

        if ($_POST['upload'] === 'post') {
            System::executeHook("post_upload", null, ["user" => $user, "post_id" => $id]);
            echo json_encode([
                "type" => 'success',
                "message" => "Post uploaded successfully to " . $_POST['community'],
            ]);
        } else {
            header("Location: ../home.php?upload=success");
        }
        die();
    } elseif ($type === 'txt') {
        // Validate the input
        $validate = validate_input($type, $image_text, 0, "");
        if ($validate !== true) {
            if ($_POST['upload'] === 'post') {
                $error->err("Post failed", 32, $validate);
            } else {
                header("Location: ../home.php?upload=invalid");
            }
            die();
        }

        if (!$check) {
            // Insert data into the posts table without image
            $id = insert_post($conn, $image_text, NULL, $type, $user, $d);
            insertPostTags($conn, $id, $tags);
        } else {
            // Insert data into the stories table without image
            $id = insert_story($conn, $image_text, '', $type, $user);
        }

        if ($_POST['upload'] === 'post') {
            System::executeHook("post_upload", null, ["user" => $user, "post_id" => $id]);
            echo json_encode([
                "type" => 'success',
                "message" => "Post uploaded successfully to " . $_POST['community'],
            ]);
        } else {
            header("Location: ../home.php?upload=success");
        }

        die();
    }
}






#-------------------GET POSTs------------------#

if (isset($_GET['user'])) {
    $result_array = [];
    $user = $_SESSION["userId"];

    $query = "SELECT posts.*, COUNT(comments.id) AS comments_count,
                users.uidusers, users.usersFirstname, users.usersSecondname, users.isAdmin as 'admin', users.isBot as 'bot', users.profile_picture,
                auth_key.token, auth_key.chat_auth,
                IF(likes.user_id = ?, 1, 0) AS liked
              FROM posts
              INNER JOIN users ON posts.userid = users.idusers
              INNER JOIN auth_key ON users.idusers = auth_key.user
              LEFT JOIN comments ON posts.post_id = comments.post_id
              LEFT JOIN likes ON posts.id = likes.post_id AND likes.user_id = ?
              WHERE users.idusers = ?
                  OR EXISTS (
                      SELECT 1 FROM following
                      WHERE following.user = ?
                      AND following.following = users.idusers
                  )
              GROUP BY posts.id
              ORDER BY posts.id DESC
              LIMIT 50";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiii", $user, $user, $user, $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $result_array = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($result_array as &$row) {
        $text = $row['image_text'];
        $text = trim(preg_replace('/\s+/', ' ', $text));
        $text = trim(preg_replace('/\s\s+/', ' ', $text));
        $row['image_text'] = $text;
        $row['user'] = ['id' => $un_ravel->_queryUser($row['userid'], 4), 'name' => $row['uidusers']];
        $row['comments'] = $row['comments_count'];
        unset($row['comments_count']);
    }
    unset($row);

    print_r(json_encode($result_array));
}


if (isset($_GET['id'])) {
    $result_array = [];
    $user = $_SESSION['userId'];
    $post_id = $_GET['id'];


    $query = "SELECT posts.*, COUNT(comments.id) AS comments_count,
    users.uidusers, users.usersFirstname, users.usersSecondname, users.profile_picture,
    users.isAdmin as 'admin', users.isBot as 'bot',auth_key.token, auth_key.chat_auth,
    IF(likes.user_id = ?, 1, 0) AS liked
  FROM posts
  INNER JOIN users ON posts.userid = users.idusers
  INNER JOIN auth_key ON users.idusers = auth_key.user
  LEFT JOIN comments ON posts.post_id = comments.post_id
  LEFT JOIN likes ON posts.id = likes.post_id AND likes.user_id = ?
  WHERE posts.post_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $user, $user, $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $result_array = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($result_array as &$row) {
        $text = $row['image_text'];
        $text = trim(preg_replace('/\s+/', ' ', $text));
        $text = trim(preg_replace('/\s\s+/', ' ', $text));
        $row['image_text'] = $text;
        $row['user'] = ['id' => $un_ravel->_queryUser($row['userid'], 4), 'name' => $row['uidusers']];
        $row['comments'] = $row['comments_count'];
        unset($row['comments_count']);
    }
    unset($row);

    print_r(json_encode($result_array));
}

if (isset($_GET['del_post'])) {
    // first check if the user is the owner of the post
    $id = $_GET['del_post'];
    $sql = "SELECT * FROM `posts` WHERE `post_id`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $user = $row['userid'];

    if ($user == $_SESSION['userId']) {
        $sql = "DELETE FROM `posts` WHERE `post_id`=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // delete comments
        $sql = "DELETE FROM `comments` WHERE `post_id`=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // delete likes
        $sql = "DELETE FROM `likes` WHERE `post_id`=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: ../home.php?delpost");
    } else {
        // status code 403
        header("HTTP/1.0 403 Forbidden");
    }
}
