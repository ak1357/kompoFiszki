<?php

namespace App;

session_start();

date_default_timezone_set('UTC');

// Konfiguracja aplikacji

$uri = null;
$context   = '/kompo/2';
$appConfig = [
    'collection' => '1.0',
	'quiz' => '1.0',
	'sheet' => '1.0',
    'user' => '1.0',
    'word' => '1.0'

];


require_once $_SERVER['DOCUMENT_ROOT'] . $context . '/style/template/header.php';

// Zaimportowanie interfejsów

require_once $_SERVER['DOCUMENT_ROOT'] . $context . '/interfaces/iComponent.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $context . '/interfaces/iUser.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $context . '/interfaces/iCollection.php';


// Zaimportowanie klas komponentów

foreach (((is_array($appConfig)) ? $appConfig : []) as $component => $version) {
    
    $appDir = $_SERVER['DOCUMENT_ROOT'] . $context ;
    $componentPath = $appDir . '/components/' . $component . '/' . $version . '/main.php'; 
    
    if (file_exists($componentPath))
        require_once $componentPath;
    
    else {
        require_once $appDir . '/style/template/error.php';
        exit;
    }
    
}

require_once $_SERVER['DOCUMENT_ROOT'] . $context . '/logic/ioc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $context . '/logic/client.php';

new Logic\Ioc($appConfig);

require_once $_SERVER['DOCUMENT_ROOT'] . $context . '/style/template/footer.php';

?>