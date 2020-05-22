<?php

    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
    // PHP for removing all the projects from a client
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

    include 'variables.php';

    $e = $_REQUEST["e"];
    //$n = "Project 1";
    //$e = "name.one@solve-x.net";

    try {

        // We connect to the database
        $connection = mysqli_connect($servername, $username, $password, $database) or die("Error en la conexion");

        // We delete the matching project
        mysqli_query($connection, 'delete from projects where owner = "'.$e.'"') or die("Problemas en el insert");

        echo "Fine! remove user projects";

        // We close the connection
        mysqli_close($connection);

    } catch(mysqli_sql_exception $e) {
        
    }

?>