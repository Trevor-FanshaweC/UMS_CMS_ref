<?php
    include('connect.php');

    function getAllUsers($conn) {
        $query = 'SELECT * FROM users';

        $getAllUsers = $conn->prepare($query);
        $getAllUsers->execute();

        $users = $getAllUsers->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    }
    //TODO => add the insert query and parse thru the post array for entries
    // to build out the values and params
    
    function addUser($conn) {
        //echo "hit add user route";

        $allPostEntries = filter_input_array(INPUT_POST);

        return $allPostEntries;

        //return "need to finish the addUser function - stripping POST is workin";
    }

    function deleteUser($conn, $userID) {
        $query = "DELETE FROM users WHERE id=:uID";

        $removeUser = $conn->prepare($query);
        $count = $removeUser->execute(array(':uID' => $userID));

        // this will just return a boolean for success or failure - true ($count)
        // or a message if it's false (can't delete)
        if ($count > 0) {
            return $count;
        } else {
            return "couldn't delete user";
        }
    }