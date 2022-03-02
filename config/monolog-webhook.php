<?php

return [
    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    |
    | This is the URL that the exception handler will send data to.
    |
    | This is a required value.
    |
    */
    'url'            => '',

    /*
    |--------------------------------------------------------------------------
    | Signing secret
    |--------------------------------------------------------------------------
    |
    | This option specifies the secret that will be used to sign the payload
    | that will be sent to the receiving webhook endpoint.
    |
    | This is a required value.
    |
    */
    'signing_secret' => '',

    /*
    |--------------------------------------------------------------------------
    | Default signature suffix
    |--------------------------------------------------------------------------
    |
    | This options control the default suffix that will be used when sending
    | requests to the webhook url.
    |
    | The default header is "Signature". You are merely adding a prefix to this.
    |
    | You may specify anything here as long as it will match your receiving
    | endpoint's signature verification algorithm.
    |
    */
    'header_prefix'  => '',
];
