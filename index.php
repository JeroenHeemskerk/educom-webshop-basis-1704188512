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
                $pageName = $header;
                break;
            case 'about':
                require_once('about.php');
                $pageName = $header;
                break;
            case 'contact':
                require_once('contact.php');
                $pageName = $header;
                break;
            case 'register':
                require_once('register.php');
                $pageName = $header;
                break;
            default:
                $pageName = '404: Page Not Found';
        }
        echo '    <h1>' . $pageName . '</h1>' . PHP_EOL;
    } 

    function showMenu() { 
        echo '    <ul class="menu">' . PHP_EOL;
        echo '        <li><a href="index.php?page=home">HOME</a></li>' . PHP_EOL;
        echo '        <li><a href="index.php?page=about">ABOUT</a></li>' . PHP_EOL;
        echo '        <li><a href="index.php?page=contact">CONTACT</a></li>' . PHP_EOL;
        echo '        <li><a href="index.php?page=register">REGISTER</a></li>' . PHP_EOL;
        echo '    </ul>' . PHP_EOL;
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