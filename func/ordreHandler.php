<?php
/**
 * Created by PhpStorm.
 * User: mads
 * Date: 16-05-2018
 * Time: 16:07
 */

function ordreCreate( $franco, $ekspress, $datetime, $afPostnr, $afBy, $afVej, $afHusNr, $levPostnr, $levBy, $levVej, $levHusNr, $beskrivelse, $authorization, $userId ){
    if($ekspress == 'checkedValue'){
        $ekspress = 1;
    } else {
        $ekspress = 0;
    }
    if($franco == 'checkedValue'){
        $franco = 1;
    } else {
        $franco = 0;
    }

    /* Postnr Check */

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, "http://localhost:65278/api/postnrBies" );
    #  curl_setopt($curl, CURLOPT_URL, "http://localhost:1158/api/Account/UserId" );
    $token = "Authorization: Bearer $authorization";
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token ));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result =(curl_exec($curl));

    $jsonresult = json_decode($result);

    curl_close($curl);

    $postNrBy = array();

    foreach ($jsonresult as $postnrentry){

        $postnr = $postnrentry->Postnr;

        array_push($postNrBy, $postnr);
    }

    if(in_array($afPostnr,$postNrBy)){

    } else {
        $postby = array(
            'Postnr' => $afPostnr,
            'By' => $afBy
        );
        $postbyCreate = curl_init( 'http://localhost:65278/api/postnrBies' );

# Setup request to send json via POST.
        $payload = json_encode( $postby );
        curl_setopt( $postbyCreate, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $postbyCreate, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $token));
# Return response instead of printing.
        curl_setopt( $postbyCreate, CURLOPT_RETURNTRANSFER, true );
# Send request.
        curl_exec($postbyCreate);
        curl_close($postbyCreate);

    }
    if(in_array($levPostnr,$postNrBy)){

    } else {
        $postby = array(
            'Postnr' => $levPostnr,
            'By' => $levBy
        );
        $postbyCreate = curl_init( 'http://localhost:65278/api/postnrBies' );

# Setup request to send json via POST.
        $payload = json_encode( $postby );
        curl_setopt( $postbyCreate, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $postbyCreate, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $token));
# Return response instead of printing.
        curl_setopt( $postbyCreate, CURLOPT_RETURNTRANSFER, true );
# Send request.
        curl_exec($postbyCreate);
        curl_close($postbyCreate);

    }



    $fragt = array( 'medarbejderID' => '',
        'ekspress' => $ekspress,
        'afPostnr' => $afPostnr,
        'afVej' => $afVej,
        'afHusNr' => $afHusNr,
        "levPostnr" => $levPostnr,
        "levVej" => $levVej,
        "levHusNr" => $levHusNr
    );

    $fragtCreate = curl_init( 'http://localhost:65278/api/fragts' );
    $token = "Authorization: Bearer $authorization";


# Setup request to send json via POST.
    $payload = json_encode( $fragt );
    curl_setopt( $fragtCreate, CURLOPT_POSTFIELDS, $payload );
    curl_setopt( $fragtCreate, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $token));
# Return response instead of printing.
    curl_setopt( $fragtCreate, CURLOPT_RETURNTRANSFER, true );
# Send request.
    curl_exec($fragtCreate);
    curl_close($fragtCreate);

    /* Get kunde and fragt id here and generate QR generer det her eller i api*/
    /* fragtID */

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, "http://localhost:65278/api/fragts" );
#  curl_setopt($curl, CURLOPT_URL, "http://localhost:1158/api/Account/UserId" );
    $token = "Authorization: Bearer $authorization";
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token ));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result =(curl_exec($curl));

    $jsonresult = json_decode($result);

    curl_close($curl);

    $fragt = end($jsonresult);

    $fragtID = $fragt->fragtID;



    $bruger = kundeGet($userId, $authorization);

    $kundeID = $bruger[0]->kundeID;

    $QR = md5(uniqid(rand().$kundeID, true));

    $ordre = array( 'kundeID' => $kundeID,
        'Godkendt' => 0,
        'fragtID' => $fragtID,
        'Franco' => $franco,
        'Dato' => $datetime,
        'leveringsDato' => null,
        'Beskrivelse' => $beskrivelse,
        'QR' => $QR

    );


    $ordreCreate = curl_init( 'http://localhost:65278/api/ordres' );
    $token = "Authorization: Bearer $authorization";

# Setup request to send json via POST.
    $payload = json_encode( $ordre );
    curl_setopt( $ordreCreate, CURLOPT_POSTFIELDS, $payload );
    curl_setopt( $ordreCreate, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $token));
# Return response instead of printing.
    curl_setopt( $ordreCreate, CURLOPT_RETURNTRANSFER, true );
# Send request.
    curl_exec($ordreCreate);
    curl_close($ordreCreate);

    $_SESSION['Besked'] = "Bestilling Oprettet";
    header('location:?page=profil&subpage=ordre');

}

