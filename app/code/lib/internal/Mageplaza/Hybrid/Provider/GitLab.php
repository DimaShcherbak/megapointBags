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
class GitLab extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected $scope = 'api';

    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://gitlab.com/api/v3/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://gitlab.com/oauth/authorize';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://gitlab.com/oauth/token';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://docs.gitlab.com/ee/api/oauth2.html';

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
        $userProfile->displayName = $data->get('name');
        $userProfile->description = $data->get('bio');
        $userProfile->photoURL = $data->get('avatar_url');
        $userProfile->profileURL = $data->get('web_url');
        $userProfile->email = $data->get('email');
        $userProfile->webSiteURL = $data->get('website_url');

        $userProfile->displayName = $userProfile->displayName ?: $data->get('username');

        return $userProfile;
    }
}
