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
 * GitLab OAuth2 provider adapter.
 */
class Strava extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected $scope = 'profile:read_all';

    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://www.strava.com/api/v3/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://www.strava.com/oauth/authorize';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://www.strava.com/oauth/token';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://developers.strava.com/docs/reference/';

    /**
     * {@inheritdoc}
     */
    public function getUserProfile()
    {
        $response = $this->apiRequest('athlete');

        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($response);

        if (!$data->exists('id')) {
            throw new UnexpectedApiResponseException('Provider API returned an unexpected response.');
        }

        $userProfile = new \lib\internal\Mageplaza\Hybrid\User\Profile();

        $userProfile->identifier = $data->get('id');
        $userProfile->firstName = $data->get('firstname');
        $userProfile->lastName = $data->get('lastname');
        $userProfile->gender = $data->get('sex');
        $userProfile->country = $data->get('country');
        $userProfile->city = $data->get('city');
        $userProfile->email = $data->get('email');

        $userProfile->displayName = $userProfile->displayName ?: $data->get('username');

        return $userProfile;
    }
}
