<?php

    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
    // PHP for getting the projects
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

    include 'variables.php';

    try {

        $mysqli = new mysqli($servername, $username, $password, $database);

        if ($mysqli -> connect_errno) {
          echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
          exit();
        }

        $sql = "SELECT * FROM projects";

        $array = [];

        if ($result = $mysqli -> query($sql)) {
            while ($row = $result -> fetch_row()) {
                array_push($array, $row);
            }
            $result -> free_result();
        }

        echo json_encode($array);

        $mysqli -> close();

    } catch (Exception $e) {
        
    }
?>