<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
header('content-type: application/json');
session_start();
//auth check
$un_ravel->_isAuth();


// get people the user follows from `following` table and from `chat` table get last message between them
if (isset($_GET['user'])) {
    // Use prepared statements to prevent SQL injection
    $user = $un_ravel->_getUser($_GET['user']);
    $stmt = $conn->prepare("SELECT * FROM `following` WHERE `user`=?");
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // Initialize an empty array for the answer
    $answer = [];
    $i = 0;
    // Check if the result is not empty
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Get the following user id
            $f = $row['following'];

            // Use another prepared statement to get the user details and auth key
            $stmt = $conn->prepare("SELECT `idusers`,`uidusers`,`usersFirstname`,`usersSecondname`,`last_online`,`profile_picture`,`token`,`chat_auth` FROM `users`,`auth_key` WHERE `users`.`idusers` = ? AND `auth_key`.`user` = ?");
            $stmt->bind_param("ii", $f, $f);
            $stmt->execute();
            // Get the result as an associative array
            $row = $stmt->get_result()->fetch_assoc();
            // Close the statement
            $stmt->close();

            // Check if the result is not empty
            if (!is_null($row)) {
                // Add the user details to the answer array
                $answer['users'][$i] = $row;
                $user_id = $row['idusers'];
                $last_online = $row['last_online'];
                // Use trim to remove any extra spaces from the full name
                $answer['users'][$i]['full_name'] = trim($row['usersFirstname'] . " " . $row['usersSecondname']);
                // if full name is empty then set it to username
                if ($answer['users'][$i]['full_name'] == "") {
                    $answer['users'][$i]['full_name'] = $row['uidusers'];
                }

                // Prepare the statement
                $sql = "SELECT `message`,`time`,`type` FROM `chat` WHERE (`who_to`='$user_id' OR `who_to`='$user') AND (`who_from`='$user_id' OR `who_from`='$user') ORDER BY `chat`.`time` DESC LIMIT 1";
                $r = $conn->query($sql)->fetch_assoc();



                if (!is_null($r)) {
                    $time = $r['time'];
                    $type = $r['type'];
                    $message = $r['message'];
                    // Use DateTime objects for easier manipulation and formatting of dates and times
                    // Set the timezone to UTC for consistency
                    date_default_timezone_set('UTC');
                    // Create a DateTime object from the time string
                    $date = new DateTime($time);
                    // Get the day of the week as a string
                    $answer['users'][$i]['day'] = $date->format('l');
                    // Get the last message as a string
                    $answer['users'][$i]['last_msg'] = $message;
                    // Get the time in 12 hour format as a string
                    $answer['users'][$i]['time'] = $date->format('g:i a');
                    // Get the time in ISO format as a string
                    $answer['users'][$i]['actual_time'] = date('c', strtotime($time));
                    // Get the type as a string
                    $answer['users'][$i]['type'] = $type;
                } else {
                    // Set empty values if there is no last message
                    $answer['users'][$i]['last_msg'] = "";
                    $answer['users'][$i]['type'] = "";
                    // Set null values for actual_time and day to avoid errors in sorting later
                    $answer['users'][$i]['actual_time'] = null;
                    $answer['users'][$i]['day'] = null;
                }

                // Check if the user is online by comparing their last online time with the current time minus 7 minutes
                date_default_timezone_set('UTC');
                // Create a DateTime object for the current time minus 7 minutes
                $now_minus_7_minutes = new DateTime('now');
                date_sub($now_minus_7_minutes, date_interval_create_from_date_string('7 minutes'));
                // Create a DateTime object for the last online time
                $last_online_date = new DateTime($last_online);
                // Compare the two DateTime objects and set the online status accordingly
                if ($last_online_date > $now_minus_7_minutes) {
                    $answer['users'][$i]['online'] = true;
                } else {
                    $answer['users'][$i]['online'] = false;
                }

                // Increment the index
                $i++;
            } else {
                // Skip the user if they are not in the users table
                continue;
            }
        }

        // Sort the array based on last_online in descending order
        usort($answer['users'], function ($a, $b) {
            if (isset($a['last_online']) && isset($b['last_online'])) {
                return strtotime($b['last_online']) - strtotime($a['last_online']);
            } elseif (isset($a['last_online'])) {
                return -1; // Place the item with last_online first
            } elseif (isset($b['last_online'])) {
                return 1; // Place the item with last_online first
            } else {
                return 0; // Keep the order unchanged
            }
        });

        // Sort the array based on actual_time in descending order
        usort($answer['users'], function ($a, $b) {
            if (isset($a['actual_time']) && isset($b['actual_time'])) {
                return strtotime($b['actual_time']) - strtotime($a['actual_time']);
            } elseif (isset($a['actual_time'])) {
                return -1; // Place the item with actual_time first
            } elseif (isset($b['actual_time'])) {
                return 1; // Place the item with actual_time first
            } else {
                return 0; // Keep the order unchanged
            }
        });
        // Encode the answer array as a JSON string and print it
        print_r(json_encode($answer));
    } else {
        // Print an error message if the result is empty
        print_r(json_encode(array('users' => [])));
    }
}
