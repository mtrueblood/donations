<?
$pageType = "donate";
$success = $_GET['success'];
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Donations</title>
        <meta name="description" content="">
		<meta name="keywords" content="">
        <? include("assets/includes/head.php"); ?>

        <!-- Call for the Stripe JS -->
        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
        <script type="text/javascript">
          // Get from Stripe account. There is a testing key and a live key. This identifies your website in the createToken call below
          Stripe.setPublishableKey('YOUR STRIPE TESTING/LIVE KEY HERE');
        </script>

    </head>
    <style type="text/css">
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
             /* display: none; <- Crashes Chrome on hover */
            -webkit-appearance: none;
            margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }
    </style>
    <body class="<? echo $pageType; ?> interior">

        <section id="wrapper" class="wrapper">
            <!--[if lt IE 7]>
                <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
            <![endif]-->

            <article id="" class="content">

                <h5 class="notify">

                    <!-- Set error messages based on returned error from Stripe -->
                    <? if($success == "incorrect_number") { echo "Your payment could NOT be processed (i.e., you have not been charged) because the card number is incorrect. You can try again or use another card.";
                    } elseif($success == "invalid_request_error") { echo "Your payment could NOT be processed as invalid parameters have been passed. You can try again or use another card.";
                    } elseif($success == "invalid_number") { echo "Your payment could NOT be processed as the card number is not a valid credit card number. You can try again or use another card.";
                    } elseif($success == "invalid_expiry_month") { echo "Your payment could NOT be processed as the card's expiration month is invalid. You can try again or use another card.";
                    } elseif($success == "invalid_expiry_year") { echo "Your payment could NOT be processed  as the card's expiration year is invalid. You can try again or use another card.";
                    } elseif($success == "invalid_cvc") { echo "Your payment could NOT be processed as the card's security code is invalid. You can try again or use another card.";
                    } elseif($success == "expired_card") { echo "Your payment could NOT be processed as the card has expired. You can try again or use another card.";
                    } elseif($success == "incorrect_cvc") { echo "Your payment could NOT be processed as the card's security code is incorrect. You can try again or use another card.";
                    } elseif($success == "card_declined") { echo "Your payment could NOT be processed as the card was declined. You can try again or use another card.";
                    } elseif($success == "missing") { echo "Your payment could NOT be processed as there is no card on a customer that is being charged. You can try again or use another card.";
                    } elseif($success == "processing_error") { echo "Your payment could NOT be processed as an error occurred while processing the card. You can try again or use another card.";
                    } elseif($success == "card_error") { echo "Your payment could NOT be processed as there was an error with your card. You can try again or use another card.";
                    } else { } ?>
                </h5>

                <div class="form-half">
                    <form id="donation-form" name="donation-form" action="assets/resources/donate.php" method="POST">
                        <input type="hidden" name="stripeToken" value="" />
                        <input type="hidden" name="amount" value="" />
                        <div class="form-twothirds">
                            <div class="donation-amount">
                                <p class="amounts">Donation Amount<span></span></p>   
                                <div class="donation-choices">
                                    <p class="choice" id="25" name="25">$25</p>
                                    <p class="choice" id="50" name="50">$50</p>
                                    <p class="choice" id="100" name="100">$100</p>
                                    <p class="choice" id="500" name="500">$500</p>
                                </div><br>
                                <span>*not displayed on the site</span>
                            </div>
                        </div>
                        <div class="form-onethird">
                            <input id="type" type="radio" name="type" class="paypal" value="paypal"> PayPal<br>
                            <input id="type" type="radio" name="type" value="credit" class="credit" checked> Credit Card
                        </div>

                        <div class="other-amount">
                            <input type="number" pattern="[0-9]+" name="otheramount" id="otheramount" class="text other" title=" please use only numbers." placeholder="$ Other Amount" required>
                            <p>.00</p>
                        </div>
                        <input type="text" name="nameoncard" id="nameoncard" pattern="^\w+(?:\s+)?\w+$" title=" please use letters from the alphabet." maxlength="100" class="text name nameoncard" placeholder="Name on Card" required>
                        <input type="number" maxlength="3" pattern="[0-9]{3}" name="ccv" id="ccv" class="text ccv" data-stripe="cvc" placeholder="CVC" required>
                        <input type="number" pattern="[0-9]+" name="creditcardnumber" maxlength="20" class="text number creditcardnumber" data-stripe="number" placeholder="Credit Card Number" required>
                        <div class="card-expire">
                            <input type="number" pattern="[0-9]{2}" size="2" name="expiration" class="text expire mon-expire" data-stripe="exp-month" title=" ie - 06" placeholder="MM" required><p> / </p> 
                            <input type="number" pattern="[0-9]{4}" maxlength="4" name="expiration" class="text expire year-expire" data-stripe="exp-year" title=" ie - 2016"placeholder="YYYY" required>
                        </div>
                </div>
                <div class="form-half">
                        <input type="text" name="name" id="name" class="text recognition" pattern="^\w+(?:\s+)?\w+$" maxlength="100" title=" please use letters from the alphabet." placeholder="Your Name for Recognition" required>
                        <div>
                            <input type="email" name="email" id="email" pattern="^(?:\w+|[.-])+\@(?:\w+|[.-])\.(\w{2,6})$" class="text email"  title=" not a valid email address." maxlength="100" placeholder="Your Email Address" required><br>
                            <p>*will be kept private</p>
                        </div>
                        <div>
                            <textarea name="message" id="message" class="textarea" rows="5" pattern="^\w+(?:\s+)?\w+$" maxlength="500" title=" please use letters from the alphabet." placeholder="Why are you donating?"></textarea><br>
                            <p>*optional</p>
                        </div>
                        <div class="terms-opt">
                            <input name="terms" id="terms" class="check" type="checkbox" value="">
                            I have read and agree to the <a href="#" class="terms">terms and conditions</a> that apply.<br>
                            <input name="optin" id="optin" class="check" type="checkbox" value="1" checked>
                            Sign up to receive email updates from The Company.
                        </div>
                        <div class='sub-but'>
                            <input class="btn" id="send_donate" type="submit" value="Donate">
                        </div>
                        <!-- <input class="btn" id="send_donate" type="submit" value="Donate"> -->
                    </form>
                </div>

            </article>

        </section>

        <div class="page-mask"></div>

        <div class="overlay"><a href="#" class="close" title="close">X</a><p>Enter legal terms here.</p></div>

        <? include("assets/includes/scripts.php"); ?>
    </body>
</html>
