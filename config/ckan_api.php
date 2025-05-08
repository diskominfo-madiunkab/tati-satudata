<?php

/**
 * CKAN API CONFIGURATION
 *
 * url: is the base ckan url, for example https://data.myckan.com
 * api_key: To find it, login to the CKAN site using its web interface and visit your user profile page.
 * api_version: Api version that you want to use, we use the last one, so leave it empty if you are sure.
 */
return [
    'url' => env('CKAN_API_URL', ''),
    'api_key' => env('CKAN_API_KEY', ''),
    'api_version' => env('CKAN_API_VERSION', ''),
    'guzzle' => false,
    'container' => env('CKAN_API_CONTAINER', ''),

    'repositories' => [
        'per_page' => 20,
    ],

    // 'curl_options' => [
    //     CURLOPT_SSL_VERIFYPEER => false,
    //     CURLOPT_SSL_VERIFYHOST => false,

    // ],
];
