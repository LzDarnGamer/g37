<?php
    session_start();
    require_once("../Lib/db.php");
    require_once("../Lib/lib.php");

    if ( $_SERVER['REQUEST_METHOD'] == 'POST') {
      $_INPUT_METHOD = INPUT_POST;
    } else {
      $_INPUT_METHOD = INPUT_GET;
    }
    
    $flags[] = FILTER_NULL_ON_FAILURE;
    
    $name = filter_input( 
            $_INPUT_METHOD, 
            'name', 
            FILTER_SANITIZE_STRING, 
            $flags);

    $pass = filter_input( 
        $_INPUT_METHOD, 
        'password', 
        FILTER_SANITIZE_STRING, 
        $flags);

    $repass = filter_input( 
        $_INPUT_METHOD, 
        'repassword', 
        FILTER_SANITIZE_STRING, 
        $flags);

    $email = filter_input( 
            $_INPUT_METHOD, 
            'email', 
            FILTER_SANITIZE_EMAIL, 
            $flags);


    $captcha = filter_input( 
            $_INPUT_METHOD, 
            'captcha', 
            FILTER_SANITIZE_STRING, 
            $flags);

    $trueCaptcha = $_SESSION['captcha'];



    if($trueCaptcha != $captcha){
        header("Location: formRegister.php?return=Captcha%20Failed!");
        exit();
    }

    if($pass != $repass){
        header("Location: formRegister.php?return=Passwords%20don't%20match");
        exit();      
    }

    if ( $name===null || $name=="") {
        header("Location: formRegister.php?return=Name%20is%20incorrect%20format");
        exit();
    }
    
    if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
        header("Location: formRegister.php?return=Email%20is%20incorrect%20format");
        exit();
    }

    dbConnect(ConfigFile);
    
    $dataBaseName = $GLOBALS['configDataBase']->db;

    $linkIdentifier = $GLOBALS['ligacao'];

    mysqli_select_db($linkIdentifier, $dataBaseName );

    $pass = sha1($pass);
    $query = 
            "INSERT INTO `$dataBaseName`.`auth-basic` " .
            "(`name`, `password`, `email`) values " .
            "('$name', '$pass', '$email')";
     

    mysqli_query( $linkIdentifier, $query );

    $recordsInserted = mysqli_affected_rows( $linkIdentifier );
  
    if ( $recordsInserted==-1 ) {
        $title = "User already exists";
        $info = "User already exists, please check your email to activate your account";
        header("Location: ../responsePage.php?title=$title&info=$info");
        exit();
    }
    else {
		$res = $linkIdentifier->insert_id;
    	$randomHash = md5generation();
	    $queryCha = 
	            "INSERT INTO `$dataBaseName`.`auth-challenge` " .
	            "(`id`, `challenge`) values " .
	            "('$res', '$randomHash')";
		mysqli_query( $linkIdentifier, $queryCha ); 

		sendMail($email, $name, $pass, $randomHash);

        $title = "User created with success";
        $info = "User created with success, please check your email to activate your account";
        header("Location: ../responsePage.php?title=$title&info=$info");
        exit();
    }

    dbDisconnect();

    function md5generation(){
    	return bin2hex(openssl_random_pseudo_bytes(16));
    }

    function sendMail($Toemail, $username, $userPass, $hash) {

        require_once( "../Lib/lib.php" );

        $serverName = $_SERVER['SERVER_NAME'];
        #$serverName = "localhost";

        $serverPortSSL = 443;
        $serverPort = 80;

        $name = webAppName();

        $nextUrl = "https://" . $serverName . ":" . $serverPortSSL . $name . "processConfirmationMail.php";

        $urlName = rawurlencode($username);
        $urlmail = rawurlencode($Toemail);
        $to      = $Toemail; // Send email to our user
        $subject = 'Signup | Verification'; // Give the email a subject 
        $message = '
         
        Thanks for signing up!
        Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
         
        ------------------------
        Username: '.$username.'
        Password: '.$userPass.'
        ------------------------
         
        Please click this link to activate your account:
        '.$nextUrl.'?confirm='.$hash.'
        
        Best regards
        '; // Our message above including the link
                             
        $headers = 'From:noreply@yourwebsite.com' . "\r\n"; // Set from headers
        mail($to, $subject, $message, $headers); // Send our email
    }
?>
