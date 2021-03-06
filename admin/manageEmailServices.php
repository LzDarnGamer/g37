<!DOCTYPE html>
<?php
if (!isset($_SESSION) ) {
  session_start();
}

require_once("../Lib/lib.php");
require_once("../Lib/db.php");
include( "../ensureAuth.php" );
require_once("../languageAddon.php");

include_once( "../Lib/config.php" );

$isAdmin = false;

$userId = $_SESSION['id'];
$username = $_SESSION['username'];
$role = getRoleFromUser($userId);

$isAdmin = false;
if ($role == "administrator") { $isAdmin = true; }
if (!$isAdmin) {
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
}
?>

<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Manage E-mail Services</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- CSS here -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/css/slicknav.css">
    <link rel="stylesheet" href="../assets/css/flaticon.css">
    <link rel="stylesheet" href="../assets/css/animate.min.css">
    <link rel="stylesheet" href="../assets/css/magnific-popup.css">
    <link rel="stylesheet" href="../assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/css/themify-icons.css">
    <link rel="stylesheet" href="../assets/css/slick.css">
    <link rel="stylesheet" href="../assets/css/nice-select.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .btn {
            background-color: #f7a784;
            border: none;
            color: white;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            margin-right: 15px;
        }   

    </style>


</head>

<body>
 <?php include_once("../Page_Elements/preloader.php") ?>

 <main>
    <?php include_once("../Page_Elements/header.php") ?>
    <!-- Hero Area Start-->
    <div class="nada">
        <div class="nada">
            <div class="container" style="width: 100%; height: 500px; text-align: center;">

                <div class="section-top-border">
                   <h3 class="mb-30">Table</h3>

                   <div class="section-tittle text-center mb-80">
                    <span><?php if (!$isAdmin) { echo "No permissions to be in this page"; } else { echo "Manage E-mail Services"; } ?></span>
                </div>

                <?php

                $ini_email = parse_ini_file($emailServicesDirectory);

                if (isset($ini_email)) {
                    $smtp_server = $ini_email['smtp_server'];
                    $smtp_port = $ini_email['smtp_port'];
                    $smtp_ssl = $ini_email['smtp_ssl'];

                    $auth_username = $ini_email['auth_username'];
                    $auth_password = $ini_email['auth_password'];

                    $pop3_server = $ini_email['pop3_server'];
                    $pop3_username = $ini_email['pop3_username'];
                    $pop3_password = $ini_email['pop3_password'];

                    ?>

                    <?php if ($isAdmin) {

                        ?>
                        <form method="POST" action="changeEmail.php">
                            <h3>SMTP</h3>
                            <table id="customers">
                                <tr>
                                    <th>Server</th>
                                    <th>Port</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Visibility</th>
                                </tr>

                                <tr>
                                    <td>
                                        <input type="text" name="smtpServer" value="<?php echo $smtp_server; ?>" required="" class="single-input">
                                    </td>
                                    <td>
                                        <input type="number" name="smtpPort" value="<?php echo $smtp_port; ?>" required="" class="single-input">    
                                    </td>
                                    <td>
                                        <input type="text" name="smtpUsername" value="<?php echo $auth_username; ?>" required="" class="single-input">
                                    </td>
                                    <td>
                                        <input id="password-field" type="password" name="smtpPassword" value="<?php echo $auth_password; ?>" required="" class="single-input">    
                                    </td>
                                    <td>
                                        <span toggle="#password-field" id="toggle-password" class="fa fa-fw fa-eye field-icon"></span>
                                    </td>
                                </tr>

                                <script>
                                    $("#toggle-password").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        var input = $( "#password-field" );
                                        if (input.attr("type") == "password") {
                                            input.attr("type", "text");
                                        } else {
                                            input.attr("type", "password");
                                        }
                                    });
                                </script>
                            </table>



                            <h3 style="margin-top: 10px;">POP3</h3>
                            <table id="customers">
                                <tr>
                                    <th>Server</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Visibility</th>
                                </tr>

                                <tr>
                                    <td>
                                        <input type="text" name="pop3Server" value="<?php echo $pop3_server; ?>" class="single-input">
                                    </td>
                                    <td>
                                        <input type="text" name="pop3Username" value="<?php echo $pop3_username; ?>" class="single-input">
                                    </td>
                                    <td>
                                        <input id="password-field1" type="password" name="pop3Password" value="<?php echo $pop3_password; ?>" class="single-input">  
                                    </td>
                                    <td>
                                        <span toggle="#password-field" id="toggle-password1" class="fa fa-fw fa-eye field-icon"></span>
                                    </td>
                                </tr>

                                <script>
                                    $("#toggle-password1").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        let x = document.getElementById("password-field1");
                                        if (x.type === "password") {
                                            x.type = "text";
                                        } else {
                                            x.type = "password";
                                        }
                                    });
                                </script>
                            </table>

                            <input type="submit" value="Update" style="margin-top: 10px;" >
                        </form>
                        <?php
                    } 
                } else {
                    echo "Error with ini email file";
                }?>

            </div>
            
        </div>

    </div>
</div>

<style>
    #customers {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #customers td, #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers tr:nth-child(even){background-color: #f2f2f2;}

    #customers tr:hover {background-color: #ddd;}

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #fbd083;
        color: white;
    }
</style>

<!--Hero Area End-->
<!-- Popular Locations Start -->


</main>

<!-- FOOTER HERE -->
<!-- Scroll Up -->
<div id="back-top" >
    <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
</div>


<!-- JS here -->
<!-- All JS Custom Plugins Link Here here -->
<script src="../assets/js/vendor/modernizr-3.5.0.min.js"></script>
<!-- Jquery, Popper, Bootstrap -->
<script src="../assets/js/vendor/jquery-1.12.4.min.js"></script>
<script src="../assets/js/popper.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<!-- Jquery Mobile Menu -->
<script src="../assets/js/jquery.slicknav.min.js"></script>

<!-- Jquery Slick , Owl-Carousel Plugins -->
<script src="../assets/js/owl.carousel.min.js"></script>
<script src="../assets/js/slick.min.js"></script>
<!-- One Page, Animated-HeadLin -->
<script src="../assets/js/wow.min.js"></script>
<script src="../assets/js/animated.headline.js"></script>
<script src="../assets/js/jquery.magnific-popup.js"></script>

<!-- Nice-select, sticky -->
<script src="../assets/js/jquery.nice-select.min.js"></script>
<script src="../assets/js/jquery.sticky.js"></script>

<!-- contact js -->
<script src="../assets/js/contact.js"></script>
<script src="../assets/js/jquery.form.js"></script>
<script src="../assets/js/jquery.validate.min.js"></script>
<script src="../assets/js/mail-script.js"></script>
<script src="../assets/js/jquery.ajaxchimp.min.js"></script>

<!-- Jquery Plugins, main Jquery -->    
<script src="../assets/js/plugins.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>