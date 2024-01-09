<?php

    $header = 'Registreer Pagina';

    function showRegisterContent() {
        $vals_and_errs = validation();
        if ($vals_and_errs['valid']) {
            display_thanks($vals_and_errs);
        } else {
            display_form($vals_and_errs);
        }
    }
    
    function display_thanks($vals_and_errs) {
        echo '<p>U bent geregistreerd.<p>
<div>Email: ' . $vals_and_errs['email'] . ' </div>' . PHP_EOL;
    }
    
    function display_form($vals_and_errs) {
        
        echo '<h4>Vul uw gegevens in om te registreren</h4>' . PHP_EOL;
        
        echo '    <form method="post" action="index.php" accept-charset=utf-8>
        <input type="hidden" name="page" value="register">' . PHP_EOL;
        
        echo '        <div class="inputfield">
            <label for="name">Naam:</label>
            <input type="text" value="' . $vals_and_errs['name'] . '" id="name" name="name">
            <span class="error">' . $vals_and_errs['nameErr'] . '</span><br>
        </div>' . PHP_EOL;
        
        echo '        <div class="inputfield">
            <label for="email">Email:</label>
            <input type="email" value="' . $vals_and_errs['email'] . '" id="email" name="email">
            <span class="error">' . $vals_and_errs['emailErr'] . '</span><br>
        </div>' . PHP_EOL;
        
        echo '        <div class="inputfield">
            <label for="pass">Wachtwoord:</label>
            <input type="text" value="' . $vals_and_errs['pass'] . '" id="pass" name="pass">
            <span class="error">' . $vals_and_errs['passErr'] . '</span><br>
        </div>' . PHP_EOL;
        
        echo '        <div class="inputfield">
            <label for="pass_confirm">Wachtwoord Herhalen:</label>
            <input type="text" value="' . $vals_and_errs['pass_confirm'] . '" id="pass_confirm" name="pass_confirm">
            <span class="error">' . $vals_and_errs['pass_confirmErr'] . '</span><br>
        </div><br>' . PHP_EOL;
        
        echo '        <input type="submit" value="Registreer">
    </form>' . PHP_EOL;
    }


    function validation() {
        $name = $email = $pass = $pass_confirm = '';
        $nameErr = $emailErr = $passErr = $pass_confirmErr = '';
        $valid = false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //check name (only allow letters, spaces, dashes and apostrophes)
            if (empty($_POST["name"])) {
                $nameErr = "Vul uw naam in";
            } else {
                $name = test_input($_POST["name"]);
                if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                    $nameErr= "Alleen letters, streepjes en apostrophen zijn toegestaan	";
                }
            }
            
            $email = test_input($_POST["email"]);
            //check email (use built-in filter method)
            if (empty($email)) {
                $emailErr = "Vul uw email in";
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "ongeldig email";
            } else if (checkEmailExists($email)) {
                $emailErr = "Dit email adres heeft al een account";
            }
            
            $pass = test_input($_POST["pass"]);
            if (empty($pass)) {
                $passErr = "Vul een wachtwoord in";
            }
            $pass_confirm = test_input($_POST['pass_confirm']);
            if ($pass != $pass_confirm) {
                $pass_confirmErr = 'Wachtwoorden moeten gelijk zijn.';
            }
            
            
            
            //update valid boolean after all error checking
            $valid = empty($nameErr) && empty($emailErr) && empty($passErr) && empty($pass_confirmErr);
        }
        $vals_and_errs = array('valid'=>$valid, 'name'=>$name, 'email'=>$email, 'pass'=>$pass, 'pass_confirm'=>$pass_confirm,
                               'nameErr'=>$nameErr, 'emailErr'=>$emailErr, 'passErr'=>$passErr, 'pass_confirmErr'=>$pass_confirmErr);
        return $vals_and_errs;
    }
    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    function checkEmailExists($email) {
        $users = fopen('users/users.txt', 'r');
        while (!feof($users)) {
            $user_email = explode('|', fgets($users))[0];
            if ($user_email == $email) {
                return true;
            }
        }
        return false;
    }
?>