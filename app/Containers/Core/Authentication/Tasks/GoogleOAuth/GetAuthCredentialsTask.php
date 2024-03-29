<?php

namespace App\Containers\Core\Authentication\Tasks\GoogleOAuth;

use App\Containers\Core\Authentication\Data\Dto\LoginDto;
use App\Ship\Parents\Tasks\Task;
use Google_Client;
use Google_Service_PeopleService;

class GetAuthCredentialsTask extends Task implements GetAuthCredentialsTaskInterface
{
    private Google_Service_PeopleService $googleService;

    /**
     * @throws \Google\Exception
     */
    public function __construct(
        private readonly Google_Client $googleClient
    )
    {
        $this->googleService = new Google_Service_PeopleService($this->googleClient);
        $constFile           = config('appSection-authentication.oauth.google.config_file');

        if (empty($constFile)) {
            $this->googleClient->setClientId(config('appSection-authentication.oauth.google.client_id'));
            $this->googleClient->setClientSecret(config('appSection-authentication.oauth.google.client_secret'));
        } else {
            $this->googleClient->setAuthConfig(storage_path($constFile));
        }

        $this->googleClient->setRedirectUri(route(config('appSection-authentication.oauth.google.callback')));
        $this->googleClient->setScopes([
            Google_Service_PeopleService::USERINFO_PROFILE,
            Google_Service_PeopleService::USERINFO_EMAIL,
        ]);
    }

    /**
     * @param string $code
     * @return \App\Containers\Core\Authentication\Data\Dto\LoginDto
     */
    public function run(string $code): LoginDto
    {
        $this->googleClient->fetchAccessTokenWithAuthCode($code);

        $person = $this->googleService->people->get('people/me', ['personFields' => 'emailAddresses,names,genders,birthdays']);
        $name   = data_get($person->getNames(), 0);

        /**
         * @var \Google_Service_PeopleService_EmailAddress $mail
         */
        $email = current($person->getEmailAddresses())->getValue();

        return (new LoginDto())
            ->setEmail(strtolower($email))
            ->setName(implode(' ', [$name->givenName, $name->familyName]))
            ->setRememberMe(true);
    }
}
