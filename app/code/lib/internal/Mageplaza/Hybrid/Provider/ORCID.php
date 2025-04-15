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
 * ORCID OAuth2 provider adapter.
 */
class ORCID extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected $scope = '/authenticate';

    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://pub.orcid.org/v2.1/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://orcid.org/oauth/authorize';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://orcid.org/oauth/token';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://members.orcid.org/api/';

    /**
     * {@inheritdoc}
     */
    protected function validateAccessTokenExchange($response)
    {
        $data = parent::validateAccessTokenExchange($response);
        $this->storeData('orcid', $data->get('orcid'));
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserProfile()
    {
        $response = $this->apiRequest($this->getStoredData('orcid') . '/record');
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($response['record']);

        if (!$data->exists('orcid-identifier')) {
            throw new UnexpectedApiResponseException('Provider API returned an unexpected response.');
        }

        $profile = new \lib\internal\Mageplaza\Hybrid\User\Profile();

        $profile = $this->getDetails($profile, $data);
        $profile = $this->getBiography($profile, $data);
        $profile = $this->getWebsite($profile, $data);
        $profile = $this->getName($profile, $data);
        $profile = $this->getEmail($profile, $data);
        $profile = $this->getLanguage($profile, $data);
        $profile = $this->getAddress($profile, $data);

        return $profile;
    }

    /**
     * Get profile details.
     *
     * @param \lib\internal\Mageplaza\Hybrid\User\Profile $profile
     * @param \lib\internal\Mageplaza\Hybrid\Data\Collection $data
     *
     * @return \lib\internal\Mageplaza\Hybrid\User\Profile
     */
    protected function getDetails(\lib\internal\Mageplaza\Hybrid\User\Profile $profile, \lib\internal\Mageplaza\Hybrid\Data\Collection $data)
    {
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('orcid-identifier'));

        $profile->identifier = $data->get('path');
        $profile->profileURL = $data->get('uri');

        return $profile;
    }

    /**
     * Get profile biography.
     *
     * @param \lib\internal\Mageplaza\Hybrid\User\Profile $profile
     * @param \lib\internal\Mageplaza\Hybrid\Data\Collection $data
     *
     * @return \lib\internal\Mageplaza\Hybrid\User\Profile
     */
    protected function getBiography(\lib\internal\Mageplaza\Hybrid\User\Profile $profile, \lib\internal\Mageplaza\Hybrid\Data\Collection $data)
    {
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('person'));
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('biography'));

        $profile->description = $data->get('content');

        return $profile;
    }

    /**
     * Get profile website.
     *
     * @param \lib\internal\Mageplaza\Hybrid\User\Profile $profile
     * @param \lib\internal\Mageplaza\Hybrid\Data\Collection $data
     *
     * @return \lib\internal\Mageplaza\Hybrid\User\Profile
     */
    protected function getWebsite(\lib\internal\Mageplaza\Hybrid\User\Profile $profile, \lib\internal\Mageplaza\Hybrid\Data\Collection $data)
    {
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('person'));
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('researcher-urls'));
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('researcher-url'));

        if ($data->exists(0)) {
            $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get(0));
        }

        $profile->webSiteURL = $data->get('url');

        return $profile;
    }

    /**
     * Get profile name.
     *
     * @param \lib\internal\Mageplaza\Hybrid\User\Profile $profile
     * @param \lib\internal\Mageplaza\Hybrid\Data\Collection $data
     *
     * @return \lib\internal\Mageplaza\Hybrid\User\Profile
     */
    protected function getName(\lib\internal\Mageplaza\Hybrid\User\Profile $profile, \lib\internal\Mageplaza\Hybrid\Data\Collection $data)
    {
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('person'));
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('name'));

        if ($data->exists('credit-name')) {
            $profile->displayName = $data->get('credit-name');
        } else {
            $profile->displayName = $data->get('given-names') . ' ' . $data->get('family-name');
        }

        $profile->firstName = $data->get('given-names');
        $profile->lastName = $data->get('family-name');

        return $profile;
    }

    /**
     * Get profile email.
     *
     * @param \lib\internal\Mageplaza\Hybrid\User\Profile $profile
     * @param \lib\internal\Mageplaza\Hybrid\Data\Collection $data
     *
     * @return \lib\internal\Mageplaza\Hybrid\User\Profile
     */
    protected function getEmail(\lib\internal\Mageplaza\Hybrid\User\Profile $profile, \lib\internal\Mageplaza\Hybrid\Data\Collection $data)
    {
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('person'));
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('emails'));
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('email'));

        if (!$data->exists(0)) {
            $email = $data;
        } else {
            $email = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get(0));

            $i = 1;
            while ($email->get('@attributes')['primary'] == 'false') {
                $email = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get($i));
                $i++;
            }
        }

        if ($email->get('@attributes')['primary'] == 'false') {
            return $profile;
        }

        $profile->email = $email->get('email');

        if ($email->get('@attributes')['verified'] == 'true') {
            $profile->emailVerified = $email->get('email');
        }

        return $profile;
    }

    /**
     * Get profile language.
     *
     * @param \lib\internal\Mageplaza\Hybrid\User\Profile $profile
     * @param \lib\internal\Mageplaza\Hybrid\Data\Collection $data
     *
     * @return \lib\internal\Mageplaza\Hybrid\User\Profile
     */
    protected function getLanguage(\lib\internal\Mageplaza\Hybrid\User\Profile $profile, \lib\internal\Mageplaza\Hybrid\Data\Collection $data)
    {
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('preferences'));

        $profile->language = $data->get('locale');

        return $profile;
    }

    /**
     * Get profile address.
     *
     * @param \lib\internal\Mageplaza\Hybrid\User\Profile $profile
     * @param \lib\internal\Mageplaza\Hybrid\Data\Collection $data
     *
     * @return \lib\internal\Mageplaza\Hybrid\User\Profile
     */
    protected function getAddress(\lib\internal\Mageplaza\Hybrid\User\Profile $profile, \lib\internal\Mageplaza\Hybrid\Data\Collection $data)
    {
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('person'));
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('addresses'));
        $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get('address'));

        if ($data->exists(0)) {
            $data = new \lib\internal\Mageplaza\Hybrid\Data\Collection($data->get(0));
        }

        $profile->country = $data->get('country');

        return $profile;
    }
}
