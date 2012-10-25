<?php

namespace GitHubAPIv3\User;

use GitHubAPIv3\AbstractAPI;

class UserAPI extends AbstractAPI
{

    /**
     * @param string $user
     * @return User
     */
    public function getUser($user)
    {
        $api = 'GET /users/' . $user;
        $userData = $this->doAPIRequest($api);
        return $this->createEntity('GitHubAPIv3\User', $userData);
    }

    /**
     * http://developer.github.com/v3/users/#get-the-authenticated-user
     * @return AuthenticatedUser
     */
    public function getAuthenticatedUser()
    {
        if (!$this->accessToken) {
            throw new \RuntimeException('This API requires an access_token');
        }

        $api = 'GET /user';
        $userData = $this->doAPIRequest($api);
        return $this->createEntity(__NAMESPACE__ . '\AuthenticatedUser', $userData);
    }

    /**
     * @param AuthenticatedUser $user
     * @return bool
     */
    public function updateAuthenticatedUser(AuthenticatedUser $user)
    {

        return true;
    }

}