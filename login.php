<?php
    function loginHeader() {
        $header = 'Login Pagina';
        return $header;
    }
    
    function showLoginContent($valsAndErrs) {
        displayForm($valsAndErrs);
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


    function validateLogin() {
        $email = $pass = $name = '';
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
            $name = authorizeUser($email, $pass);
            if (empty($pass)) {
                $passErr = "Vul een wachtwoord in";
            } elseif (empty($emailErr) && $name == NULL) {
                $passErr = "Ongeldig wachtwoord";
            }
            
            //update valid boolean after all error checking
            $valid = empty($nameErr) && empty($emailErr) && empty($passErr) && empty($passConfirmErr);
        }
        $valsAndErrs = array('valid'=>$valid, 'email'=>$email, 'pass'=>$pass, 'name'=>$name,
                             'emailErr'=>$emailErr, 'passErr'=>$passErr);
        return $valsAndErrs;
    }
?>