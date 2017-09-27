OTP Bank Payment Gateway for WooCommerce
=========================

The plugin supports the 3 step payment via the [OTP Bank](https://www.otpbank.hu/). The customer will be redirected to the OTP's secure website and the payment will be made there.

## Installation

Download the [latest stable version](https://github.com/woohgit/woocommerce-otpbank-payment-gateway/releases/latest) and install it simply just like any other WordPress / WooCommerce plugin.

## Configuration options

After installing the plugin, you can configure the plugin via the WooCommerce admin interface.

**Shop ID**

Each shop has it's unique webshop id. This is also known as PosID. Note that your private key will be provided by the OTP and it's without a leading **#**. Only the testing keys are started with character **#**.

**Shop key**

Your custom key will be provided by the OTP. The testing key is shown in the Test credentials section below.

**Shop lang**

The language of the OTP secure payment site the customer will be redirected to.

**Shop currency**

The currency used for the payment.

## Test credentials

For testing purpose, OTP provides test cards and pos ids for your site. You can use the cards and pos ids below to test your site.

Note: All PosIDs and RSA keys has to be pasted as they're shown here.

**Card numbers for testing**

| Card number            | Expiration date    | CVC2  | payment will be |
| :--------------------: |:------------------:|:-----:| --------------- |
| 4908 3660 9990 0425    | 2014.10 (1014)     | 823   | accepted        |
| 1111 1111 1111 1117    | 2004.04 (0404)     | 111   | rejected        |
| *Cafateria card*       |                    |       |                 |
| 6101 3240 1000 2441    | 2006.02 (0206)     | none  | accepted        |


**POS IDs for testing**

| Currency | PosID     |
| -------- | --------- |
| HUF      | #02299991 |
| EUR      | #02299992 |
| USD      | #02299993 |

**Private RSA key for testing**

```
-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQCpRN6hb8pQaDen9Qjt18P2FqScF2uhjKfd0DZ1t0HWtvYMmJGf
M6+wgjQGDHHc4LAcLIHF1TQVLCYdbyLzsOTRUhi4UFsW18IBznoEAx2wxiTCyzxt
ONpIkr5HD2E273UbXvVKA2hig2BgpOA2Poil9xtOXIm63iVw6gjP2qDnNwIDAQAB
AoGBAKP1ctTbDRRPrsGBB3IjEsz3Z+FOilIEhcHE4kuqBBswRCs1SbD1BtQpeqz1
NwGlntDbh6SSfQ2ZIx5VvXxhN3G6lNC0Mb15ra3XMjyHVHG7c/q3rDzhxFE361NO
uIgPdN1kVDCKm+RNmTbyLhCCpfbNIv8UlT2XnlajdMnPOzCZAkEA2trOehgWMFnl
IDDYAu1MtoNPPXCLfa44oCwwUmWg2Q/DbFWYs5u2wlk4xfnv9qGbv0DBuGesTYT0
DzjP0nqBkwJBAMX/kyNJ4TBouRDDnSj99Sdx57aYbzC1Xikt6FJVpT4P1KiGSoj6
OYSF8hz5kG90Dbv6W8Q4TwMbiFIOy9pFGk0CQQDG1OWj7UAze2h8H4QQ3LDGXHPg
WOCSNXeCpcLdCTHiIr0kLnwGKaEX3uGClDlb86VBU77sH1xeLT1imvXMvrn7AkEA
iktDqz88EYLj2Gi5Cduv8vglPy1jZGMZvKt6/J8jhqCqCXea8efMatrfzAsoLiCi
QyzQEdK+pU4CvkXlbrQbdQJAdLTPLSakZaN47bijXY05v11aC5ydb2pOpLHGKneX
wOt1vzdPct1YSk88YMD9RUi/xk/VnJHQ7cq8ltAXK/QNYA==
-----END RSA PRIVATE KEY-----
```
