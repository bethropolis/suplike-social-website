<?php

require 'dbh.inc.php';
require 'Auth/auth.php';
require 'extra/xss-clean.func.php';
header('content-type: application/json');
session_start();

//auth check
$un_ravel->_isAuth();


// Define constants for file extensions, file size limit, random bytes length, etc.
define("FILE_EXTENSIONS", array("jpeg", "jpg", "png", "gif", "webp"));
define("FILE_SIZE_LIMIT", 6291456);
define("RANDOM_BYTES_LENGTH", 4);

// Define a function to validate the input
function validate_input($type, $image_text, $file_size, $file_ext)
{
    print_r([$type, $image_text, $file_size, $file_ext]);
    die();
    if ($type != "img" && $type != "txt") {
        return 'invalid post type';
    }
    // Check if the image text is empty
    if ($type == "txt" && $image_text === "") {
        return 'post text cannot be empty';
    }
    // Check if the file size and extension are valid
    if ($type == "img" && ($file_size > FILE_SIZE_LIMIT || !in_array($file_ext, FILE_EXTENSIONS))) {
        return '';
    }
    // Return true if all checks pass
    return true;
}

// Define a function to generate a random post ID
function generate_post_id()
{
    return bin2hex(openssl_random_pseudo_bytes(RANDOM_BYTES_LENGTH));
}

// Define a function to insert data into the posts table
function insert_post($conn, $image_text, $image, $type, $user, $d)
{
    // Prepare the SQL statement with named parameters
    $sql = "INSERT INTO posts (`post_id`, `image_text`,`image`, `type`, `userid`, `date_posted`, `day`) VALUES (?, ?, ?, ?, ?,?,?)";
    $stmt = $conn->prepare($sql);
    // Bind the parameters with the values
    $day = $d->format('l');
    $date = $d->format('j M');
    $post_id = generate_post_id();
    $stmt->bind_param("sssssss", $post_id, $image_text, $image, $type, $user, $date, $day);
    // Execute the statement
    $stmt->execute();

    return $post_id;
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
    // Split the tags into an array
    $id = getPostId($conn, $postId);
    $tagArray = array_map('trim', explode(',', $tags));

    // Insert tags into tags table
    foreach ($tagArray as $tagName) {
        $tagName = mysqli_real_escape_string($conn, $tagName);

        // Use IGNORE keyword to ignore duplicate tag names
        $sql = "INSERT IGNORE INTO tags (name) VALUES ('$tagName')";
        mysqli_query($conn, $sql);

        // Get the tag ID
        $tagId = getTagIdByName($conn, $tagName);

        // Insert tag and post ID into post_tags table
        $sql = "INSERT INTO post_tags (post_id, tag_id) VALUES ($id, $tagId)";
        mysqli_query($conn, $sql);
    }
}



// Define a function to insert data into the stories table
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
}

// Check if upload is set
if (isset($_POST['upload'])) {

    // Get the type and user from POST
    $type = $_POST['type'];
    $user = $_SESSION['userId'];
    $tags = $_POST['tags'];

    // Create a DateTime object with timezone
    $d = new DateTime("now", $timeZone);

    // Sanitize and trim the image text from POST
    $image_text = mysqli_real_escape_string($conn, $_POST['postText']);
    $image_text = xss_clean($image_text);
    $image_text = htmlspecialchars($image_text);
    $image_text = trim($image_text);

    // Remove newlines from image text
    $image_text = preg_replace('~[\r\n]+~', '', $image_text);

    // Get the check value from POST or false if not set
    $check = isset($_POST['community']) && $_POST['community'] == 'story' ? true : false;

    // Check if the type is image
    if ($type == 'img') {

        // Get the file size, tmp, type, and extension from FILES
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $dot = explode('.', $_FILES['image']['name']);
        $file_ext = strtolower(end($dot));

        // Generate a random image name
        $image = rand(12, 2000) . '_' . generate_post_id() . "." . $file_ext;

        // Validate the input
        if (!validate_input($type, $image_text, $file_size, $file_ext)) {
            header('Location: ../home.php?upload=invalid');
            die();
        }

        // Move the uploaded file to the target directory
        $target = "../img/" . $image;
        move_uploaded_file($file_tmp, $target);

        // Check if check is false
        if (!$check) {
            // Insert data into the posts table with image
            $id = insert_post($conn, $image_text, $image, $type, $user, $d);
            insertPostTags($conn, $id, $tags);
        } else {
            // Insert data into the stories table with image
            insert_story($conn, $image_text, $image, $type, $user);
        }

        if ($_POST['upload'] == 'post') {
            print_r(
                json_encode(
                    [
                        "type" => 'success',
                        "message" => "Post uploaded successfully to " . $_POST['community'],
                    ]
                )
            );
        } else {
            header("Location: ../home.php?upload=success");
        }
        die();
    } else if ($type == 'txt') {

        // Validate the input
        if (!validate_input($type, $image_text, 0, "")) {
            if ($_POST['upload'] == 'post') {
                print_r(
                    json_encode(
                        [
                            "type" => 'error',
                            "message" => "Input is invalid",
                        ]
                    )
                );
            } else {
                header("Location: ../home.php?upload=invalid");
                die();
            }
        }

        // Check if check is false
        if (!$check) {
            // Insert data into the posts table without image
            $id = insert_post($conn, $image_text, NULL, $type, $user, $d);
            insertPostTags($conn, $id, $tags);
        } else {
            // Insert data into the stories table without image
            insert_story($conn, $image_text, '', $type, $user);
        }

        // Redirect to the appropriate page based on upload value
        if ($_POST['upload'] == 'post') {
            die(json_encode(
                [
                    "type" => 'success',
                    "message" => "Post uploaded successfully to " . $_POST['community'],
                ]
            ));
        } else {
            header("Location: ../home.php?upload=success");
        }

        die();
    }
}





