# OTP Bank (Hungary) - WooCommerce Payment Gateway

Work in progress. I want to completely refactor and rewrite this code, but for now it's only a PoC, I did this in a hurry to help a friend.

Note that this code has baked in test key/pem and PosID. You can't upload the keys via the WooCommerce admin page, you need to adjust the code in order to activate the production credentials. In the stable verison of course you'll be able to upload the keys and set the PosID via the admin UI.

Another important thing is that the code by default supports only the "Default" URL Wordpress/WooCommerce permalinks at the moment, so it assumes the wc-api is located at:


```
http://<yourdomain>/?wc-api=callback&...
```

If you're using pretty permalinks and your wc-api is like below, you need to adjust the code:

```
http://<yourdomain>/wc-api/callback/...
```


in [woocommerce-otp.php](woocommerce-otp.php#L168), l ine 168. you have to change this line:

```php
$backURL = WC()->api_request_url( 'WC_Gateway_OTPBank' )."&fizetesValasz=true&posId=%2302299991&tranzakcioAzonosito=".$tranzakcioAzonosito;
```

to this:
```php
$backURL = WC()->api_request_url( 'WC_Gateway_OTPBank' )."?fizetesValasz=true&posId=%2302299991&tranzakcioAzonosito=".$tranzakcioAzonosito;
```
