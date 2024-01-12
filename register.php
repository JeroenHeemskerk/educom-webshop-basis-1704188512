<?php
    function registerHeader() {
        $header = 'Registratie Pagina';
        return $header;
    }

    function showRegisterContent() {
        $valsAndErrs = validation();
        if ($valsAndErrs['valid']) {
            addUser($valsAndErrs);
            displayThanks($valsAndErrs);
        } else {
            displayForm($valsAndErrs);
        }
    }
    
    function displayThanks($valsAndErrs) {
        echo '<h4>Registratie gelukt!</h4>
<div>Email: ' . $valsAndErrs['email'] . ' </div>' . PHP_EOL;        
    }
    
    function displayForm($valsAndErrs) {
        
        echo '<h4>Vul uw gegevens in om te registreren</h4>' . PHP_EOL;
        
        showFormStart('register');
        
        //input for name
        showFormField('name', 'Naam:', 'text', $valsAndErrs);
        //input for email
        showFormField('email', 'Email:', 'email', $valsAndErrs);
        
        //input for password
        showFormField('pass', 'Wachtwoord:', 'password', $valsAndErrs);
        showFormField('passConfirm', 'Wachtwoord Herhalen:', 'password', $valsAndErrs);

        echo '        <br><br>' . PHP_EOL;

        showFormEnd('Registreer');
    }


    function validation() {
        $name = $email = $pass = $passConfirm = '';
        $nameErr = $emailErr = $passErr = $passConfirmErr = '';
        $valid = false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //check name (only allow letters, spaces, dashes and apostrophes)
            $name = testInput(getPostVar("name"));
            $nameErr = validateName($name);
            
            //check email
            $email = testInput(getPostVar("email"));
            $emailErr = validateEmail($email);
            if (doesEmailExist($email)) {
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
?>