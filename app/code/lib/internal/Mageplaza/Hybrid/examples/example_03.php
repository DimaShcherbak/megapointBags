<?php
/*!
* An example on how use Access Tokens to access providers APIs, and how to setup custom API endpoints.
*/

include 'vendor/autoload.php';

$config = [
    'callback' => \lib\internal\Mageplaza\Hybrid\HttpClient\Util::getCurrentUrl(),

    'keys' => ['id' => 'your-facebook-app-id', 'secret' => 'your-facebook-app-secret'],

    'endpoints' => [
        'api_base_url' => 'https://graph.facebook.com/v2.8/',
        'authorize_url' => 'https://www.facebook.com/dialog/oauth',
        'access_token_url' => 'https://graph.facebook.com/oauth/access_token',
    ]
];

try {
    $adapter = new \lib\internal\Mageplaza\Hybrid\Provider\Facebook($config);

    $adapter->setAccessToken(['access_token' => 'user-facebook-access-token']);

    $userProfile = $adapter->getUserProfile();

    // print_r($userProfile);

    $adapter->disconnect();
} catch (Exception $e) {
    echo $e->getMessage();
}
