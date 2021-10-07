<?php
if (isset($_GET["chat"])) {
    if (isset($_GET['start'])) {
        $start = intval($_GET['start']);
        $from =  $auth->_getUser($_GET['from']);
        $to =  $auth->_getUser($_GET['to']);
        $items = $conn->query("SELECT * FROM `chat` WHERE `id`>" . $start . " AND (`who_to`='$to' OR `who_to`='$from')  AND (`who_from`='$from' OR `who_from`='$to') ORDER BY `chat`.`time` LIMIT 15;");
        while ($row = $items->fetch_assoc()) {
            $row["who_from"] = $auth->_queryUser($row["who_from"], 2);
            $row["who_to"] = $auth->_queryUser($row["who_to"], 2);
            $result['items'][] = $row;
        }
        print_r(json_encode($result));
    }
}
