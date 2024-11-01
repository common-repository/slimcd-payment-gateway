=== Slim CD payment gateway ===
Contributors: slim cd
Tags: : ecommerce, e-commerce, commerce, woothemes, wordpress ecommerce, store, sales, sell, shop, shopping, cart, checkout
Requires at least: 4.9.0
Tested up to: 6.2
Stable tag: 1.0.3
Requires PHP: 7.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Accept credit card/check payments for woocommerce stores, using your own merchant account.


== Description ==
Slim CD allows you to easy accept payments in WooCommerce, using your existing merchant account. Slim CD’s Hosted Payment Pages keep your web site out of PCI scope and tokenize all transactions for future use.  Payments are entered on Slim CD’s site, so checkout is secure and encrypted.  The hosted payment pages can process payments via credit/debit cards or with a checking account.

[Tutorial video](https://slimcd.com/videos/)

**More about SLIM CD**

- [Slim CD Website](https://www.slimcd.com)



Features of **Slim CD payment gateway** plugin:

1. Easy configuration.
2. Accept Credit or Debit cards. 
3. Accept ACH from Checking Accounts.
4. Uses customizable SLIM CD Hosted Payment Pages. 
5. PCI compliant, providing secure payments and meets [PCI Compliance SAQ-A standards](https://www.pcisecuritystandards.org/documents/Understanding_SAQs_PCI_DSS_v3.pdf).


If you have any question or features request, please access the plugin's official support forum. You can also get help from [Slim CD's website](https://slimcd.com/contact/).

== Installation ==

#### From within WordPress

1. Visit 'Plugins > Add New'
2. Search for 'SlimCD payment gateway'
3. Activate 'Slim CD for WooCommerce ' for WordPress from your 'Plugins' menu from WordPress.
4. Visit 'WooCommerce' menu and click 'settings' to navigate to payment options.
5. Enable Slim CD payment gateway from payment section.
6. Click manage option and enter the API details.

#### Manually

1. Upload the slimcd-payment-for-woocommerce/ folder to the /wp-content/plugins/ directory. 
2. Activate the plugin through the 'Plugins' menu in WordPress. 
3. Visit 'Woocommerce' menu and click 'settings' to navigate to payment options.
4. Enable Slim CD payment gateway from payment section.
5. Click manage option and enter the API details. 


== Screenshots ==

1. Setting page.
2. API section.
3. Credit card options.
4. Check payment options.
5. Payment option.


== Frequently Asked Questions ==
= 1. How do I get Slim CD Account? =
Slim CD uses your existing merchant account.  Please contact your bank and ask them to set up SLIM CD for your account.  You can request additional information [here](https://slimcd.com/contact/).

= 2. Does Slim CD have sandbox setup? =
Slim CD can provide test account upon request. Contact us [here](https://slimcd.com/contact/).

= 3. What are payment options available? =
Slim CD can accept credit card, debit card through your existing merchant account. Slim CD also supports ACH/checking accounts.  If you need a merchant account, please contact your bank and request a merchant account to be used with SLIM CD.

= 4. What is form name? =
Slim CD for WooCommerce uses Slim CD’s Hosted Payment Pages to keep your website out of scope for PCI.  A Hosted Payment Page is created on SLIM CD’s servers.  Each page as a “form name”.  Create a Hosted Payment Page (with a form name) and use that name to identify the form.

= 5. what is a post back url? =
A post back url is slient api call back url where you can add in your form.A postback url is part of the Hosted Payment Page.  Thus URL is triggered as a slient api callback url, from SLIM CD’s servers to your WooCommerce site.  The postback url must be configured in your Slim CD Hosted Payment Page.  This allows Slim CD’s servers to inform WooCommerce of the success/failure of the payment.  It must contain a fully-qualified domain name so that Slim CD can update your WooCommerce site.

= 6. what is a redirect url? =
After processing a payment, the Slim CD form will redirect the consumer back to the redirect url on your server.  This allows the consumer to continue shopping or using your website.  The redirect url is configured inside the Slim CD Hosted Payment Page and must contain a fully-qualified URL.

= 7. Where can enter the post back and redirect url? =
Log into SLIMCD.COM and edit the Hosted Payment Page.  The Hosted Payment Page contains entries for the post back and redirect URLs.

= 8. Is post back and redirect url mandatory? =
Yes, both the post back url and the redirect url must be configured in the Slim CD Hosted Payment Page for the plugin to function properly.

== Changelog ==

= 1.0.0 =
Initial release.

= 1.0.1 =
Fixed reported bugs.

= 1.0.2 =
Added user choice to select status after payment.

= 1.0.3 =
Added payment gateway (transaction) id to post meta table.

