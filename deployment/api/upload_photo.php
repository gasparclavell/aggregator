<?php

	/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
    // PHP for uploading a client profile photo
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

    include 'variables.php';

    $i = $_REQUEST["i"];

    if(isset($_FILES['file'])) {

        if ( 0 < $_FILES['file']['error'] ) {
            echo 'Error: ' . $_FILES['file']['error'] . '<br>';
        }
        else {
            move_uploaded_file($_FILES['file']['tmp_name'], 'assets/images/'.$i.".jpg");
            echo "Fine!";
        }
    }
?>