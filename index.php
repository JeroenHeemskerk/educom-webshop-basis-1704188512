<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once('session_manager.php');
    require_once('user_service.php');
    $page = getRequestedPage();
    $valsAndErrs = processRequest($page);
    showResponsePage($valsAndErrs);
    
    function getRequestedPage() {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            $p = getPostVar('page', 'home');
        } else if ($method == 'GET') {
            $p = getUrlvar('page', 'home');
        }
        return $p;
    }
    
    function processRequest($page) {
        switch ($page) {
            case 'contact':
                require_once('contact.php');
                $valsAndErrs = validateContact();
                // currently no separate thank you page
                // if ($valsAndErrs['valid']) {
                    // $page = 'thanks';
                // }
                break;
            case 'login':
                require_once('login.php');
                $valsAndErrs = validateLogin();
                if ($valsAndErrs['valid']) {
                    loginUser($valsAndErrs['name']);
                    $page = 'home';
                }
                break;
            case 'logout':
                logoutUser();
                $page = 'home';
                break;
            case 'register':
                require_once('register.php');
                $valsAndErrs = validateRegistration();
                if ($valsAndErrs['valid']) {
                    addUser($valsAndErrs);
                    $page = 'login';
                }
                break;
        }
        
        $valsAndErrs['page'] = $page;
        return $valsAndErrs;
    }
    
    function showResponsePage($valsAndErrs) {
        beginDocument();
        showHeadSection();
        showBodySection($valsAndErrs);
        endDocument();
    }
    
    //================ request functions ================
    
    function getArrayVar($array, $key, $default='') { 
        return isset($array[$key]) ? $array[$key] : $default; 
    } 
    
    function getPostVar($key, $default='') { 
        return getArrayVar($_POST, $key, $default);
    } 

    function getUrlVar($key, $default='') { 
        return getArrayVar($_GET, $key, $default);
    }
    
    //================ response functions ================
    
    function beginDocument() { 
        echo '<!doctype html>' . PHP_EOL;
        echo '<html>' . PHP_EOL; 
    } 

    function showHeadSection() { 
        echo '    <head>' . PHP_EOL;
        echo '        <link rel="stylesheet" href="CSS/stylesheet.css">' . PHP_EOL;
        echo '    </head>' . PHP_EOL;
    } 

    function showBodySection($valsAndErrs) { 
        echo '    <body>' . PHP_EOL;
        showHeader($valsAndErrs['page']);
        showMenu();
        showContent($valsAndErrs);
        showFooter();
        echo '    </body>' . PHP_EOL;
    } 

    function endDocument() { 
        echo '</html>'; 
    } 

    function showHeader($page) {
        
        switch ($page) 
        {
            case 'home':
                require_once('home.php');
                $pageName = homeHeader();
                break;
            case 'about':
                require_once('about.php');
                $pageName = aboutHeader();
                break;
            case 'contact':
                require_once('contact.php');
                $pageName = contactHeader();
                break;
            case 'register':
                require_once('register.php');
                $pageName = registerHeader();
                break;
            case 'login':
                require_once('login.php');
                $pageName = loginHeader();
                break;
            default:
                $pageName = '404: Page Not Found';
        }
        echo '    <h1>' . $pageName . '</h1>' . PHP_EOL;
    } 

    function showMenu() { 
        echo '    <ul class="menu">' . PHP_EOL;
        showMenuItem('home', 'HOME');
        showMenuItem('about', 'ABOUT');
        showMenuItem('contact', 'CONTACT');
        require_once('session_manager.php');
        if (isUserLoggedIn()) {
            showMenuItem('logout', 'LOGOUT ' . getLoggedInUsername());
        } else {
            showMenuItem('register', 'REGISTER');
            showMenuItem('login', 'LOGIN');
        }
        echo '    </ul>' . PHP_EOL;
    }
    
    function showMenuItem($link, $label) {
        echo '        <li><a href="index.php?page=' . $link . '">' . $label . '</a></li>' . PHP_EOL;
    }

    function showContent($valsAndErrs) { 
        switch ($valsAndErrs['page']) 
        { 
            case 'home':
                require_once('home.php');
                showHomeContent();
                break;
            case 'about':
                require_once('about.php');
                showAboutContent();
                break;
            case 'contact':
                require_once('contact.php');
                showContactContent($valsAndErrs);
                break;
            case 'register':
                require_once('register.php');
                showRegisterContent($valsAndErrs);
                break;
            case 'login':
                require_once('login.php');
                showLoginContent($valsAndErrs);
                break;
            default:
                //require('404.php');
        }     
    } 

    function showFooter() { 
        echo '    <footer>' . PHP_EOL;
        echo '        <p>&copy 2024, Thomas van Haastrecht</p>' . PHP_EOL;
        echo '    </footer>' . PHP_EOL;
    }
    
    //================ common functions ================
    
    function testInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    //function that checks name input and returns an error message
    function validateName($name) {
        $nameErr = '';
        if (empty($name)) {
                $nameErr = "Vul uw naam in";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
            $nameErr= "Alleen letters, streepjes en apostrophen zijn toegestaan	";
        }
        return $nameErr;
    }
    
    //function that checks email input and returns an error message
    function validateEmail($email, $emailRequired=true) {
        $emailErr = '';
        if ($emailRequired) {
            if (empty($email)) {
                $emailErr = "Vul uw email in";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "ongeldig email";
            }
        }
        return $emailErr;
    }
    
    function showFormStart($value) {
        echo '    <form method="post" action="index.php" accept-charset=utf-8>
        <input type="hidden" name="page" value="'.$value.'">'.PHP_EOL;
    }
    
    //function to display a text input as well as its label and error message
    function showFormField($id, $label, $type, $valsAndErrs, $options=NULL, $placeholder=NULL) {
        switch ($type) {
            case 'text':
            case 'password':
            case 'email':
                inputField($id, $label, $type, $valsAndErrs);
                break;
            case 'radio':
                radioField($id, $label, $type, $valsAndErrs, $options);
                break;
            case 'select':
                selectField($id, $label, $type, $valsAndErrs, $options);
                break;
            case 'textarea':
                textAreaField($id, $label, $type, $valsAndErrs, $options, $placeholder);
                break;
            default:
                //error
                break;
        }
    }
    
    function inputField($id, $label, $type, $valsAndErrs) {
        echo '        <div class="inputfield">
            <label for="' . $id . '">' . $label . '</label>
            <input type="' . $type . '" value="' . $valsAndErrs[$id] . '" id="' . $id . '" name="' . $id . '">
            <span class="error">' . $valsAndErrs[$id.'Err'] . '</span><br>
        </div>' . PHP_EOL;
    }
    
    function selectField($id, $label, $type, $valsAndErrs, $options) {
        echo '        <div class="'. $id .'">
            <label for="'. $id .'">'.$label.'</label>
            <select name="'. $id .'" id="'. $id .'">' . PHP_EOL;

        echo '            <option value="" disabled ' . ($valsAndErrs[$id] == '' ? 'selected="true"' : '');
        echo '>Selecteer een optie</option>' . PHP_EOL;
        
        foreach ($options as $option => $optionLabel) {
            echo '<option value="'.$optionLabel.'" ' . ($valsAndErrs[$id] == $optionLabel ? 'selected="true"' : '');
            echo '>'.$optionLabel.'</option>';
        }

        echo '        </select>
            <span class="error">' . $valsAndErrs[$id.'Err'] . '</span>
        </div><br>' . PHP_EOL;
    }
    
    function radioField($id, $label, $type, $valsAndErrs, $options) {
        echo '        <label for="'.$id.'">'.$label.'</label>
        <span class="error">' . $valsAndErrs[$id.'Err'] . '</span><br>'.PHP_EOL;
        
        foreach($options as $option => $optionLabel) {
            echo '<input type="radio" id="'.$option.'Option'.'" name="'.$id.'" value="'.$option.'" ' . ($valsAndErrs[$id] == $option ? "checked" : ''); 
            echo '>
        <label for="'.$option.'Option'.'">'.$optionLabel.'</label><br>'.PHP_EOL;
        }
    }
    
    function textAreaField($id, $label, $type, $valsAndErrs, $options, $placeholder) {
        echo '        <label for="'.$id.'">'.$label.'</label> <span class="error">' . $valsAndErrs[$id.'Err'] . '</span><br>
        <textarea name="'.$id.'" placeholder="'.$placeholder.'"';
        foreach($options as $key => $value){
            echo ' '.$key.'="'.$value.'"';
        }
        echo '>' . $valsAndErrs[$id] . '</textarea><br>
        <br>';
    }
    
    function showFormEnd($value) {
        echo '<input type="submit" value="'.$value.'">
    </form>' . PHP_EOL;
    }
?>