function ordreUpdate( $franco, $ekspress, $datetime, $afPostnr, $afBy, $afVej, $afHusNr, $levPostnr, $levBy, $levVej, $levHusNr, $beskrivelse, $authorization, $userId, $ordreId, $fragtId ){
    if($ekspress == 'checkedValue'){
        $ekspress = 1;
    } else {
        $ekspress = 0;
    }
    if($franco == 'checkedValue'){
        $franco = 1;
    } else {
        $franco = 0;
    }

    /* Postnr Check */

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, "http://localhost:65278/api/postnrBies" );
    #  curl_setopt($curl, CURLOPT_URL, "http://localhost:1158/api/Account/UserId" );
    $token = "Authorization: Bearer $authorization";
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token ));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result =(curl_exec($curl));

    $jsonresult = json_decode($result);

    curl_close($curl);

    $postNrBy = array();

    foreach ($jsonresult as $postnrentry){

        $postnr = $postnrentry->Postnr;

        array_push($postNrBy, $postnr);
    }

    if(in_array($afPostnr,$postNrBy)){

    } else {
        $postby = array(
            'Postnr' => $afPostnr,
            'By' => $afBy
        );
        $postbyCreate = curl_init( 'http://localhost:65278/api/postnrBies' );

# Setup request to send json via POST.
        $payload = json_encode( $postby );
        curl_setopt( $postbyCreate, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $postbyCreate, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $token));
# Return response instead of printing.
        curl_setopt( $postbyCreate, CURLOPT_RETURNTRANSFER, true );
# Send request.
        curl_exec($postbyCreate);
        curl_close($postbyCreate);

    }
    if(in_array($levPostnr,$postNrBy)){

    } else {
        $postby = array(
            'Postnr' => $levPostnr,
            'By' => $levBy
        );
        $postbyCreate = curl_init( 'http://localhost:65278/api/postnrBies' );

# Setup request to send json via POST.
        $payload = json_encode( $postby );
        curl_setopt( $postbyCreate, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $postbyCreate, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $token));
# Return response instead of printing.
        curl_setopt( $postbyCreate, CURLOPT_RETURNTRANSFER, true );
# Send request.
        curl_exec($postbyCreate);
        curl_close($postbyCreate);

    }


    $fragt = array(
        'fragtID' => $fragtId,
        'ekspress' => $ekspress,
        'afPostnr' => $afPostnr,
        'afVej' => $afVej,
        'afHusNr' => $afHusNr,
        "levPostnr" => $levPostnr,
        "levVej" => $levVej,
        "levHusNr" => $levHusNr
    );

    $fragtUpdate = curl_init( "http://localhost:65278/api/fragts/$fragtId" );
    $token = "Authorization: Bearer $authorization";


# Setup request to send json via POST.
    $payload = json_encode( $fragt );
    curl_setopt($fragtUpdate, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($fragtUpdate, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($fragtUpdate, CURLOPT_POSTFIELDS,$payload);
    curl_setopt($fragtUpdate, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $fragtUpdate, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response instead of printing.
# Send request.
    $result1 = curl_exec($fragtUpdate);
    $httpCode1 = curl_getinfo($fragtUpdate, CURLINFO_HTTP_CODE);

    curl_close($fragtUpdate);

    /* Get kunde and fragt id here and generate QR generer det her eller i api*/


    $ordre = array(
        'ordreID' => $ordreId,
        'kundeID' => $userId,
        'Franco' => $franco,
        'Dato' => $datetime,
        'Beskrivelse' => $beskrivelse,
    );


    $ordreUpdate = curl_init( "http://localhost:65278/api/ordres/$ordreId" );
    $token = "Authorization: Bearer $authorization";

# Setup request to send json via POST.
    $payload = json_encode( $ordre );
    curl_setopt( $ordreUpdate, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt( $ordreUpdate, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ordreUpdate, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ordreUpdate, CURLOPT_POSTFIELDS, $payload );
    curl_setopt( $ordreUpdate, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response instead of printing.
# Send request.
    $result2 = curl_exec($ordreUpdate);
    $httpCode2 = curl_getinfo($ordreUpdate, CURLINFO_HTTP_CODE);

    curl_close($ordreUpdate);

    //return $httpCode1." ".$httpCode2;
    return $result1." ".$result2;

    $_SESSION['Besked'] = "Bestilling Oprettet";


}

function ordreDelete($authorization, $ordreId, $fragtId ){
    $ordreDel = curl_init();
    curl_setopt($ordreDel, CURLOPT_URL,"http://localhost:65278/api/ordres/$ordreId");
    curl_setopt($ordreDel, CURLOPT_CUSTOMREQUEST, "DELETE");

    $token = "Authorization: Bearer $authorization";

    curl_setopt($ordreDel, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token ));
    $httpCode = curl_getinfo($ordreDel, CURLINFO_HTTP_CODE);

    $result2 = curl_exec($ordreDel);
    curl_close($ordreDel);

    $fragtDel = curl_init();
    curl_setopt($fragtDel, CURLOPT_URL,"http://localhost:65278/api/fragts/$fragtId");
    curl_setopt($fragtDel, CURLOPT_CUSTOMREQUEST, "DELETE");

    $token = "Authorization: Bearer $authorization";

    curl_setopt($fragtDel, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token ));
    $httpCode = curl_getinfo($fragtDel, CURLINFO_HTTP_CODE);

    $result1 = curl_exec($fragtDel);
    curl_close($fragtDel);




    header('location:?page=profil&subpage=ordre');

    //return $result1." ".$result2;
}

