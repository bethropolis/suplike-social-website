<?php

/**
 * Create a session token for a given user.
 *
 * @param int $user The user for whom the session token is created.
 * @throws Exception If there is an error generating the session token.
 * @return string The generated session token.
 */
function create_session_token($user)
{
    global $conn;

    function generate_session_token()
    {
        $length = 32;
        $bytes = random_bytes($length);
        return substr(bin2hex($bytes), 0, $length);
    }

    $session_token = generate_session_token();

    $stmt_check = $conn->prepare("SELECT session_id FROM session WHERE user_id = ?");
    $stmt_check->bind_param("i", $user);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $stmt_update = $conn->prepare("UPDATE session SET session_id = ? WHERE user_id = ?");
        $stmt_update->bind_param("si", $session_token, $user);
        $stmt_update->execute();
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO session (session_id, user_id) VALUES (?, ?)");
        $stmt_insert->bind_param("si", $session_token, $user);
        $stmt_insert->execute();
    }

    return $session_token;
}

/**
 * Retrieves the user ID associated with the session token.
 *
 * @param string $session_token The session token to retrieve the user ID for.
 * @throws Exception If there is an error retrieving the user ID.
 * @return int|null The user ID associated with the session token, or null if no user ID is found.
 */

function get_user_id_from_session_token($session_token)
{
    global $conn;

    // Retrieve the user ID associated with the session token
    $stmt = $conn->prepare("SELECT user_id FROM session WHERE session_id = ?");
    $stmt->bind_param("s", $session_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['user_id'];
    } else {
        return null; // No user ID found for the given session token
    }
}
