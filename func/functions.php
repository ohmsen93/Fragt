<?php
/**
 * Created by PhpStorm.
 * User: mads
 * Date: 09-05-2018
 * Time: 19:30
 */

    function tokenReq($userlogin, $userpass){
        # Token request.
        $tokenReq = curl_init( 'http://localhost:65278/token' );
        #  $tokenReq = curl_init( 'http://localhost:1158/token' );
        # Setup request to send json via POST.
        $payload = "username=$userlogin&password=$userpass&grant_type=password";
        curl_setopt( $tokenReq, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $tokenReq, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $tokenReq, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($tokenReq);
        curl_close($tokenReq);

        $array = preg_split('/"/', $result);

        if($array[7] == "The user name or password is incorrect."){
            session_destroy();
            $_SESSION['message'] = $array[7];
            header('URL = index.php');
        } else {
            // Token response

            echo "<pre>";
            print_r($array[3]);
            echo "</pre>";

            $_SESSION['token'] = $array[3];
        }

    }

        function userCreate($email, $password, $confirmPassword){
            $data = array('Email' => $email, 'Password' => $password, 'ConfirmPassword' => $confirmPassword);

            $userCreate = curl_init( 'http://localhost:65278/api/Account/Register' );
# Setup request to send json via POST.
            $payload = json_encode( $data );
            curl_setopt( $userCreate, CURLOPT_POSTFIELDS, $payload );
            curl_setopt( $userCreate, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response instead of printing.
            curl_setopt( $userCreate, CURLOPT_RETURNTRANSFER, true );
# Send request.
            curl_exec($userCreate);
            curl_close($userCreate);

            header("location: ?page=kundeInformation");


        }

    function userGET($authorization){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, "http://localhost:65278/api/Account/UserId" );
        #  curl_setopt($curl, CURLOPT_URL, "http://localhost:1158/api/Account/UserId" );
        $token = "Authorization: Bearer $authorization";
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result =(curl_exec($curl));

        $jsonresult = json_decode($result);

        curl_close($curl);


        $_SESSION['userId'] = $jsonresult;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, "http://localhost:65278/api/AspNetUserRoles?user=$jsonresult" );
        #  curl_setopt($curl, CURLOPT_URL, "http://localhost:1158/api/Account/UserId" );
        $token = "Authorization: Bearer $authorization";
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result =(curl_exec($curl));

        $jsonresult = json_decode($result);

        curl_close($curl);


        $_SESSION['userRole'] = $jsonresult;

    }

    function userKundeType($authorization){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, "http://localhost:65278/api/kundetypes" );
        $token = "Authorization: Bearer $authorization";

#  curl_setopt($curl, CURLOPT_URL, "http://localhost:1158/api/Students/User/$user/" );

        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $token));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result =(curl_exec($curl));

        $jsonresult = json_decode($result);

#  print_r($jsonresult);

        curl_close($curl);

        return $jsonresult;
    }

    function kundeCreate($name, $type, $id, $authorization){
        $data = array('kundeType' => $type, 'kundeNavn' => $name, 'AspUserID' => $id);

        $userCreate = curl_init( 'http://localhost:65278/api/kundes' );
        $token = "Authorization: Bearer $authorization";

# Setup request to send json via POST.
        $payload = json_encode( $data );
        curl_setopt( $userCreate, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $userCreate, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $token));
# Return response instead of printing.
        curl_setopt( $userCreate, CURLOPT_RETURNTRANSFER, true );
# Send request.
        curl_exec($userCreate);
        curl_close($userCreate);

        header("location: ?");
    }

    function kundeGet($authorization){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, "http://localhost:65278/api/kundes/1" );
        #  curl_setopt($curl, CURLOPT_URL, "http://localhost:1158/api/Account/UserId" );
        $token = "Authorization: Bearer $authorization";
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result =(curl_exec($curl));

        $jsonresult = json_decode($result);

        curl_close($curl);

        return $jsonresult;
    }




