<?php
# Token request.

$userLogin = $_POST['loginEmail'];
$userPassword = $_POST['loginPassword'];
if(isset($_POST['rememberMe'])){
    setcookie('username',$userLogin,time() + (86400 * 30), "/");
    setcookie('password',$userPassword,time() + (86400 * 30), "/");
}

tokenReq($userLogin, $userPassword);


$result = array(userGET($_SESSION['token']));


?>




