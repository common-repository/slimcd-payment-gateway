jQuery(document).ready(function () {
  jQuery(document).ajaxComplete(function () {
    managePlaceOrderButton();
    jQuery("input[name='payment_method']:checked").val();
    if (
      jQuery("input[type=radio][name=payment_mode]:checked").val() ==
      "payment_mode_card"
    ) {
      jQuery("#slimcdDisclaimerCreditCard").show();
    } else if (
      jQuery("input[type=radio][name=payment_mode]:checked").val() ==
      "payment_mode_cheque"
    ) {
      jQuery("#slimcdDisclaimerChecks").show();
    } else {
      jQuery(".slimcdPaymentDisclaimer").show();
    }

    jQuery(document).on("change", "#payment", function () {
      managePlaceOrderButton();
    });

    jQuery(function () {
      jQuery(document).on(
        "change",
        "input[type=radio][name=payment_mode]",
        manageDisclaimer
      );
    });

    function manageDisclaimer() {
      {
        jQuery(".slimcdPaymentDisclaimer").hide();
        if (
          jQuery("input[type=radio][name=payment_mode]:checked").val() ==
          "payment_mode_card"
        ) {
          jQuery("#slimcdDisclaimerCreditCard").show();
        } else {
          jQuery("#slimcdDisclaimerChecks").show();
        }
      }
    }

    function managePlaceOrderButton() {
      var payment_option = jQuery("input[name='payment_method']:checked").val();
      if (payment_option == "slimcd_payment") {
        if (jQuery("#slimcdDisclaimerError").length) {
          jQuery("#payment #place_order")
            .attr("disabled", "disabled")
            .attr("title", jQuery("#slimcdDisclaimerError p").text());
        }
      } else {
        jQuery("#payment #place_order")
          .removeAttr("disabled")
          .removeAttr("title");
      }
    }
  });
});
