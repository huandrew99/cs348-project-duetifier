<?php

//require_once __DIR__ . '/vendor/autoload.php';
ini_set('allow_url_fopen',1);
$path = @parse_url($_SERVER['REQUEST_URI'])['path'];
switch ($path) {
    case '/':
        require 'index.php';
        break;
    case '/index':
        require 'index.php';
        break;
    case '/index.php':
        require 'index.php';
        break;
        
    case '/events_del':
        require 'events_del.php';
        break;
    case '/events_del.php':
        require 'events_del.php';
        break;
        
    case '/events_edit':
        require 'events_edit.php';
        break;
    case '/events_edit.php':
        require 'events_edit.php';
        break;
        
    case '/events_update':
        require 'events_update.php';
        break;
    case '/events_update.php':
        require 'events_update.php';
        break;
    
    case '/course_edit':
        require 'course_edit.php';
        break;
    case '/course_edit.php':
        require 'course_edit.php';
        break;
        
    default:
        http_response_code(404);
        echo @parse_url($_SERVER['REQUEST_URI'])['path'];
        exit('Not Found');
}


?>
