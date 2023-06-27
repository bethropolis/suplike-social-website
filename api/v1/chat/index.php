
<?php
require '../r.php';
$upload_dir = "/image/";


// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from = $un_ravel->_getUser(filter_input(INPUT_POST, 'from'));
    $to = $un_ravel->_getUser(filter_input(INPUT_POST, 'to'));
    $message = filter_input(INPUT_POST, 'message');
    $type = filter_input(INPUT_POST, 'type') ?: 'txt';

    // check if session is valid
    authentication_check($from);

    if (!$from || !$to) {
        return print_r(json_encode([
            'code' => 1,
            'msg' => "missing parameters",
            'type' => 'error'
        ]));
    }

    if ($from === $to) {
        return print_r(json_encode([
            'code' => 6,
            'msg' => "cannot message yourself",
            'type' => 'error'
        ]));
    }

    if (empty($message) && $type === 'txt') {
        return print_r(json_encode([
            'code' => 1,
            'msg' => "message empty",
            'type' => 'error'
        ]));
    }

    // Sanitize and validate user input
    $from = mysqli_real_escape_string($conn, $from);
    $to = mysqli_real_escape_string($conn, $to);
    $message = mysqli_real_escape_string($conn, $message);
    $message = str_replace('\\', '\\\\', $message);
    $message = str_replace('"', '\\"', $message);
    $message = str_replace("\n", '\\n', $message);

    // Prepare and execute SQL statement
    $sql = "INSERT INTO `chat` (`who_from`, `who_to`, `message`, `type`) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($type === 'txt' || $type === 'share') {
        mysqli_stmt_bind_param($stmt, "ssss", $from, $to, $message, $type);
    } else if ($type === 'img' || $type === 'mus' || $type === 'vid') {
        // Upload file and get URL
        $file_url = upload_file($_FILES['file'], $type);

        mysqli_stmt_bind_param($stmt, "ssss", $from, $to, $file_url, $type);
    } else {
        return print_r(json_encode([
            'code' => 1,
            'msg' => "invalid message type",
            'type' => 'error'
        ]));
    }

    if (!mysqli_stmt_execute($stmt)) {
        return print_r(json_encode([
            'code' => 2,
            'msg' => "database error",
            'type' => 'error'
        ]));
    }
    $chatId = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);

    if($un_ravel->_isBot($to)){
        $bot->setBot($to);
        $bot->send("chat", $_POST['from'], $id);
    }

    return print_r(json_encode([
        'code' => 0,
        'msg' => "message sent",
        'type' => 'success'
    ]));
}


function upload_file($file, $type)
{
    global $upload_dir;
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
            'msg' => "Invalid file type"
        ];
    }

    // Check for file upload errors
    if ($file_error !== UPLOAD_ERR_OK) {
        return [
            'code' => 1,
            'msg' => "File upload error"
        ];
    }

    // Generate unique file name and move file to destination
    $file_name_new = uniqid('', true) . '.' . $file_extension;
    $file_destination = $upload_dir . $file_name_new;
    if (!move_uploaded_file($file_tmp,  "../../../inc" . $file_destination)) {
        return [
            'code' => 1,
            'msg' => "File upload error"
        ];
    }

    // Return file destination
    return $file_destination;
}


function uploadImage($file)
{
    global $upload_dir;

    // Check if file was uploaded successfully
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return [
            'code' => 1,
            'msg' => "failed to upload image",
            'type' => 'error'
        ];
    }

    // Generate a unique filename for the uploaded image
    $filename = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);

    // Move the uploaded file to a directory on the server
    $upload_path = $upload_dir . $filename;

    try {
        if (!move_uploaded_file($file['tmp_name'], "../../../inc" . DIRECTORY_SEPARATOR . $upload_path)) {
            throw new Exception('Failed to move uploaded file');
        }
    } catch (Exception $e) {
        return [
            'code' => 1,
            'msg' => "failed to upload image",
            'type' => 'error'
        ];
    }

    // Return the URL of the uploaded image
    return $upload_path;
}




if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if required query parameters are set
    $from = filter_input(INPUT_GET, 'from');
    $to = filter_input(INPUT_GET, 'to');
    $start = filter_input(INPUT_GET, 'start', FILTER_VALIDATE_INT);
    if (!$from || !$to || !$start) {
        $response = [
            'code' => 4,
            'msg' => 'missing required parameters',
            'type' => 'error'
        ];
        echo json_encode($response);
        exit;
    }



    // Get the request parameters
    $from = $un_ravel->_getUser($from);
    $to = $un_ravel->_getUser($to);


    // check if session is valid
    authentication_check($from);


    // Define an array of non-text message types
    $nonTextTypes = array('img', 'vid', 'mus');

    // Query the database for messages
    $stmt = $conn->prepare("(SELECT `who_from`,`id`, `who_to`, `type`, `time`, 
   CASE WHEN `type` IN (" . implode(',', array_map(function ($type) {
        return "'" . $type . "'";
    }, $nonTextTypes)) . ") THEN CONCAT('" . BASE_URL . "../inc', `message`) ELSE `message` END AS `message` 
FROM `chat` 
WHERE id > ? AND ((who_from = ? AND who_to = ?) OR (who_from = ? AND who_to = ?)) 
ORDER BY id DESC LIMIT 20 )
ORDER BY id ASC;");
    $stmt->bind_param("issss", $start, $from, $to, $to, $from);
    $stmt->execute();
    $result = $stmt->get_result();

    // Build an array of message objects
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        // Get the auth key for the from and to users
        $from_auth = $un_ravel->_queryUser($row['who_from'], 2);
        $to_auth = $un_ravel->_queryUser($row['who_to'], 2);

        // Reverse the double-escaping for PHP
        $msg = $row['message'];
        $row['message'] = str_replace('\\n', "\n", $row['message']);
        $row['message'] = str_replace('\\"', '"', $row['message']);
        $row['message'] = str_replace('\\\\', '\\', $row['message']);

        // Reverse the escaping for SQL
        $message = stripslashes($row['message']);

        $message_obj = [
            'id' => $row['id'],
            'from' => $from_auth,
            'to' => $to_auth,
            'original' => $msg,
            'message' => $message,
            'timestamp' => $row['time'],
            'type' => $row['type'],
        ];
        $messages[] = $message_obj;
    }

    if (count($messages) > 0) {
        // Return the array of message objects
        $response = [
            'code' => 0,
            'msg' => 'messages fetched',
            'type' => 'success',
            'data' => $messages
        ];
        echo json_encode($response);
    } else {
        // No messages found
        $response = [
            'code' => 3,
            'msg' => 'no messages found',
            'type' => 'error'
        ];
        echo json_encode($response);
    }
}
