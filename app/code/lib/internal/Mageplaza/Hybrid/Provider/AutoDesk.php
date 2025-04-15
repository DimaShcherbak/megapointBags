<?php
/**
 * Hybridauth
 * https://hybridauth.github.io | https://github.com/hybridauth/hybridauth
 *  (c) 2020 Hybridauth authors | https://hybridauth.github.io/license.html
 */

namespace lib\internal\Mageplaza\Hybrid\Provider;

use lib\internal\Mageplaza\Hybrid\Adapter\OAuth2;

/**
 * AutoDesk OAuth2 provider adapter.
 */
class AutoDesk extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected $scope = 'data:read';

    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://developer.api.autodesk.com/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl
        = 'https://developer.api.autodesk.com/authentication/v1/authorize';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl
        = 'https://developer.api.autodesk.com/authentication/v1/gettoken';

    /**
     * {@inheritdoc}
     */
    protected $refreshTokenUrl
        = 'https://developer.api.autodesk.com/authentication/v1/refreshtoken';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation
        = 'https://forge.autodesk.com/en/docs/oauth/v2/developers_guide/overview/';

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        parent::initialize();
        
        if ($this->isRefreshTokenAvailable()) {
            $this->tokenRefreshParameters += [
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type'    => 'refresh_token',
            ];
        }
    }

    /**
     * {@inheritdoc}
     *
     * See: https://forge.autodesk.com/en/docs/oauth/v2/reference/http/users-@me-GET/
     */
    public function getUserProfile()
    {
        $response = $this->apiRequest('userprofile/v1/users/@me');

        $collection = new \lib\internal\Mageplaza\Hybrid\Data\Collection($response);

        $userProfile = new \lib\internal\Mageplaza\Hybrid\User\Profile();

        $userProfile->identifier = $collection->get('userId');
        $userProfile->displayName
            = $collection->get('firstName') .' '. $collection->get('lastName');
        $userProfile->firstName = $collection->get('firstName');
        $userProfile->lastName = $collection->get('lastName');
        $userProfile->email = $collection->get('emailId');
        $userProfile->language = $collection->get('language');
        $userProfile->webSiteURL = $collection->get('websiteUrl');
        $userProfile->photoURL
            = $collection->filter('profileImages')->get('sizeX360');

        return $userProfile;
    }
}
