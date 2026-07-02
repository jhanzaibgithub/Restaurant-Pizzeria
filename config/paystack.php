<?php

/*
 * This file is part of the Laravel Paystack package.
 *
 * (c) Prosper Otemuyiwa <prosperotemuyiwa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /**
     * Public Key From Paystack Dashboard
     *
     */
    'publicKey' => getenv('PAYSTACK_PUBLIC_KEY','pk_test_1f1bfc025d99b92410bc0de7289e84ba1864ff27'),

    /**
     * Secret Key From Paystack Dashboard
     *
     */
    'secretKey' => getenv('PAYSTACK_SECRET_KEY','sk_test_379cc93da264aaaf5a8eb30cb9434c160bb76e87'),

    /**
     * Paystack Payment URL
     *
     */
    'paymentUrl' => getenv('PAYSTACK_PAYMENT_URL',"https://api.paystack.co"),

    /**
     * Optional email address of the merchant
     *
     */
    'merchantEmail' => getenv('MERCHANT_EMAIL','showrov2185@gmail.com'),

];
