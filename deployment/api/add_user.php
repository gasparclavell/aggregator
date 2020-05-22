<?php

    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
    // PHP for uploading new users into the database
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

    include 'variables.php';

    $n = $_REQUEST["n"];
    $s = $_REQUEST["s"];
    $i = $_REQUEST["i"];
    $b = $_REQUEST["b"];
    $e = $_REQUEST["e"];
    $p = $_REQUEST["p"];

    try {

        // We connect to the database
        $connection = mysqli_connect($servername, $username, $password, $database) or die("Error en la conexion");

        // We insert the user values
        mysqli_query($connection, 'insert ignore into users values("'.$n.'", "'.$s.'", "'.$i.'", "'.$b.'", "'.$e.'", "'.$p.'")') or die("Problemas en el insert");

        echo "Fine!";

        // We close the connection
        mysqli_close($connection);

    } catch(mysqli_sql_exception $e) {
        
    }
?>