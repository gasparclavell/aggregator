<?php

    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
    // PHP for creating the databse
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

    include 'variables.php';

    try {

        // We create the database and a user in case they don't exist
        createDatabase($servername, $username, $password, $database);

        // We connect to the database
        $connection = mysqli_connect($servername, $username, $password, $database) or die("Error en la conexion");

        // We create the "admins" table in case it doesn't exist
        mysqli_query($connection, 'create table if not exists admins (email varchar(255), password varchar(64), primary key (email))') or die("Problemas en la creación de la tabla");

        // We create the "users" table in case it doesn't exist
        mysqli_query($connection, 'create table if not exists users (name varchar(64), surname varchar(64), id varchar(64), birthdate date, email varchar(255), password varchar(64), primary key (email))') or die("Problemas en la creación de la tabla");

        // The projects are removed first
        //mysqli_query($connection, 'drop table if exists projects') or die("Problemas en la creación de la tabla");

        // We create the "projects" table if it does not exist
        mysqli_query($connection, 'create table if not exists projects (name varchar(64), owner varchar(64), created datetime, severity varchar(64), primary key (name, owner))') or die("Problemas en la creación de la tabla");

        // We create the admin entries
        mysqli_query($connection, 'insert ignore into admins values("'."john.doe@solve-x.net".'", "'."1234".'")') or die("Problemas en el insert");

        // The users entries
        mysqli_query($connection, 'insert ignore into users values("'."name".'", "'."one".'", "'."000000001".'", "'."1987-01-01".'", "'."name.one@solve-x.net".'", "'."1234".'")') or die("Problemas en el insert");
        mysqli_query($connection, 'insert ignore into users values("'."name".'", "'."two".'", "'."000000002".'", "'."1989-01-01".'", "'."name.two@solve-x.net".'", "'."1234".'")') or die("Problemas en el insert");
        mysqli_query($connection, 'insert ignore into users values("'."name".'", "'."three".'", "'."000000003".'", "'."1987-01-01".'", "'."name.three@solve-x.net".'", "'."1234".'")') or die("Problemas en el insert");
        mysqli_query($connection, 'insert ignore into users values("'."name".'", "'."four".'", "'."000000004".'", "'."1977-01-01".'", "'."name.four@solve-x.net".'", "'."1234".'")') or die("Problemas en el insert");
        mysqli_query($connection, 'insert ignore into users values("'."name".'", "'."five".'", "'."000000005".'", "'."1993-01-01".'", "'."name.five@solve-x.net".'", "'."1234".'")') or die("Problemas en el insert");
        mysqli_query($connection, 'insert ignore into users values("'."name".'", "'."six".'", "'."000000006".'", "'."1990-01-01".'", "'."name.six@solve-x.net".'", "'."1234".'")') or die("Problemas en el insert");

        // The projects entries
        mysqli_query($connection, 'insert ignore into projects values("'."Example".'", "'."name.one@solve-x.net".'", "'."2020-03-16 12:00:00".'", "'."7".'")') or die("Problemas en el insert");
        mysqli_query($connection, 'insert ignore into projects values("'."Example".'", "'."name.two@solve-x.net".'", "'."2020-03-19 12:00:00".'", "'."7".'")') or die("Problemas en el insert");
        mysqli_query($connection, 'insert ignore into projects values("'."Example".'", "'."name.three@solve-x.net".'", "'."2020-03-17 12:00:00".'", "'."7".'")') or die("Problemas en el insert");
        mysqli_query($connection, 'insert ignore into projects values("'."Example".'", "'."name.four@solve-x.net".'", "'."2020-03-20 12:00:00".'", "'."7".'")') or die("Problemas en el insert");
        mysqli_query($connection, 'insert ignore into projects values("'."Example".'", "'."name.five@solve-x.net".'", "'."2020-03-23 12:00:00".'", "'."7".'")') or die("Problemas en el insert");
        mysqli_query($connection, 'insert ignore into projects values("'."Example".'", "'."name.six@solve-x.net".'", "'."2020-03-18 12:00:00".'", "'."7".'")') or die("Problemas en el insert");

        echo "Fine!";

        // We close the connection
        mysqli_close($connection);

    } catch(mysqli_sql_exception $e) {
        
    }
?>