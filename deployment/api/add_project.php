<?php

    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
    // PHP for uploading new projects into de database
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

    include 'variables.php';

    $n = $_REQUEST["n"];
    $e = $_REQUEST["e"];
    $c = $_REQUEST["c"];
    $s = $_REQUEST["s"];

    try {

        // We connect to the database
        $connection = mysqli_connect($servername, $username, $password, $database) or die("Error en la conexion");

        // We insert the project values into the database
        mysqli_query($connection, 'insert ignore into projects values("'.$n.'", "'.$e.'", "'.$c.'", "'.$s.'")') or die("Problemas en el insert");

        echo "Fine!";

        // We close the connection
        mysqli_close($connection);

    } catch(mysqli_sql_exception $e) {
        
    }
?>