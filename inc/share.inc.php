<?php
require 'dbh.inc.php';
require 'errors/error.inc.php';

try {
    $user = $_POST["id"];

    $sql = "INSERT INTO `share`(`user`) VALUE ($user)";
    print_r(
        json_encode(
            array(
                'code' => 21,
                "type" => 'successful'
            )
        )
    );
} catch (\Throwable $th) {
    $error->err($_SESSION['userId'], 26, "Share could not be updated.");
}
