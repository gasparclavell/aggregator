<?php

    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
    // PHP for storing common variables and methods
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

    // Local solution for CORS error
    /*header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');*/

    $servername = "localhost";

    //$username = "root";
    //$username = "id13324870_root";
    $username = "mytestappl";

    //$password = "";
    //$password = "Solviks.2020";
    $password = "pS57y7Xw";

    //$database = "solviks";
    //$database = "id13324870_aggregator";
    $database = "aggregator";

    // Function to create the databse and a user in case they do not exist
    function createDatabase($servername, $username, $password, $database) {

        try {

            // We connect to local host
            $raw_connection = mysqli_connect("localhost", "root", "", "") or die("Error en la conexion");

            // If the database does not exist, we create it
            mysqli_query($raw_connection, 'create database if not exists '.$database.'') or die("Problemas en la creación de la base de datos");

            // If the user does not exist, we create it
            mysqli_query($raw_connection, 'create user if not exists '.$username.'@'.$servername.' identified by "'.$password.'"');
            // We give him every permission to manipulate the last database
            mysqli_query($raw_connection, 'grant all on '.$database.'.* to '.$username.'@'.$servername.'');

            // We close the connection
            mysqli_close($raw_connection);

        } catch (Exception $e) {
            
        }  
    }
?>