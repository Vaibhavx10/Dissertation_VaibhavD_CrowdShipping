<?php
session_status() === PHP_SESSION_ACTIVE ?: session_start();
require 'vendor/autoload.php'; 
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$base_url = "http://localhost/vaibhav_php/project1/"; 
$asset_url = "http://localhost/vaibhav_php/project1/default/"; 
$db = new MysqliDb($_ENV['DB_SERVER'],$_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_DB']);
 

function check_login()
{
    $base_url = "http://localhost/vaibhav_php/project1/"; 
    if(!isset($_SESSION['user'])){
        header('Location: ' . $base_url);
        exit();
    }else{
    }
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
