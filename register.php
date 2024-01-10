<?php
    function registerHeader() {
        $header = 'Registratie Pagina';
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
        
        showFormStart('register');
        
        //input for name
        showFormField('name', 'Naam:', 'text', $valsAndErrs);
        //input for email
        showFormField('email', 'Email:', 'text', $valsAndErrs);
        
        //input for password
        showFormField('pass', 'Wachtwoord:', 'text', $valsAndErrs);
        showFormField('passConfirm', 'Wachtwoord Herhalen:', 'text', $valsAndErrs);

        echo '        <br>' . PHP_EOL;

        showFormEnd('Registreer');
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