#-------------------GET POSTs------------------#

if (isset($_GET['user'])) {
    $result_array = [];
    $user = $un_ravel->_getUser($_GET['user']);

    # STAGE 1: GETTING THE USERS
    $query = "SELECT users.idusers, users.uidusers, users.usersFirstname, users.usersSecondname, users.profile_picture, auth_key.token, auth_key.chat_auth
              FROM users
              INNER JOIN auth_key ON users.idusers = auth_key.user
              WHERE users.idusers = ?
              
              UNION
              
              SELECT users.idusers, users.uidusers, users.usersFirstname, users.usersSecondname, users.profile_picture, auth_key.token, auth_key.chat_auth
              FROM following
              INNER JOIN users ON following.following = users.idusers
              INNER JOIN auth_key ON following.following = auth_key.user
              WHERE following.user = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user, $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);

    # STAGE 2: GETTING THE POSTS FROM EACH USER
    foreach ($users as $key) {
        $acc = $key["idusers"];
        $usr = $key["uidusers"];
        $sql = "SELECT posts.*, COUNT(comments.id) AS comments_count
                FROM posts
                LEFT JOIN comments ON posts.post_id = comments.post_id
                WHERE posts.userid = ?
                GROUP BY posts.id
                ORDER BY posts.id DESC
                LIMIT 20";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $acc);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($posts as $row) {
            $row['profile_picture'] = $un_ravel->_profile_picture($key["idusers"]);
            $text = $row['image_text'];
            $text = trim(preg_replace('/\s+/', ' ', $text));
            $text = trim(preg_replace('/\s\s+/', ' ', $text));
            $row['image_text'] = $text;
            $row['user'] = ['id' => $un_ravel->_queryUser($acc, 4), 'name' => $usr];
            $id = $row['id'];
            $post_id = $row['post_id'];
            $sql = "SELECT * FROM `likes` WHERE `post_id`=? AND `user_id`=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id, $user);
            $stmt->execute();
            $result = $stmt->get_result();
            $r = $result->fetch_assoc();
            # STAGE 3: DETERMINING IF THE USER HAS LIKED IT
            $row['liked'] = !is_null($r);
            $row['comments'] = $row['comments_count'];
            unset($row['comments_count']);
            $result_array[] = $row;
        }
    }
    # sort posts by id in descending order
    usort($result_array, function ($a, $b) {
        return $b['id'] - $a['id'];
    });
    print_r(json_encode($result_array));
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM `posts` WHERE `post_id`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $user = $row['userid'];

    # get user
    $sql = "SELECT `idusers`,`uidusers`,`usersFirstname`,`usersSecondname`,`profile_picture`,`token`,`chat_auth` FROM `users`,`auth_key` WHERE `users`.`idusers`=? AND `auth_key`.`user` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user, $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $resp = $result->fetch_assoc();
    $row['user'] = ['id' => $un_ravel->_queryUser($user, 4), 'name' => $resp['uidusers'], 'profile_picture' => $resp['profile_picture']];
    $post_id = $row['post_id'];

    # get number of likes
    $sql = "SELECT * FROM `likes` WHERE `post_id`=? AND `user_id`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $r = $result->fetch_assoc();

    if (!is_null($r)) {
        $row['liked'] = true;
    } else {
        $row['liked'] = false;
    }

    # get number of comments
    $sql = "SELECT * FROM `comments` WHERE `post_id`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row['comments'] = $result->num_rows;

    print_r(json_encode($row));
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

        header("Location: ../?delpost");
    } else {
        // status code 403
        header("HTTP/1.0 403 Forbidden");
    }
}
