<?php

    define("TITLES", array("mr."=>"Dhr.", "mrs."=>"Mvr.", "dr." => "Dr.", "prof." => "Prof."));
    define("COMM_PREFS", array("email" => "E-Mail", "phone" => "Telefoon", "mail" => "Post"));
    
    function contactHeader() {
        $header = 'Contact Pagina';
        return $header;
    }
    
    function showContactContent() {
        $valsAndErrs = validation();
        if ($valsAndErrs['valid']) {
            displayThanks($valsAndErrs);
        } else {
            displayForm($valsAndErrs);
        }
    }
    
    function displayForm($valsAndErrs) {
        echo '    <form method="post" action="index.php" accept-charset=utf-8>
        <input type="hidden" name="page" value="contact">
        <div class="title">
            <label for="title">Kies uw aanhef:</label>

            <select name="title" id="title">' . PHP_EOL;
        
        echo '            <option value="" disabled ' . ($valsAndErrs['title'] == '' ? 'selected="true"' : '');
        echo '>Selecteer een optie</option>
            <option value="Dhr." ' . ($valsAndErrs['title'] == 'Dhr.' ? 'selected="true"' : '');
        echo '>Dhr.</option>
            <option value="Mvr." ' . ($valsAndErrs['title'] == 'Mvr.' ? 'selected="true"' : '');
        echo '>Mvr.</option>
            <option value="Dr." ' . ($valsAndErrs['title'] == 'Dr.' ? 'selected="true"' : '');
        echo '>Dr.</option>
            <option value="Prof." ' . ($valsAndErrs['title'] == 'Prof.' ? 'selected="true"' : '');
        echo '>Prof.</option>' . PHP_EOL;

        echo '        </select>
            <span class="error">' . $valsAndErrs['titleErr'] . '</span>
        </div><br>' . PHP_EOL;
        
        //input for name
        showFormField('name', 'Naam:', 'text', $valsAndErrs);
        //input for email
        showFormField('email', 'Email:', 'text', $valsAndErrs);
        //input for phone
        showFormField('phone', 'Tel. nr.:', 'text', $valsAndErrs);

        echo '    <h4>Adres</h4> <span class="error"></span>' . PHP_EOL;
        
        //input for address fields (street, number, postal code, city)
        showFormField('street', 'Straat:', 'text', $valsAndErrs);
        showFormField('streetNo', 'Nr. + Toevoeging:', 'text', $valsAndErrs);
        showFormField('postcode', 'Postcode:', 'text', $valsAndErrs);
        showFormField('city', 'Woonplaats:', 'text', $valsAndErrs);
        
        echo '        <br>' . PHP_EOL;
        
        echo '        <label for="preference">Communicatie Voorkeur</label>
        <span class="error">' . $valsAndErrs['preferenceErr'] . '</span><br>
        <input type="radio" id="emailPref" name="preference" value="email" ' . ($valsAndErrs['preference'] == 'email' ? "checked" : ''); 
        echo '>
        <label for="emailPref">Email</label><br>
        <input type="radio" id="telPref" name="preference" value="phone" ' . ($valsAndErrs['preference'] == 'phone' ? "checked" : '');
        echo '>
        <label for="telPref">Telefoon</label><br>
        <input type="radio" id="postPref" name="preference" value="post" ' . ($valsAndErrs['preference'] == 'post' ? "checked" : '');
        echo '>
        <label for="postPref">Post</label><br><br>' . PHP_EOL;
        
        echo '        <label for="message">Bericht</label> <span class="error">' . $valsAndErrs['messageErr'] . '</span><br>
        <textarea name="message" placeholder="Vul in waar u contact over wil opnemen." rows="10" cols="30">' . $valsAndErrs['message'] . '</textarea><br>
        <br>
        
        <input type="submit" value="Verstuur">
    </form>' . PHP_EOL;
    }
    
    //function to display a text input as well as its label and error message
    function showFormField($id, $label, $type, $valsAndErrs, $options=NULL, $placeholder=NULL) {
        echo '        <div class="inputfield">
            <label for="' . $id . '">' . $label . '</label>
            <input type="' . $type . '" value="' . $valsAndErrs[$id] . '" id="' . $id . '" name="' . $id . '">
            <span class="error">' . $valsAndErrs[$id.'Err'] . '</span><br>
        </div>' . PHP_EOL;
    }
    
    function displayThanks($valsAndErrs) {
        echo '<p>Bedankt voor uw reactie<p>
<div>Naam: ' . $valsAndErrs['title'] . ' ' . $valsAndErrs['name'] . ' </div>
<div>Email: ' . $valsAndErrs['email'] . ' </div>
<div>Tel. nr.: ' . $valsAndErrs['phone'] . ' </div>
<div>Adres: ' . $valsAndErrs['street'] . ', ' . $valsAndErrs['streetNo'] . ', ' . $valsAndErrs['postcode'] . ', ' . $valsAndErrs['city'] . ' </div><br>';

    }
    
    function validation() {
        $title = $name = $message = $email = $phone = $preference = $street = $streetNo = $postcode = $city = '';
        $titleErr = $nameErr = $messageErr = $emailErr = $phoneErr = $preferenceErr = $streetErr = $streetNoErr = $postcodeErr = $cityErr = '';
        $valid = false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //check title
            if (empty(getPostVar("title", ''))) {
                $titleErr = "Kies een aanhef";
            } else {
                $title = testInput(getPostVar("title"));
            }
            
            //check name (only allow letters, spaces, dashes and apostrophes)
            if (empty(getPostVar("name"))) {
                $nameErr = "Vul uw naam in";
            } else {
                $name = testInput(getPostVar("name"));
                if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                    $nameErr= "Alleen letters, streepjes en apostrophen zijn toegestaan	";
                }
            }
            //check message
            if (empty(getPostVar("message"))) {
                $messageErr = "Vul een bericht in";
            } else {
                $message = testInput(getPostVar("message"));
            }
            
            $addressRequired = $emailRequired = $phoneRequired = false;
            //check preference
            if (empty(getPostVar("preference"))) {
                $preferenceErr = "Vul in hoe we met u contact op kunnen nemen";
            } else {
                $preference = testInput(getPostVar("preference"));
                if ($preference == "post" || !empty(getPostVar("street")) || !empty(getPostVar("streetNo")) || !empty(getPostVar("postcode")) || !empty(getPostVar("city")) )  {
                    $addressRequired = true;
                }
                if ($preference == "email" || !empty(getPostVar("email"))) {
                    $emailRequired = true;
                }
                if ($preference == "phone" || !empty(getPostVar("phone"))) {
                    $phoneRequired = true;
                }
            }

            $street = testInput(getPostVar("street"));
            $streetNo = testInput(getPostVar("streetNo"));
            $postcode = testInput(getPostVar("postcode"));
            $city = testInput(getPostVar("city"));
            
            if (empty($street) && $addressRequired) {
                $streetErr = "vul een straat in";
            }
            if (empty($streetNo) && $addressRequired) {
                $streetNoErr = "vul een straat nummer in";
            }
            if (empty($postcode)) {
                if ($addressRequired) {
                    $postcodeErr = "vul een postcode in";
                }
            } elseif (strlen($postcode) != 6 || !is_numeric(substr($postcode, 0, 4)) || !preg_match("/^[a-zA-Z]{2}$/",substr($postcode, 4, 2))) {
                $postcodeErr = "vul een geldige Nederlandse postcode in";
            }
            if (empty($city) && $addressRequired) {
                    $cityErr = "vul een woonplaats in";
            }
            
            $email = testInput(getPostVar("email"));
            //check email (use built-in filter method)
            if ($emailRequired) {
                if (empty($email)) {
                    $emailErr = "Vul uw email in";
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "ongeldig email";
                }
            }
            
            $phone = testInput(getPostVar("phone"));
            if ($phoneRequired) {
                if (empty($phone)) {
                    $phoneErr = "Vul uw telefoon nummer in";
                } else if (!is_numeric($phone)) {
                    $phoneErr = "Ongeldig nummer";
                }
            }
            
            //update valid boolean after all error checking
            $valid = empty($titleErr) && empty($nameErr) && empty($messageErr) && empty($emailErr) && empty($phoneErr) && empty($preferenceErr) && empty($streetErr) && empty($streetNoErr) && empty($postcodeErr) && empty($cityErr);
        }
        $valsAndErrs = array('valid'=>$valid, 'title'=>$title, 'name'=>$name, 'message'=>$message, 'email'=>$email, 'phone'=>$phone, 'preference'=>$preference,
                               'street'=>$street, 'streetNo'=>$streetNo, 'postcode'=>$postcode, 'city'=>$city,
                               'titleErr'=>$titleErr, 'nameErr'=>$nameErr, 'messageErr'=>$messageErr, 'emailErr'=>$emailErr, 'phoneErr'=>$phoneErr, 'preferenceErr'=>$preferenceErr,
                               'streetErr'=>$streetErr, 'streetNoErr'=>$streetNoErr, 'postcodeErr'=>$postcodeErr, 'cityErr'=>$cityErr);
        return $valsAndErrs;
    }
?>
