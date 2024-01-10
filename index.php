<?php
    $page = getRequestedPage();
    showResponsePage($page);
    
    function getRequestedPage() {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            $p = getPostVar('page', 'home');
        } else if ($method == 'GET') {
            $p = getUrlvar('page', 'home');
        }
        return $p;
    }
    
    function showResponsePage($page) {
        beginDocument();
        showHeadSection();
        showBodySection($page);
        endDocument();
    }
    
    //================ request functions ================
    
    function getArrayVar($array, $key, $default='home') { 
        return isset($array[$key]) ? $array[$key] : $default; 
    } 
    
    function getPostVar($key, $default='home') { 
        return getArrayVar($_POST, $key, $default);
    } 

    function getUrlVar($key, $default='home') { 
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

    function showBodySection($page) { 
        echo '    <body>' . PHP_EOL;
        showHeader($page);
        showMenu();
        showContent($page);
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
        showMenuItem('register', 'REGISTER');
        echo '    </ul>' . PHP_EOL;
    }
    
    function showMenuItem($link, $label) {
        echo '        <li><a href="index.php?page=' . $link . '">' . $label . '</a></li>' . PHP_EOL;
    }

    function showContent($page) { 
        switch ($page) 
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
                showContactContent();
                break;
            case 'register':
                require_once('register.php');
                showRegisterContent();
            default:
                //require('404.php');
        }     
    } 

    function showFooter() { 
        echo '    <footer>' . PHP_EOL;
        echo '        <p>&copy 2024, Thomas van Haastrecht</p>' . PHP_EOL;
        echo '    </footer>' . PHP_EOL;
    } 
?>