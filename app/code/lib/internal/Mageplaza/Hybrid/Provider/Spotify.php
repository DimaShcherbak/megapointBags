<?php
/*!
* Hybridauth
* https://hybridauth.github.io | https://github.com/hybridauth/hybridauth
*  (c) 2017 Hybridauth authors | https://hybridauth.github.io/license.html
*/

namespace lib\internal\Mageplaza\Hybrid\Provider;

use lib\internal\Mageplaza\Hybrid\Adapter\OAuth2;
use lib\internal\Mageplaza\Hybrid\Exception\UnexpectedApiResponseException;

/**
 * Spotify OAuth2 provider adapter.
 */
class Spotify extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected $scope = 'user-read-email';

    /**
     * {@inheritdoc}
     */
    public $apiBaseUrl = 'https://api.spotify.com/v1/';

    /**
     * {@inheritdoc}
     */
    public $authorizeUrl = 'https://accounts.spotify.com/authorize';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://accounts.spotify.com/api/token';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://developer.spotify.com/documentation/general/guides/authorization-guide/';

    /**
     * {@inheritdoc}
     */
    public function getUserProfile()
    {
        $response = $this->apiRequest('me');

        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($response);

        if (!$data->exists('id')) {
            throw new UnexpectedApiResponseException('Provider API returned an unexpected response.');
        }

        $userProfile = new \lib\internal\Mageplaza\Hybrid\User\Profile();

        $userProfile->identifier = $data->get('id');
        $userProfile->displayName = $data->get('display_name');
        $userProfile->email = $data->get('email');
        $userProfile->emailVerified = $data->get('email');
        $userProfile->profileURL = $data->filter('external_urls')->get('spotify');
        $userProfile->photoURL = $data->filter('images')->get('url');
        $userProfile->country = $data->get('country');

        if ($data->exists('birthdate')) {
            $this->fetchBirthday($userProfile, $data->get('birthdate'));
        }

        return $userProfile;
    }

    /**
     * Fetch use birthday
     *
     * @param \lib\internal\Mageplaza\Hybrid\User\Profile $userProfile
     * @param              $birthday
     *
     * @return \lib\internal\Mageplaza\Hybrid\User\Profile
     */
    protected function fetchBirthday(\lib\internal\Mageplaza\Hybrid\User\Profile $userProfile, $birthday)
    {
        $result = (new \lib\internal\Mageplaza\Hybrid\Data\Parser())->parseBirthday($birthday);

        $userProfile->birthDay = (int)$result[0];
        $userProfile->birthMonth = (int)$result[1];
        $userProfile->birthYear = (int)$result[2];

        return $userProfile;
    }
}
