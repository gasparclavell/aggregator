<?php

    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
    // PHP for renaming a project
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

    include 'variables.php';

    $new = $_REQUEST["nn"];
    $old = $_REQUEST["n"];
    $e = $_REQUEST["e"];

    try {

        // We connect to the database
        $connection = mysqli_connect($servername, $username, $password, $database) or die("Error en la conexion");

        // We update the name of the project
        mysqli_query($connection, 'update projects set name = "'.$new.'" WHERE owner = "'.$e.'" and name = "'.$old.'"') or die("Problemas en el insert");

        echo "Fine!";

        // We close the connection
        mysqli_close($connection);

    } catch(mysqli_sql_exception $e) {
        
    }
?>