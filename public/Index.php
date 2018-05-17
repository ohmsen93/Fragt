<?php
ini_set('max_execution_time', 300);
ob_start();
session_start();
INCLUDE '../func/functions.php';
require_once '../vendor/autoload.php';
//Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader, array('auto_reload' => true));


if(isset($_COOKIE['username'])){
    $_SESSION['username'] = $_COOKIE['username'];
}

$page = $_GET["page"] ?? "";
$subpage = $_GET["subpage"] ?? "";
$pageid = $_GET["pageid"] ?? "";
$user = $_SESSION["username"] ?? null;

// Login
if(isset($_POST['accountCreate'])){
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];
    $passwordConfirm = $_POST['passwordConfirm'];
    userCreate($email, $password, $passwordConfirm);

    tokenReq($email, $password);


    $result = array(userGET($_SESSION['token']));
}




if(isset($_POST['loginSubmit'])){
    include '../func/login.php';
}

if(isset($_POST['logout'])){
    include '../func/logout.php';
}


if(isset($_SESSION['userId'])){
    $userId = $_SESSION['userId'];
    //$student = studentGET($_SESSION['token'], $_SESSION['userId']);
} else {
    $userId = Null;
}

if(isset($_SESSION['token'])){
    $token = $_SESSION['token'];
    //$students = studentsGET($_SESSION['token']);

} else {
    $token = Null;
    //$students = null;
}

if(isset($_COOKIE['username'])){
    $username = $_COOKIE['username'];
} else {
    $username = Null;
}
if(isset($_COOKIE['password'])){
    $password = $_COOKIE['password'];
} else {
    $password = Null;
}


$kundetyper = "";
$bruger[0] = "";
$type = "";
$ordres = "";
$Role = $_SESSION['userRole']->RoleId ?? "";
$bruger = kundeGet($userId, $token);



                Switch($page){
                    case "ordre":
                        $template = $twig->loadTemplate('ordre.html.twig');
                        include '../func/ordreHandler.php';
                        if(isset($_POST['ordreCreate'])) {
                            ordreCreate($_POST['franco'] ?? "", $_POST['ekspress'] ?? "", $_POST['datetime'], $_POST['afPostnr'], $_POST['afBy'], $_POST['afVej'], $_POST['afNr'], $_POST['levPostnr'], $_POST['levBy'], $_POST['levVej'], $_POST['levNr'], $_POST['beskrivelse'], $token, $userId);
                        }
                        if(isset($_GET['pageid'])){
                            $ordres = singleordreGET($_GET['pageid'], $token);
                            if(isset($_POST['ordreUpdate'])){
                                $result = ordreUpdate($_POST['franco'] ?? "", $_POST['ekspress'] ?? "", $_POST['datetime'], $_POST['afPostnr'], $_POST['afBy'], $_POST['afVej'], $_POST['afNr'], $_POST['levPostnr'], $_POST['levBy'], $_POST['levVej'], $_POST['levNr'], $_POST['beskrivelse'], $token, $bruger[0], $pageid, $ordres->fragt->fragtID);
                                echo "<pre>";
                                print_r($result);
                                echo "</pre>";
                            }
                            if(isset($_POST['ordreDelete'])){
                                 ordreDelete($token, $pageid, $ordres->fragt->fragtID);

                            }
                        }

                        break;
                    case "about":
                        $template = $twig->loadTemplate('about.html.twig');
                        break;
                    case "vognparken":
                        $template = $twig->loadTemplate('vognparken.html.twig');
                        break;
                    case "opretAccount":
                        $template = $twig->loadTemplate('opretAccount.html.twig');
                        break;
                    case "kundeInformation":
                        $template = $twig->loadTemplate('kundeInformation.html.twig');
                        $kundetyper = userKundeType($_SESSION['token']);
                        if(isset($_POST['kundeCreate'])){
                            kundeCreate($_POST['navn'], $_POST['type'], $userId, $token);
                        }
                        break;
                    case "profil":
                        $template = $twig->loadTemplate('profil.html.twig');
                        $ordres = ordresGET($bruger[0]->kundeID, $token);
                        break;
                    default:
                        $template = $twig->loadTemplate('index.html.twig');
                    break;
                }


echo $template->render(
    array(
        'page' => $page,
        'subpage' => $subpage,
        'pageid' => $pageid,
        'ordres' => $ordres,
        'token' => $token,
        'userId' => $userId,
        'role' => $Role,
        'type' => $type,
        'bruger' => $bruger[0],
        'username' => $username,
        'password' => $password,
        'kundetyper' => $kundetyper

    ));


?>


