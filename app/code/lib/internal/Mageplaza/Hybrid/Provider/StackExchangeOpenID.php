<?php
/*!
* Hybridauth
* https://hybridauth.github.io | https://github.com/hybridauth/hybridauth
*  (c) 2017 Hybridauth authors | https://hybridauth.github.io/license.html
*/

namespace lib\internal\Mageplaza\Hybrid\Provider;

use lib\internal\Mageplaza\Hybrid\Adapter\OpenID;

/**
 * StackExchange OpenID provider adapter.
 */
class StackExchangeOpenID extends OpenID
{
    /**
     * {@inheritdoc}
     */
    protected $openidIdentifier = 'https://openid.stackexchange.com/';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://openid.stackexchange.com/';

    /**
     * {@inheritdoc}
     */
    public function authenticateFinish()
    {
        parent::authenticateFinish();

        $userProfile = $this->storage->get($this->providerId . '.user');

        $userProfile->identifier = !empty($userProfile->identifier) ? $userProfile->identifier : $userProfile->email;
        $userProfile->emailVerified = $userProfile->email;

        // re store the user profile
        $this->storage->set($this->providerId . '.user', $userProfile);
    }
}
