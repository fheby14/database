<?php

require 'function.php';

if(isset($_SESSION['Login'])){
    //yaudah
} else {
    //belum login
    header('location:login.php');
}

?>
