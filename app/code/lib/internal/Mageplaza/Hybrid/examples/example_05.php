<?php
/*!
* A simple example that shows how to use Guzzle as a Http Client for Hybridauth instead of PHP Curl extention.
*/

include 'vendor/autoload.php';

$config = [
    'callback' => \lib\internal\Mageplaza\Hybrid\HttpClient\Util::getCurrentUrl(),

    'keys' => ['id' => '', 'secret' => ''],
];

$guzzle = new \lib\internal\Mageplaza\Hybrid\HttpClient\Guzzle(null, [
    // 'verify' => true, # Set to false to disable SSL certificate verification
]);

try {
    $adapter = new \lib\internal\Mageplaza\Hybrid\Provider\Github($config, $guzzle);

    $adapter->authenticate();

    $tokens = $adapter->getAccessToken();
    $userProfile = $adapter->getUserProfile();

    // print_r($tokens);
    // print_r($userProfile);

    $adapter->disconnect();
} catch (Exception $e) {
    echo $e->getMessage();
}
