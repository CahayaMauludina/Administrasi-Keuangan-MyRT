<?php 
    $db = mysqli_connect('localhost', 'root', 'root', 'myrt');
    if (mysqli_connect_error() == true) {
        die('Gagal terhubung ke database');
        return false;
    } else {
        return true;
    }
    
    function query($query) {
        global $db;
        $result = mysqli_query($db, $query);
        $rows = [];
        while( $row = mysqli_fetch_assoc($result) ) {
            $rows[] = $row;
        }
        return $rows;
    }
?>