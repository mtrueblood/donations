<?
session_start();
require_once ("../assets/resources/config.php");
$email = $_SESSION['email'];
$key = $_SESSION['key'];
$pageType = "thank-you";
$success = $_GET['success'];

$time = time();
if($success != "true") {
    header("Location: /");
}
$query = "SELECT * FROM donate WHERE email = '" . $email . "' AND session_key = " . $key . " limit 1";
$recent_donor_info = mysql_query($query);
if($recent_donor_info) {
     $recentdonor = mysql_fetch_assoc($recent_donor_info);
}
$name = $recentdonor['name'];
$message = $recentdonor['message'];
$amount = $recentdonor['amount'];
$optin = $recentdonor['optin'];

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Thank You</title>
        <meta name="description" content="">
		<meta name="keywords" content="">
        <? include("../assets/includes/head.php"); ?>
    </head>
    <body class="<? echo $pageType; ?> interior">

        <section id="wrapper" class="wrapper">
            <!--[if lt IE 7]>
                <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
            <![endif]-->

            <article id="" class="content">

                <h1><? echo ucwords($title); ?>.</h1>
                <div class="thankyou-copy">
                    <p>Thank you for your contribution of <strong>$<? echo $amount; ?></strong>. Together we can do great things!</p>
                </div>

                <div class="user-input">
                    <p>"<? echo $message; ?>"<span><? echo $name; ?></span></p>

                </div>
            </article>

        </section>

        <? include("../assets/includes/scripts.php"); ?>
    </body>
</html>
