<?php
require_once "../r.php";

if (isset($_GET['user_token'])) {
    $user = $un_ravel->_getUser($_GET['user_token']);
    // $type = $_GET["type"]; put a conditional
    $type = isset($_GET['type']) ? $_GET['type'] : "followers";
    if ($type === 'followers') {
        $sql = "SELECT u.uidusers, u.usersFirstname, u.usersSecondname, u.profile_picture, u.bio,
                (CASE WHEN f.following IS NOT NULL THEN true ELSE false END) AS following
                FROM users u
                INNER JOIN following f ON u.idusers = f.following
                WHERE f.user = ?
                ORDER BY u.page_visit DESC
                LIMIT 100";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user);
    } else if ($type === 'following') {
        $sql = "SELECT u.uidusers, u.usersFirstname, u.usersSecondname, u.profile_picture, u.bio,
                (CASE WHEN f.user IS NOT NULL THEN true ELSE false END) AS following
                FROM users u
                INNER JOIN following f ON u.idusers = f.user
                WHERE f.following = ?
                ORDER BY u.page_visit DESC
                LIMIT 100";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user);
    } else {
        $error->err("API", 25, "invalid type parameter");
        die();
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $resultArray = [];
        while ($row = $result->fetch_assoc()) {
            $resultArray[] = $row;
        }
        print_r(json_encode([
            'code' => 1,
            'msg' => 'users fetched',
            'type' => 'success',
            'data' => $resultArray
        ]));
    } else {
        print_r(json_encode([
            'code' => 1,
            'msg' => 'users fetched',
            'type' => 'success',
            'data' => false
        ]));
    }
}
