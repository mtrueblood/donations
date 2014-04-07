$(document).ready(function(){

    //user has clicked the terms and conditions, show the overlay now
    $(".terms").on("click",function(e){
        e.preventDefault(e);
        $(".page-mask").fadeIn("slow");
        $(".overlay").fadeIn("slow");
    });

    //once the user has checked the terms box, be sure to fade out the notification.
    $("#terms").on("click",function(e){
        $(".content h5").fadeOut();
    });

    //if the user wants to choose a donate amount from the donate-choices drop down, slide down the drop down (slide it up if its already open)
    $(".donation-amount .amounts").on("click",function(e){
        e.preventDefault(e);
        $this = $(this);
        if($(".donation-choices").is(":visible")) {
            $(".donation-choices").slideUp();
        } else {
            $(".donation-choices").slideDown();
        }
    });

    //if the user has chosen a donate choice, remove the selected of already selected choice, add class selected of chosen choice, set var amount equal to the text, update the default of donate-choices to the amount selected, slide up donate-choices, remove the value in otheramount (if a value has already been typed in), remove the required attribute from otheramount.
    $(".donation-choices").on("click","p",function(e){
        e.preventDefault(e);
        $this = $(this);
        $(".donation-choices p").removeClass("selected");
        $this.addClass("selected");
        var amount = $this.text();
        $(".donation-amount .amounts").html(amount+"<span></span>");
        $(".donation-choices").slideUp();
        $("#otheramount").val('').removeAttr('required');        
    });

    //if the user has chosen the other amount, remove class selected from donation-choices (if they already had one selected), slide up the donation-choices div (if it was open), replace "donation amount" as the default for donation-choices, and add the required attribute to the otheramount input.
    $("#otheramount").keydown(function(e){
        $this = $(this);
        $(".donation-choices p").removeClass("selected");
        $(".donation-choices").slideUp();
        $(".donation-amount .amounts").html("Donation Amount<span></span>");
        $this.prop('required', true);
    });

    //if the user has chosen paypal as their form of donate, fade out, strike-through and remove the required attribute for nameoncard, ccv, zip, creditcardnumber and expiration. Also update the submit button to 'continue'.
    $(".paypal").on("click",function(e){
        $('.sub-but').html('<input class="btn" type="button" id="send_donate" value="Donate">');
        $(".nameoncard, .ccv, .zip, .creditcardnumber, .expire, .recognition, .email, .textarea").removeAttr('required').prop('readOnly', true).css({"background": "#e1e1e1", "text-decoration": "line-through"});
        $("#send_donate").val("Continue");
    });

    //if the user has chosen credit card as their form of donate, un fade-out, remove the strike-through and add the required attribute for nameoncard, ccv, zip, creditcardnumber and expiration (if the user had already selected paypal first). Also update the submit button to 'Donate'.
    $(".credit").on("click",function(e){
        $('.sub-but').html('<input class="btn" id="send_donate" type="button" value="Donate">');
        $(".nameoncard, .ccv, .zip, .creditcardnumber, .expire, .recognition, .email, .textarea").prop('required',true).prop('readOnly', false).css({"background": "#fff", "text-decoration": "none"});
        $("#send_donate").val("Donate");
    });

    $(".overlay").on("click",".close, .exit",function(e){
        e.preventDefault();
        $this = $(this);
        $(".overlay").fadeOut("slow");
        $(".page-mask").fadeOut("slow");
    });

    $(".page-mask").on("click",function(e){
        e.preventDefault();
        $this = $(this);
        $(".overlay").fadeOut("slow");
        $(".page-mask").fadeOut("slow");
    });

    //when the user clicks on the donate submit button
    $('.sub-but').on("click", function (e) {
         $('.error').removeClass('error');
        e.preventDefault();
        e.stopImmediatePropagation(e);
        if (!$("#terms").is(":checked")) {
            $(".content h5").text("Please agree to the Terms and Conditions.").fadeIn();
        } else {
           
            if ($(".paypal").is(":checked")) {
                if ($(".choice").hasClass("selected")) {
                    var amount = $(".choice.selected").attr("id");
                } else if ($("#otheramount").length > 0) {
                    $("#otheramount").prop('required',true);
                    var amount = $("#otheramount").val();
                } else {
                    $(".content h5").text("There was an error with the amount you selected. Please try again.").fadeIn();
                }
               
                if (amount === "") {
                   $('#otheramount').addClass('error');
                    $('p.amounts').addClass('error');
                    $(".content h5").text("Please select an amount to donate.").fadeIn();
                } else {
                    $(".content h5").fadeOut();
                 
var url = "https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=blightauthority%40gmail%2ecom&lc=US&item_name=The%20Blight%20Authority&amount="+amount+"%2e00&currency_code=USD&no_note=0&cn=Add%20special%20instructions%20to%20the%20seller%3a&no_shipping=1&rm=1&return=https%3a%2f%2fwww%2etheblightauthority%2ecom%2fassets%2fresources%2fdonate%2ephp&cancel_return=https%3a%2f%2fwww%2etheblightauthority%2ecom%2fdonate%2f&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted&success=true";
                    window.location = url;
                }
            } else {
                
                if ($(".choice").hasClass("selected")) {
                    var amount = $(".choice.selected").attr("id");
                    $("#donation-form").data('amount', amount);
                } else if($("#otheramount").length > 0) {
                    $("#otheramount").prop('required',true);
                    var amount = $("#otheramount").val();
                    $("#donation-form").data('amount', amount);
                } else {
                    $(".content h5").text("There was an error with the amount you selected. Please try again.");
                }
                var type = "credit card";
                var required = ["#name", "#email", "#message", "#nameoncard", "#ccv", ".creditcardnumber", ".mon-expire", ".year-expire"];
                var numeric = ["#ccv", ".creditcardnumber", ".mon-expire", ".year-expire"];
                var errors = 0;
                var name = $("#name").val();
                var email = $("#email").val();
                var message = $("#message").val();
                var optin = $("#optin").is(":checked");
                if(optin === true) { optin = 1; } else { optin = 0; }
                for (var i=0; i<required.length; i++) {
                    if ($(required[i]).val() == "") {
                        $(required[i]).addClass('error');
                        errors++;
                    }
                }
                if (amount == "") {
                    errors++;
                    $('#otheramount').addClass('error');
                    $('p.amounts').addClass('error');
                }
                if (errors) {
                   $(".content h5").text("Please fill in required fields highlighted below.").fadeIn();
                   return false; 
                }
                for (var j=0; j<numeric.length; j++) {
                    var value = $(numeric[j]).val();
                    if (!/^[0-9]+$/.test(value)){
                        $(numeric[j]).addClass('error');
                        errors++;
                    }
                }
                if (errors) {
                    $(".content h5").text("Please use numeric values for the fields highlighted below.").fadeIn();
                   return false; 
                }
                if (!/^[a-z0-9-\._]+@[a-z0-9]+\.[a-z0-9]+/i.test($("#email").val())) {
                    $('#email').addClass('error');
                    $(".content h5").text("Please use a valid email address").fadeIn();
                   return false;    
                }
                   
                $(".content h5").fadeOut();
                document['donation-form'].amount.value = amount;

                var stripeResponseHandler = function(status, response) {
                  
                  var $form = $('#donation-form');

                  if (response.error) {
                    // Show the errors on the form
                    $('.content h5').text(response.error.message);
                    $form.find('.sub-but').prop('disabled', false);
                  } else {
                    // token contains id, last4, and card type
                    var token = response.id;
                    // Insert the token into the form so it gets submitted to the server
                    var form = document['donation-form'];

                    form.stripeToken.value = token;

                   
                    // and submit
                  document['donation-form'].submit();
            
                    return false;
                  }
                };

                var $form = $("#donation-form");
                $form.find("#send_donate").prop('disabled', true);
                Stripe.card.createToken($form, stripeResponseHandler);
                
            }
        }
    })
});