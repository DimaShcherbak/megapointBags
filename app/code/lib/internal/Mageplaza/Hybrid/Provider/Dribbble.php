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
 * Dribbble OAuth2 provider adapter.
 */
class Dribbble extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://api.dribbble.com/v2/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://dribbble.com/oauth/authorize';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://dribbble.com/oauth/token';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'http://developer.dribbble.com/v2/oauth/';

    /**
     * {@inheritdoc}
     */
    public function getUserProfile()
    {
        $response = $this->apiRequest('user');

        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($response);

        if (!$data->exists('id')) {
            throw new UnexpectedApiResponseException('Provider API returned an unexpected response.');
        }

        $userProfile = new \lib\internal\Mageplaza\Hybrid\User\Profile();

        $userProfile->identifier = $data->get('id');
        $userProfile->profileURL = $data->get('html_url');
        $userProfile->photoURL = $data->get('avatar_url');
        $userProfile->description = $data->get('bio');
        $userProfile->region = $data->get('location');
        $userProfile->displayName = $data->get('name');

        $userProfile->displayName = $userProfile->displayName ?: $data->get('username');

        $userProfile->webSiteURL = $data->filter('links')->get('web');

        return $userProfile;
    }
}
