<?php
/**
 * Created by PhpStorm.
 * User: Mads Ohmsen
 * Date: 01-12-2016
 * Time: 12:07
 */

setcookie('username','',time() - (86400 * 30), "/");
setcookie('password','',time() - (86400 * 30), "/");
setcookie('token', '',  time() - (86400 * 30), "/");
unset($_SESSION['userId']);
unset($userId);
unset($_COOKIE['username']);
unset($_COOKIE['password']);
unset($_COOKIE['token']);
session_destroy();

header('URL = index.php');

