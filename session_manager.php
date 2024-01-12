<?php

    function isUserLoggedIn() {
        return isset($_SESSION['name']);
        // || $_SESSION['name'] != NULL;
    }
    
    function loginUser($name) {
        $_SESSION['name'] = $name;
    }
    
    function logoutUser() {
        session_unset();
        session_destroy();
    }
    
    function getLoggedInUsername() {
        return isset($_SESSION['name']) ? $_SESSION['name'] : NULL;
    }

?>