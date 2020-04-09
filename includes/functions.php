<?php
    include('connect.php');

    function getUserFormFields($conn) {
        $query = "DESCRIBE users";
        $result = $conn->query($query);

        //$fields = [];
        $form = "";

        // build the form on the server side and send it to the client
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($row["Field"] !== "id") {
                //$fields[] = $row["Field"];

                $inputType = "text";
                
                $form .= "<label for='" . $row["Field"] . "'>" .$row["Field"] . "</label>";

                if ($row["Field"] == "plevel") { $inputType = "number" ; }
                elseif ($row["Field"] == "isadmin") { $inputType = "checkbox"; }

                $form .= "<input type='" . $inputType . "' name='" . $row["Field"] . "' id='" . $row['Field'] . "'>";
                $form .= "<input type='submit' value='Submit'>";
            }
        }
        // return $fields;
        return $form;
    }

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

        $allPostEntries = filter_input_array(INPUT_POST);

        // get the variables ready!
        $queryPlaceholder = [];

        foreach($allPostEntries as $item) {
            $queryPlaceholder[] = '?';
        }

        $insert_fields = implode(',', array_keys($allPostEntries));
        $insert_values = implode(',', array_values($queryPlaceholder));

        //echo $insert_fields;
       // echo "values:" . $insert_values;

        $query = "INSERT INTO users ($insert_fields) VALUES ($insert_values)";

        //echo $query;

        $result = $conn->prepare($query);
        $addedUser = $result->execute(array_values($allPostEntries));

        if ($addedUser) {
            $newID = $conn->lastInsertId();
            $query = "SELECT * FROM users WHERE id='" . $newID . "'";

            $newUser = $conn->query($query);
            return $newUser->fetch(PDO::FETCH_ASSOC);
        } else {
            return array("result" => false);
        }
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