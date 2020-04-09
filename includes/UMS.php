<?php
    include('functions.php');

    if (isset($_GET["get_form"])) {
        $result = getUserFormFields($pdo);
    }

    //check the request params as they come in
    if (isset($_GET["get_users"])) {
        $result = getAllUsers($pdo);
    }

    if (isset($_GET["delete_user"])) {
        $userID = $_GET["user_id"];
        $result = deleteUser($pdo, $userID);
    }

    if (isset($_GET["add_user"])) {
        $result = addUser($pdo);
    }

    echo json_encode($result);

    // could add other if statements here for a UMS system like adding or patching a user
    // refer to pan's example file