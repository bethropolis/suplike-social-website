<?php
include_once '../dbh.inc.php';
header('content-type: application/json');

$arr = [];

$dt = new DateTime("now", new DateTimeZone('Africa/Nairobi'));
$dt->format("Y-m-d H:i:s");
$dt = $dt->modify("-7 days");
$dt = $dt->format('Y-m-d H:i:s');

$sql = "SELECT `id`,`time`  FROM `likes` WHERE `time` > '$dt'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
        $arr['likes'][] = $row;
}

$sql = "SELECT `idusers`,`date_joined`  FROM `users` WHERE `date_joined` > '$dt'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
        $arr['users'][] = $row;
}

$sql = "SELECT `id`,`time`  FROM `following` WHERE `time` > '$dt'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
        $arr['following'][] = $row;
}

$sql = "SELECT `id`,`time` FROM `posts` WHERE `time` > '$dt'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
        $arr['posts'][] = $row;
}

$sql = "SELECT `id`,`time` FROM `chat` WHERE `time` > '$dt'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
        $arr['chat'][] = $row;
}

// share
$sql = "SELECT `id`,`time` FROM `share` WHERE `time` > '$dt'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
        $arr['share'][] = $row;
}
//  comments
$sql = "SELECT `id`,`date` FROM `comments` WHERE `date` > '$dt'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
        $arr['comments'][] = $row;
}



$arr['time'] = $dt;
print_r(json_encode($arr));
