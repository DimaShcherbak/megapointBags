<?php
/*!
* Hybridauth
* https://hybridauth.github.io | https://github.com/hybridauth/hybridauth
*  (c) 2017 Hybridauth authors | https://hybridauth.github.io/license.html
*/

namespace lib\internal\Mageplaza\Hybrid\Provider;

/**
 * Blizzard EU Battle.net OAuth2 provider adapter.
 */
class BlizzardEU extends Blizzard
{
    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://eu.battle.net/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://eu.battle.net/oauth/authorize';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://eu.battle.net/oauth/token';
}
