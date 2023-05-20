<?php
require '../r.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  try {
    $result_array = [];
    # STAGE 1: GETTING THE USERS
    $user = $un_ravel->_getUser($_GET['user_token']);

    // compare user to SESSION_ID
    authentication_check($user);


    $post_id = isset($_GET['post_id']) ? filter_input(INPUT_GET, 'post_id') : null;
    $query = "SELECT `idusers`, `uidusers`, `usersFirstname`, `usersSecondname`, `profile_picture` FROM `users` WHERE `idusers` = $user UNION SELECT `users`.`idusers`, `users`.`uidusers`, `users`.`usersFirstname`, `users`.`usersSecondname`, `users`.`profile_picture` FROM `following` INNER JOIN `users` ON `following`.`following` = `users`.`idusers` WHERE `following`.`user` = $user;";
    $result = $conn->query($query);
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $users[$row['idusers']] = $row;
    }
    $users[$user] = ['idusers' => $user, 'uidusers' => $un_ravel->_queryUser($user, 4)];

    # STAGE 2: GETTING THE POSTS
    $userIds = array_keys($users);
    $userIds = implode(',', $userIds);
    $lastId = isset($_GET['last_id']) ? $_GET['last_id'] : null;
    $lastId = isset($_GET['last_id']) ? $_GET['last_id'] : null;
    if (!$lastId) {
      // Return the initial set of posts
      // Construct the SQL query
      $query = "SELECT
  posts.*,
  users.uidusers,
  users.profile_picture,
  GROUP_CONCAT(DISTINCT tags.name SEPARATOR ',') AS tags,
  likes.user_id IS NOT NULL AS liked,
  COALESCE(comment_counts.comment_count, 0) AS comment_count
FROM
  posts
  INNER JOIN users ON posts.userid = users.idusers
  LEFT JOIN post_tags ON posts.id = post_tags.post_id
  LEFT JOIN tags ON post_tags.tag_id = tags.id
  LEFT JOIN likes ON posts.id = likes.post_id AND likes.user_id = '$user'
  LEFT JOIN (
    SELECT post_id, COUNT(*) AS comment_count
    FROM comments
    GROUP BY post_id
  ) AS comment_counts ON posts.post_id = comment_counts.post_id
WHERE
  posts.userid IN ($userIds) AND posts.deleted = false
GROUP BY
  posts.id
ORDER BY
  posts.id DESC
LIMIT 25";
    } else {
      // Return the next set of posts
      // Construct the SQL query
      $query = "SELECT
  posts.*,
  users.uidusers,
  users.profile_picture,
  GROUP_CONCAT(DISTINCT tags.name SEPARATOR ',') AS tags,
  likes.user_id IS NOT NULL AS liked,
  COALESCE(comment_counts.comment_count, 0) AS comment_count
FROM
  posts
  INNER JOIN users ON posts.userid = users.idusers
  LEFT JOIN post_tags ON posts.id = post_tags.post_id
  LEFT JOIN tags ON post_tags.tag_id = tags.id
  LEFT JOIN likes ON posts.id = likes.post_id AND likes.user_id = '$user'
  LEFT JOIN (
    SELECT post_id, COUNT(*) AS comment_count
    FROM comments
    GROUP BY post_id
  ) AS comment_counts ON posts.post_id = comment_counts.post_id
WHERE
                  posts.userid IN ($userIds)
                  AND posts.id < $lastId AND posts.deleted = false
              GROUP BY
                  posts.id
              ORDER BY
                  posts.id DESC
              LIMIT 25";
    }

    if ($post_id) {
      $query = "SELECT
  posts.*,
  users.uidusers,
  users.profile_picture,
  GROUP_CONCAT(DISTINCT tags.name SEPARATOR ',') AS tags,
  likes.user_id IS NOT NULL AS liked,
  COALESCE(comment_counts.comment_count, 0) AS comment_count
FROM
  posts
  INNER JOIN users ON posts.userid = users.idusers
  LEFT JOIN post_tags ON posts.id = post_tags.post_id
  LEFT JOIN tags ON post_tags.tag_id = tags.id
  LEFT JOIN likes ON posts.id = likes.post_id AND likes.user_id = '$user'
  LEFT JOIN (
    SELECT post_id, COUNT(*) AS comment_count
    FROM comments
    GROUP BY post_id
  ) AS comment_counts ON posts.post_id = comment_counts.post_id
WHERE
    posts.post_id = '$post_id' AND posts.deleted = false
LIMIT 25";
    }
    // Execute the SQL query and fetch the results
    $result = $conn->query($query);
    while ($row = mysqli_fetch_assoc($result)) {
      // Add information about the user who created the post to the 'user' field
      $row['user'] = ['name' => $row['uidusers'], 'profile_picture' => $row["profile_picture"]];
      // Split the 'tags' field into an array if it exists
      if (isset($row['tags'])) {
        $row['tags'] = explode(',', $row['tags']);
      }
      // Check if the current user has liked the post
      if ($row['liked'] == "1") {
        $row['liked'] = true;
      } else {
        $row['liked'] = false;
      }
      // Add the modified row to the result array
      $result_array[] = $row;
    }
    // if result array is empty get popular posts
    if (empty($result_array) && $un_ravel->_no_followers($user) && !$lastId) {
      $sql = "SELECT p.id, p.post_id, p.repost, p.image, p.image_text, p.userid, p.type, p.date_posted, p.post_likes, p.day, p.time, u.uidusers, u.profile_picture, u.usersFirstname, u.usersSecondname, 
            (CASE WHEN EXISTS (SELECT id FROM likes WHERE post_id = p.id AND user_id = ?) THEN true ELSE false END) AS liked 
            FROM posts p 
            INNER JOIN users u ON p.userid = u.idusers 
            WHERE p.deleted = false
            ORDER BY 
                (CASE WHEN p.post_likes IS NULL THEN 0 ELSE p.post_likes END) DESC, 
                p.time DESC 
            LIMIT 15";
      // use prepared statement
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $user);
      $stmt->execute();
      $result = $stmt->get_result();

      while ($row = mysqli_fetch_assoc($result)) {
        $row['user'] = ['name' => $row['uidusers'], 'profile_picture' => $row["profile_picture"]];

        if ($row['liked'] == "1") {
          $row['liked'] = true;
        } else {
          $row['liked'] = false;
        }

        $result_array[] = $row;
      }
    }

    usort($result_array, function ($a, $b) {
      return strcmp($b['time'], $a['time']);
    });

    print_r(json_encode($result_array));
  } catch (Exception $e) {
    $error->err("Error: ", 1, $e->getMessage());
  }
}

