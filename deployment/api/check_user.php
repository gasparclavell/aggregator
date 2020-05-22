<?php

    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
    // PHP for checking if a user account exists
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

    include 'variables.php';

    // We get the email to check from POST method
    $e = $_REQUEST["e"];
    $k = $_REQUEST["k"];
    //$e = "name.one@solve-x.net";
    //$k = "1234";

    // Boolean for checking if the email exists in users table
    $match = false;

    try {

        // We connect to the database
        $connection = mysqli_connect($servername, $username, $password, $database) or die("Error en la conexion");

        // We select the entry that has the same email as the one from the url
        $registros = mysqli_query($connection, 'select count(email) as number from users where email = "'.$e.'" and password = "'.$k.'"') or die("Problemas en la select");

        if ($reg = mysqli_fetch_array($registros)) {
            if($reg['number'] > 0) {
                $match = true;
            }
        }

        // We close the connection
        mysqli_close($connection);

    } catch(mysqli_sql_exception $e) {
        
    }

    // We print the boolean so we can work with Ajax
    echo $match;
?>