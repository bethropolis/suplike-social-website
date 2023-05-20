<?php
require '../r.php';


if (isset($_POST['user_token'])) {
  $user_key = $_POST['user_token'];
  $user = $un_ravel->_getUser($user_key);
  $following = $un_ravel->_userid($_POST['following']);

  // check if user is already following
  $sql = "SELECT COUNT(*) AS count FROM following WHERE user = ? AND following = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $user, $following);
  $stmt->execute();
  $result = $stmt->get_result();
  $count = $result->fetch_assoc()['count'];
  if ($user === $following) {
    $response = ['code' => 0, 'msg' => 'can not follow yourself', 'type' => 'error'];
    die(json_encode($response));
  }
  if ($count > 0) {
    $sql = "DELETE FROM following WHERE user = ? AND following = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user, $following);
    $stmt->execute();
    $stmt->close();
    $response = ['code' => 0, 'msg' => 'Unfollowed', 'type' => 'success'];
    die(json_encode($response));
  } else {
    $sql = "INSERT INTO following (user, following) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user, $following);
    $stmt->execute();
    $stmt->close();
    $response = ['code' => 0, 'msg' => 'Followed', 'type' => 'success'];
    die(json_encode($response));
  }
}

if (!isset($_GET['user_token'])) {
  die(json_encode(['error' => 'no user specified']));
}
$user_key = $_GET['user_token'];
$user = $un_ravel->_getUser($user_key);


// replace all occurrences of 5 with $user
$sql = "SELECT
  f.following,
  u.idusers,
  u.uidusers as username,
  u.usersFirstname as firstname,
  u.usersSecondname as secondname,
  u.gender,
  CONCAT('" . BASE_URL . "', u.profile_picture) as image,
  c.message as msg,
  c.type as type,
  c.time as time,
  ak.chat_auth as chat_key
FROM following f
JOIN users u ON u.idusers = f.following
LEFT JOIN (
  SELECT
    MAX(time) AS last_msg_time,
    CASE
      WHEN who_to = {$user} THEN who_from
      ELSE who_to
    END AS other_user
  FROM chat
  WHERE who_to = {$user} OR who_from = {$user}
  GROUP BY other_user
) AS last_msg ON u.idusers = last_msg.other_user OR u.idusers = {$user}
LEFT JOIN chat c ON ((c.who_from = u.idusers AND c.who_to = {$user}) OR (c.who_from = {$user} AND c.who_to = u.idusers)) AND last_msg.last_msg_time = c.time  
JOIN auth_key ak ON ak.user = f.following
WHERE f.user = {$user}
ORDER BY time DESC";

// execute the query
$result = mysqli_query($conn, $sql);


if (!$result) {
  $error->err("API access", 22, "Database query failed");
}

// fetch all rows as an associative array
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

// print the rows as JSON
echo json_encode($rows);
