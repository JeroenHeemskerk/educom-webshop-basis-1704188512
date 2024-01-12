<?php
    function doesEmailExist($email) {
        require_once('file_repository.php');
        if (getUserByEmail($email)== NULL) {
            return false;
        }
        return true;
    }

    function authorizeUser($email, $pass) {
        require_once('file_repository.php');
        $user = getUserByEmail($email);
        if ($user == NULL || $user['pass'] != $pass) {
            return NULL;
        }
        return $user['name'];
    }

    function addUser($data) {
        require_once('file_repository.php');
        storeUser($data);
    }
?>