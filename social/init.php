<?php

        include 'connection.php';

        $tmbl = "inc/tmbl/";
    
        include $tmbl . 'header.php';
    
        if(!isset($nonav))
        {
            include $tmbl . 'nav.php';
        }