// POST submission

function upload_file($file)
{
  $upload_dir = '../../../img/';
  // Get file information
  $file_name = $file['name'];
  $file_tmp = $file['tmp_name'];
  $file_size = $file['size'];
  $file_error = $file['error'];
  $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'mp3', 'mp4', 'avi', 'mov', 'webp');
  // Get file extension
  $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

  // Check if file extension is allowed
  if (!in_array($file_extension, $allowed_extensions)) {
    return [
      'code' => 1,
      'type' => "error",
      'msg' => "Invalid file type"
    ];
  }
  // check file size, should be less than 30mb
  if ($file_size > 30 * 1024 * 1024) {
    return [
      'code' => 1,
      'type' => "error",
      'msg' => "File too large"
    ];
  }

  // Check for file upload errors
  if ($file_error !== UPLOAD_ERR_OK) {
    return [
      'code' => 1,
      'type' => "error",
      'msg' => "File upload error"
    ];
  }

  // Generate unique file name and move file to destination
  $file_name_new = uniqid('', true) . '.' . $file_extension;
  $file_destination = $upload_dir . $file_name_new;
  if (!move_uploaded_file($file_tmp,  $file_destination)) {
    return [
      'code' => 1,
      'msg' => "File upload error"
    ];
  }

  // Return file destination
  return $file_name_new;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST["delete"])) {

  $type = isset($_POST["type"]) ? filter_input(INPUT_POST, 'type') : "txt";
  $content = isset($_POST["content"]) ? filter_input(INPUT_POST, 'content') : "";
  $repost = isset($_POST["repost"]) ? filter_input(INPUT_POST, "repost") : "";
  if (!isset($_POST["user_token"])) {
    $error->err("missing token", 1, "Missing user_token");
  }
  $user = $un_ravel->_getUser($_POST["user_token"]);

  $d = new DateTime("now", $timeZone);
  $image_text = mysqli_real_escape_string($conn, $content);
  $image_text = xss_clean($image_text);
  $image_text = htmlspecialchars($image_text);
  $image_text = trim($image_text);
  $image_text  = preg_replace('~[\r\n]+~', '', $image_text);
  $post_id = bin2hex(openssl_random_pseudo_bytes(4));

  if ($type == 'txt') {
    $sql = "INSERT INTO posts (`post_id`,`image_text`, `userid`,`type`,`repost` ,`date_posted`, `day`) VALUES (?,?,?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $date = $d->format('j M');
    $day = $d->format('l');
    $stmt->bind_param("sssssss", $post_id, $image_text, $user, $type, $repost, $date, $day);
    $stmt->execute();
    $stmt->close();

    return print_r(json_encode(['code' => 0, 'type' => 'Success', 'msg' => 'Post added successfully']));
  } else if ($type == 'img' || $type == 'vid' || $type == 'mus') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
      $location = upload_file($_FILES['file']);
      $sql = "INSERT INTO posts (`post_id`,`image_text`,`image`, `userid`,`type` ,`date_posted`, `day`) VALUES (?,?,?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $date = $d->format('j M');
      $day = $d->format('l');
      $stmt->bind_param("sssssss", $post_id, $image_text, $location, $user, $type, $date, $day);
      $stmt->execute();
      $stmt->close();
      return print_r(json_encode(['code' => 0, 'type' => 'Success', 'msg' => 'Post added successfully']));
    } else if ($type == 'mus' && isset($_POST['audio_url'])) {
      $audio_url = filter_input(INPUT_POST, 'audio_url');
      $sql = "INSERT INTO posts (`post_id`,`image_text`,`audio_url`, `userid`,`type` ,`date_posted`, `day`) VALUES (?,?,?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $date = $d->format('j M');
      $day = $d->format('l');
      $stmt->bind_param("sssssss", $post_id, $image_text, $audio_url, $user, $type, $date, $day);
      $stmt->execute();
      $stmt->close();
      return print_r(json_encode(['code' => 0, 'type' => 'Success', 'msg' => 'Post added successfully']));
    } else {
      $error->err("Invalid type", 1, "Invalid type $type");
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["delete"])) {
  if (!isset($_POST["user_token"])) {
    $error->err("missing token", 1, "Missing user_token");
  }
  if (!isset($_POST["post_id"])) {
    $error->err("missing post_id", 1, "Missing post_id");
  }

  // if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
  //   die(print_r(json_encode(['code' => 1, 'type' => 'error', 'msg' => 'CSRF token mismatch'])));
  // }

  $user = $un_ravel->_getUser($_POST["user_token"]);
  if ($user !== SESSION_ID) {
    $error->err("Unauthorized user", 1, "Unauthorized user");
  }

  $post_id = mysqli_real_escape_string($conn, $_POST["post_id"]);
  $sql = "SELECT userid, deleted FROM posts WHERE post_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $post_id);
  $stmt->execute();
  $stmt->bind_result($owner, $deleted);
  $stmt->fetch();
  $stmt->close();

  if ($deleted) {
    $error->err("Post already deleted", 1, "Post not found");
  }

  if ($owner != $user) {
    $error->err("Unauthorized user", 1, "Unauthorized user");
  }

  $sql = "UPDATE posts SET deleted=1 WHERE post_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $post_id);
  $stmt->execute();
  $stmt->close();

  echo json_encode(['code' => 0, 'type' => 'Success', 'msg' => 'Post deleted']);
}
