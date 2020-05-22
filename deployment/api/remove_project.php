<?php

    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
    // PHP for removing a project
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

    include 'variables.php';

    $n = $_REQUEST["n"];
    $e = $_REQUEST["e"];
    //$n = "Project 1";
    //$e = "name.one@solve-x.net";

    try {

        // We connect to the database
        $connection = mysqli_connect($servername, $username, $password, $database) or die("Error en la conexion");

        // We delete the matching project
        mysqli_query($connection, 'delete from projects where name ="'.$n.'" and owner = "'.$e.'"') or die("Problemas en el insert");

        echo "Fine!";

        // We close the connection
        mysqli_close($connection);

    } catch(mysqli_sql_exception $e) {
        
    }
    
?>