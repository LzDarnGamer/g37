<?php

if ( !isset($_SESSION) ) {
    session_start();
}
require_once( "Lib/db.php" );
require_once( "Lib/lib.php" );
require_once( "Lib/lib-coords.php" );
require_once( "Lib/ImageResize-class.php" );

include_once( "Lib/config.php" );
include( "ensureAuth.php" );
include_once( "Lib/config.php" );

$email = $_POST['email'];

$ajuda = false;

$subs = getAllSubscriptions();
for ($i = 0; $i < sizeof($subs); $i ++) {
    if ($email == $subs[$i]['email']) {
        $ajuda = true;
    }
}

if (trim($email) == "" || $ajuda) {
    header( "Location: " . $baseUrl . "landingpage.php" );
}

$work = subscribeNewsletter ($email);

if ($work == "true") {
    header( "Location: " . $baseUrl . "landingpage.php?newsletter=1" );
} else {
   header( "Location: " . $baseUrl . "landingpage.php?newsletter=0" );
}

?>