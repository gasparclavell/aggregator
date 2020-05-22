<?php

    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
    // PHP for removing a client
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

    include 'variables.php';

    $e = $_REQUEST["e"];
    $k = $_REQUEST["k"];
    //$e = "name.two@solve-x.net";
    //$k = "1234";

    $id = "";

    try {

        // We connect to the database
        $connection = mysqli_connect($servername, $username, $password, $database) or die("Error en la conexion");

        // We select the ID from the deleted client
        $registros = mysqli_query($connection, 'select id as id from users where email = "'.$e.'" and password = "'.$k.'"') or die("Problemas en la select");

        if ($reg = mysqli_fetch_array($registros)) {

            $id = $reg['id'];
        }

        // We delete the matching client
        mysqli_query($connection, 'delete from users where email ="'.$e.'" and password = "'.$k.'"') or die("Problemas en el insert");

        // The profile picture is removed
        if($id != "000000001" && $id != "000000002" && $id != "000000003" && $id != "000000004" && $id != "000000005" && $id != "000000006") {
            if(file_exists("assets/images/".$id.".jpg")) {
                unlink("assets/images/".$id.".jpg");
            }            
        }

        echo "Fine!";

        // We close the connection
        mysqli_close($connection);

    } catch(mysqli_sql_exception $e) {
        
    }
?>