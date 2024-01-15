<?php
    function getUserByEmail($email, $userFile='users/users.txt') {
        $users = fopen($userFile, 'r');
        while (!feof($users)) {
            list($userEmail, $userName, $userPass) = explode('|', fgets($users));
            $userPass = rtrim($userPass, "\r\n");
            if ($userEmail == $email) {
                return ['email' => $userEmail, 'name' => $userName, 'pass' => $userPass];
            }
        }
        return NULL;
    }

    function storeUser($data, $userFile='users/users.txt') {
        $file = fopen($userFile, 'a');
        $email = $data['email'];
        $name  = $data['name'];
        $pass  = $data['pass'];

        $line = implode('|', array($email, $name, $pass));
        fwrite($file, PHP_EOL . $line);
    }
?>