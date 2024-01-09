<?php 
    $header = 'Contact Pagina';
    
    function showContactContent() {
        $vals_and_errs = validation();
        if ($vals_and_errs['valid']) {
            display_thanks($vals_and_errs);
        } else {
            display_form($vals_and_errs);
        }
    }
    
    function display_form($vals_and_errs) {
        echo '    <form method="post" action="index.php" accept-charset=utf-8>
        <input type="hidden" name="page" value="contact">
        <div class="title">
            <label for="title">Kies uw aanhef:</label>

            <select name="title" id="title">' . PHP_EOL;
        
        echo '            <option value="" disabled ' . ($vals_and_errs['title'] == '' ? 'selected="true"' : '');
        echo '>Selecteer een optie</option>
            <option value="Dhr." ' . ($vals_and_errs['title'] == 'Dhr.' ? 'selected="true"' : '');
        echo '>Dhr.</option>
            <option value="Mvr." ' . ($vals_and_errs['title'] == 'Mvr.' ? 'selected="true"' : '');
        echo '>Mvr.</option>
            <option value="Dr." ' . ($vals_and_errs['title'] == 'Dr.' ? 'selected="true"' : '');
        echo '>Dr.</option>
            <option value="Prof." ' . ($vals_and_errs['title'] == 'Prof.' ? 'selected="true"' : '');
        echo '>Prof.</option>' . PHP_EOL;

        echo '        </select>
            <span class="error">' . $vals_and_errs['titleErr'] . '</span>
        </div><br>' . PHP_EOL;
        
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
            <label for="tel">Tel. nr.:</label>
            <input type="text" value="' . $vals_and_errs['phone'] . '" id="tel" name="tel">
            <span class="error">' . $vals_and_errs['phoneErr'] . '</span><br>
        </div><br>' . PHP_EOL;

        echo '    <h4>Adres</h4> <span class="error"></span>
        <div class="inputfield">
            <label for="street">Straat:</label>
            <input type="text" value="' . $vals_and_errs['address_street'] . '" id="street" name="street">
            <span class="error">' . $vals_and_errs['streetErr'] . '</span><br>
        </div>' . PHP_EOL;
        
        echo '        <div class="inputfield">
            <label for="street_no">Nr. + Toevoeging:</label>
            <input type="text" value="' . $vals_and_errs['address_street_no'] . '" id="street_no" name="street_no">
            <span class="error">' . $vals_and_errs['street_noErr'] . '</span><br>
        </div>' . PHP_EOL;
        
        echo '        <div class="inputfield">
            <label for="postcode">Postcode:</label>
            <input type="text" value="' . $vals_and_errs['address_postcode'] . '" id="postcode" name="postcode">
            <span class="error">' . $vals_and_errs['postcodeErr'] . '</span><br>
        </div>' . PHP_EOL;
  
        echo '        <div class="inputfield">
            <label for="city">Woonplaats:</label>
            <input type="text" value="' . $vals_and_errs['address_city'] . '" id="city" name="city">
            <span class="error">' . $vals_and_errs['cityErr'] . '</span><br>
        </div><br>' . PHP_EOL;
        
        echo '        <label for="preference">Communicatie Voorkeur</label>
        <span class="error">' . $vals_and_errs['preferenceErr'] . '</span><br>
        <input type="radio" id="email_pref" name="preference" value="email" ' . ($vals_and_errs['preference'] == 'email' ? "checked" : ''); 
        echo '>
        <label for="email_pref">Email</label><br>
        <input type="radio" id="tel_pref" name="preference" value="tel" ' . ($vals_and_errs['preference'] == 'tel' ? "checked" : '');
        echo '>
        <label for="tel_pref">Telefoon</label><br>
        <input type="radio" id="post_pref" name="preference" value="post" ' . ($vals_and_errs['preference'] == 'post' ? "checked" : '');
        echo '>
        <label for="post_pref">Post</label><br><br>' . PHP_EOL;
        
        echo '        <label for="message">Bericht</label> <span class="error">' . $vals_and_errs['messageErr'] . '</span><br>
        <textarea name="message" placeholder="Vul in waar u contact over wil opnemen." rows="10" cols="30">' . $vals_and_errs['message'] . '</textarea><br>
        <br>
        
        <input type="submit" value="Verstuur">
    </form>' . PHP_EOL;
    }
    
    function display_thanks($vals_and_errs) {
        echo '<p>Bedankt voor uw reactie<p>
<div>Naam: ' . $vals_and_errs['title'] . ' ' . $vals_and_errs['name'] . ' </div>
<div>Email: ' . $vals_and_errs['email'] . ' </div>
<div>Tel. nr.: ' . $vals_and_errs['phone'] . ' </div>
<div>Adres: ' . $vals_and_errs['address_street'] . ', ' . $vals_and_errs['address_street_no'] . ', ' . $vals_and_errs['address_postcode'] . ', ' . $vals_and_errs['address_city'] . ' </div><br>';

    }
    
    function validation() {
        $title = $name = $message = $email = $phone = $preference = $address_street = $address_street_no = $address_postcode = $address_city = '';
        $titleErr = $nameErr = $messageErr = $emailErr = $phoneErr = $preferenceErr = $streetErr = $street_noErr = $postcodeErr = $cityErr = '';
        $valid = false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //check title
            if (empty($_POST["title"])) {
                $titleErr = "Kies een aanhef";
            } else {
                $title = test_input($_POST["title"]);
            }
            
            //check name (only allow letters, spaces, dashes and apostrophes)
            if (empty($_POST["name"])) {
                $nameErr = "Vul uw naam in";
            } else {
                $name = test_input($_POST["name"]);
                if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                    $nameErr= "Alleen letters, streepjes en apostrophen zijn toegestaan	";
                }
            }
            //check message
            if (empty($_POST["message"])) {
                $messageErr = "Vul een bericht in";
            } else {
                $message = test_input($_POST["message"]);
            }
            
            $address_required = $email_required = $phone_required = false;
            //check preference
            if (empty($_POST["preference"])) {
                $preferenceErr = "Vul in hoe we met u contact op kunnen nemen";
            } else {
                $preference = test_input($_POST["preference"]);
                if ($preference == "post" || !empty($_POST["street"]) || !empty($_POST["street_no"]) || !empty($_POST["postcode"]) || !empty($_POST["city"]) )  {
                    $address_required = true;
                }
                if ($preference == "email" || !empty($_POST["email"])) {
                    $email_required = true;
                }
                if ($preference == "tel" || !empty($_POST["tel"])) {
                    $phone_required = true;
                }
            }

            $address_street = test_input($_POST["street"]);
            $address_street_no = test_input($_POST["street_no"]);
            $address_postcode = test_input($_POST["postcode"]);
            $address_city = test_input($_POST["city"]);
            
            if (empty($address_street)) {
                if ($address_required) {
                    $streetErr = "vul een straat in";
                }
            }
            if (empty($address_street_no)) {
                if ($address_required) {
                    $street_noErr = "vul een straat nummer in";
                }
            }
            if (empty($address_postcode)) {
                if ($address_required) {
                    $postcodeErr = "vul een postcode in";
                }
            } elseif (strlen($address_postcode) != 6 || !is_numeric(substr($address_postcode, 0, 4)) || !preg_match("/^[a-zA-Z]{2}$/",substr($address_postcode, 4, 2))) {
                $postcodeErr = "vul een geldige Nederlandse postcode in";
            }
            if (empty($address_city)) {
                if ($address_required) {
                    $cityErr = "vul een woonplaats in";
                }
            }
            
            $email = test_input($_POST["email"]);
            //check email (use built-in filter method)
            if ($email_required) {
                if (empty($email)) {
                    $emailErr = "Vul uw email in";
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "ongeldig email";
                }
            }
            
            $phone = test_input($_POST["tel"]);
            if ($phone_required) {
                if (empty($phone)) {
                    $phoneErr = "Vul uw telefoon nummer in";
                } else if (!is_numeric($phone)) {
                    $phoneErr = "Ongeldig nummer";
                }
            }
            
            //update valid boolean after all error checking
            $valid = empty($titleErr) && empty($nameErr) && empty($messageErr) && empty($emailErr) && empty($phoneErr) && empty($preferenceErr) && empty($streetErr) && empty($street_noErr) && empty($postcodeErr) && empty($cityErr);
        }
        $vals_and_errs = array('valid'=>$valid, 'title'=>$title, 'name'=>$name, 'message'=>$message, 'email'=>$email, 'phone'=>$phone, 'preference'=>$preference,
                               'address_street'=>$address_street, 'address_street_no'=>$address_street_no, 'address_postcode'=>$address_postcode, 'address_city'=>$address_city,
                               'titleErr'=>$titleErr, 'nameErr'=>$nameErr, 'messageErr'=>$messageErr, 'emailErr'=>$emailErr, 'phoneErr'=>$phoneErr, 'preferenceErr'=>$preferenceErr,
                               'streetErr'=>$streetErr, 'street_noErr'=>$street_noErr, 'postcodeErr'=>$postcodeErr, 'cityErr'=>$cityErr);
        return $vals_and_errs;
    }
    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>
