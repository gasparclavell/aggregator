<?php

    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
    // PHP for updating the severity level of a project
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

    include 'variables.php';

    $e = $_REQUEST["e"];
    $s = $_REQUEST["s"];
    $n = $_REQUEST["n"];

    //$e = "name.one@solve-x.net";
    //$s = "notification";
    //$n = "Project 1";

    try {

        // We connect to the database
        $connection = mysqli_connect($servername, $username, $password, $database) or die("Error en la conexion");

        // We update the severity of the project
        mysqli_query($connection, 'update projects set severity = "'.$s.'" WHERE owner = "'.$e.'" and name = "'.$n.'"') or die("Problemas en el insert");

        echo "Fine!";

        // We close the connection
        mysqli_close($connection);

    } catch(mysqli_sql_exception $e) {
        
    }
?>