<?php
    function loginHeader() {
        $header = 'Login Pagina';
        return $header;
    }
    
    function showLoginContent() {
        $valsAndErrs = validation();
        if ($valsAndErrs['valid']) {
            displayThanks($valsAndErrs);
        } else {
            displayForm($valsAndErrs);
        }
    }
    
    function displayForm($valsAndErrs) {
        
        echo '<h4>Vul uw gegevens in om te registreren</h4>' . PHP_EOL;
        
        showFormStart('login');
        
        //input for email
        showFormField('email', 'Email:', 'email', $valsAndErrs);
        
        //input for password
        showFormField('pass', 'Wachtwoord:', 'password', $valsAndErrs);

        echo '        <br><br>' . PHP_EOL;

        showFormEnd('Login');
    }


    function validation() {
        $email = $pass = '';
        $emailErr = $passErr = '';
        $valid = false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //check email
            $email = testInput(getPostVar("email"));
            $emailErr = validateEmail($email);
            if (!doesEmailExist($email)) {
                $emailErr = "Dit email adres heeft geen account";
            }
            
            $pass = testInput(getPostVar("pass"));
            if (empty($pass)) {
                $passErr = "Vul een wachtwoord in";
            } elseif (empty($emailErr) && !validateLogin($email, $pass)) {
                $passErr = "Ongeldig wachtwoord";
            }
            
            //update valid boolean after all error checking
            $valid = empty($nameErr) && empty($emailErr) && empty($passErr) && empty($passConfirmErr);
        }
        $valsAndErrs = array('valid'=>$valid, 'name'=>$name, 'email'=>$email, 'pass'=>$pass, 'passConfirm'=>$passConfirm,
                             'nameErr'=>$nameErr, 'emailErr'=>$emailErr, 'passErr'=>$passErr, 'passConfirmErr'=>$passConfirmErr);
        return $valsAndErrs;
    }
    
    function validateLogin($email, $pass) {
        $users = fopen('users/users.txt', 'r');
        while (!feof($users)) {
            list($userEmail, $userName, $userPass) = explode('|', fgets($users));
            if ($userEmail == $email && $userPass == $pass) {
                return true;
            }
        }
        return false;
    }
?>