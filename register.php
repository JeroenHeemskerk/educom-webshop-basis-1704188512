<?php
    function registerHeader() {
        $header = 'Registreer Pagina';
        return $header;
    }

    function showRegisterContent() {
        $valsAndErrs = validation();
        if ($valsAndErrs['valid']) {
            displayThanks($valsAndErrs);
        } else {
            displayForm($valsAndErrs);
        }
    }
    
    function displayThanks($valsAndErrs) {
        echo '<p>U bent geregistreerd.<p>
<div>Email: ' . $valsAndErrs['email'] . ' </div>' . PHP_EOL;
    }
    
    function displayForm($valsAndErrs) {
        
        echo '<h4>Vul uw gegevens in om te registreren</h4>' . PHP_EOL;
        
        echo '    <form method="post" action="index.php" accept-charset=utf-8>
        <input type="hidden" name="page" value="register">' . PHP_EOL;
        
        echo '        <div class="inputfield">
            <label for="name">Naam:</label>
            <input type="text" value="' . $valsAndErrs['name'] . '" id="name" name="name">
            <span class="error">' . $valsAndErrs['nameErr'] . '</span><br>
        </div>' . PHP_EOL;
        
        echo '        <div class="inputfield">
            <label for="email">Email:</label>
            <input type="email" value="' . $valsAndErrs['email'] . '" id="email" name="email">
            <span class="error">' . $valsAndErrs['emailErr'] . '</span><br>
        </div>' . PHP_EOL;
        
        echo '        <div class="inputfield">
            <label for="pass">Wachtwoord:</label>
            <input type="text" value="' . $valsAndErrs['pass'] . '" id="pass" name="pass">
            <span class="error">' . $valsAndErrs['passErr'] . '</span><br>
        </div>' . PHP_EOL;
        
        echo '        <div class="inputfield">
            <label for="passConfirm">Wachtwoord Herhalen:</label>
            <input type="text" value="' . $valsAndErrs['passConfirm'] . '" id="passConfirm" name="passConfirm">
            <span class="error">' . $valsAndErrs['passConfirmErr'] . '</span><br>
        </div><br>' . PHP_EOL;
        
        echo '        <input type="submit" value="Registreer">
    </form>' . PHP_EOL;
    }


    function validation() {
        $name = $email = $pass = $passConfirm = '';
        $nameErr = $emailErr = $passErr = $passConfirmErr = '';
        $valid = false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //check name (only allow letters, spaces, dashes and apostrophes)
            if (empty(getPostVar("name"))) {
                $nameErr = "Vul uw naam in";
            } else {
                $name = testInput(getPostVar("name"));
                if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                    $nameErr= "Alleen letters, streepjes en apostrophen zijn toegestaan	";
                }
            }
            
            $email = testInput(getPostVar("email"));
            //check email (use built-in filter method)
            if (empty($email)) {
                $emailErr = "Vul uw email in";
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "ongeldig email";
            } else if (checkEmailExists($email)) {
                $emailErr = "Dit email adres heeft al een account";
            }
            
            $pass = testInput(getPostVar("pass"));
            if (empty($pass)) {
                $passErr = "Vul een wachtwoord in";
            }
            $passConfirm = testInput(getPostVar('passConfirm'));
            if ($pass != $passConfirm) {
                $passConfirmErr = 'Wachtwoorden moeten gelijk zijn.';
            }
            
            
            
            //update valid boolean after all error checking
            $valid = empty($nameErr) && empty($emailErr) && empty($passErr) && empty($passConfirmErr);
        }
        $valsAndErrs = array('valid'=>$valid, 'name'=>$name, 'email'=>$email, 'pass'=>$pass, 'passConfirm'=>$passConfirm,
                               'nameErr'=>$nameErr, 'emailErr'=>$emailErr, 'passErr'=>$passErr, 'passConfirmErr'=>$passConfirmErr);
        return $valsAndErrs;
    }

    function checkEmailExists($email) {
        $users = fopen('users/users.txt', 'r');
        while (!feof($users)) {
            $userEmail = explode('|', fgets($users))[0];
            if ($userEmail == $email) {
                return true;
            }
        }
        return false;
    }
?>