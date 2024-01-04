<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="CSS/stylesheet.css">
	</head>
	
	<body>
		<?php 
			$title = $name = $message = $email = $phone = $preference = $address_street = $address_street_no = $address_postcode = $address_city = '';
			$titleErr = $nameErr = $messageErr = $emailErr = $phoneErr = $preferenceErr = $addressErr = '';
			$valid = false;
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$valid = true;
				//check title
				if (empty($_POST["title"])) {
					$titleErr = "Kies een aanhef";
					$valid = false;
				} else {
					$title = $_POST["title"];
				}
				
				//check name (only allow letters, spaces, dashes and apostrophes)
				if (empty($_POST["name"])) {
					$nameErr = "Vul uw naam in";
					$valid = false;
				} else {
					$name = $_POST["name"];
					if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
						$nameErr= "Alleen letters, streepjes en apostrophen zijn toegestaan	";
						$valid = false;
					}
				}
				//check message
				if (empty($_POST["message"])) {
					$messageErr = "Vul een bericht in";
					$valid = false;
				} else {
					$message = $_POST["message"];
				}
				
				$address_required = $email_required = $phone_required = false;
				//check preference
				if (empty($_POST["preference"])) {
					$preferenceErr = "Vul in hoe we met u contact op kunnen nemen";
					$valid = false;
				} else {
					$preference = $_POST["preference"];
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
				
				$address_street = $_POST["street"];
				$address_street_no = $_POST["street_no"];
				$address_postcode = $_POST["postcode"];
				$address_city = $_POST["city"];
				if ($address_required) {	
					if (address_check($address_street, $address_street_no, $address_postcode, $address_city) != 1) {
						$addressErr = "Vul een geldig adres in";
						$valid = false;
					}
				}
				
				$email = $_POST["email"];
				//check email (use built-in filter method)
				if ($email_required) {
					if (empty($email)) {
						$emailErr = "Vul uw email in";
						$valid = false;
					} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$emailErr = "ongeldig email";
						$valid = false;
					}
				}
				
				$phone = $_POST["tel"];
				if ($phone_required) {
					if (empty($phone)) {
						$phoneErr = "Vul uw telefoon nummer in";
						$valid = false;
					} else if (!is_numeric($phone)) {
						$phoneErr = "Ongeldig nummer";
						$valid = false;
					}
				}
			}
			
			//function to check if an address is valid or empty (invalid=-1, empty=0, valid=1)
			function address_check($street, $street_no, $postcode, $city) {
				if (empty($street) && empty($street_no) && empty($postcode) && empty($city)) {
					return 0;
				}
				
				if (empty($street)) {
					return -1;
				}
				//could check if this starts with a number, but not all houses have a number
				if (empty($street_no)) {
					return -1;
				}
				if (strlen($postcode) != 6 || !is_numeric(substr($postcode, 0, 4)) || !preg_match("/^[a-zA-Z]{2}$/",substr($postcode, 4, 2)) ) {
					return -1;
				}
				if (empty($city)) {
					return -1;
				}
				
				return 1;
				
			}
		?>
	
		<h1>Contact Pagina</h1>
		<ul class="menu">
			<li><a href="index.html">HOME</a></li>
			<li><a href="about.html">ABOUT</a></li>
			<li><a href="contact.php">CONTACT</a></li>
		</ul>
		<?php
		if (!$valid) { ?>
			<form method="post" action="contact.php" accept-charset=utf-8>
			
				<div class="title">
					<label for="title">Kies uw aanhef:</label>
					
					<select name="title" id="title">
						<option value="" disabled <?php if ($title == "") { ?> selected="true"<?php }; ?>>Select your option</option>
						<option value="Dhr." <?php if ($title == "Dhr.") { ?> selected="true"<?php }; ?>>Dhr.</option>
						<option value="Mvr." <?php if ($title == "Mvr.") { ?> selected="true"<?php }; ?>>Mvr.</option>
						<option value="Dr." <?php if ($title == "Dr.") { ?> selected="true"<?php }; ?>>Dr.</option>
						<option value="Prof." <?php if ($title == "Prof.") { ?> selected="true"<?php }; ?>>Prof.</option>
					</select>
					<span class="error"><?php echo $titleErr; ?></span>
				</div><br>
				
				<div class="inputfield">
					<label for="name">Naam:</label>
					<input type="text" value="<?php echo $name; ?>" id="name" name="name">
					<span class="error"><?php echo $nameErr; ?></span><br>
				</div>
				
				<div class="inputfield">
					<label for="email">Email:</label>
					<input type="email" value="<?php echo $email; ?>" id="email" name="email">
					<span class="error"><?php echo $emailErr; ?></span><br>
				</div>
				
				<div class="inputfield">
					<label for="tel">Tel. nr.:</label>
					<input type="text" value="<?php echo $phone; ?>" id="tel" name="tel">
					<span class="error"><?php echo $phoneErr; ?></span><br>
				</div>
				
				<br>
				
				<h4>Adres</h4> <span class="error"><?php echo $addressErr; ?></span>
				<div class="inputfield">
					<label for="street">Straat:</label>
					<input type="text" value="<?php echo $address_street; ?>" id="street" name="street"><br>
				</div>
				
				<div class="inputfield">
					<label for="street_no">Nr. + Toevoeging:</label>
					<input type="text" value="<?php echo $address_street_no; ?>" id="street_no" name="street_no"><br>
				</div>
				
				<div class="inputfield">
					<label for="postcode">Postcode:</label>
					<input type="text" value="<?php echo $address_postcode; ?>" id="postcode" name="postcode"><br>
				</div>
		  
				<div class="inputfield">
					<label for="city">Woonplaats:</label>
					<input type="text" value="<?php echo $address_city; ?>" id="city" name="city"><br>
				</div>
				
				<br>
				
				<label for="preference">Communicatie Voorkeur</label>
				<span class="error"><?php echo $preferenceErr; ?></span><br>
				<input type="radio" id="email_pref" name="preference" value="email" <?php if ($preference == "email") { echo "checked";} ?>>
				<label for="email_pref">Email</label><br>
				<input type="radio" id="tel_pref" name="preference" value="tel" <?php if ($preference == "tel") { echo "checked";} ?>>
				<label for="tel_pref">Telefoon</label><br>
				<input type="radio" id="post_pref" name="preference" value="post" <?php if ($preference == "post") { echo "checked";} ?>>
				<label for="post_pref">Post</label><br>
				
				<br>
				
				<label for="message">Bericht</label> <span class="error"><?php echo $messageErr; ?></span><br>
				<textarea name="message" placeholder="Vul in waar u contact over wil nemen." rows="10" cols="30"></textarea><br>
				<br>
				
				<input type="submit" value="Verstuur">
			</form>
		<?php 
		} else {
		?>
		
		<p>Bedankt voor uw reactie<p>
		
		<div>Naam: <?php echo "$title $name"; ?></div>
		<div>Email: <?php echo $email; ?></div>
		<div>Tel. nr.: <?php echo $phone; ?></div>
		<div>Adres: <?php echo "$address_street, $address_street_no, $address_postcode, $address_city"; ?></div><br>
		
		<?php
		}
		?>
		
	</body>

	<footer>
		<p>&copy 2024, Thomas van Haastrecht</p>
	</footer> 
</html>