<?php

namespace GitHubAPIv3\User;

use GitHubAPIv3\AbstractAPI;

class RateLimitAPI extends AbstractAPI
{

    public function getRateLimit($which = null)
    {
        // get data from API data if it exists
        if (isset($this->accessToken) && isset(self::$apiData['rate'][$this->accessToken])) {
            $data = self::$apiData['rate'][$this->accessToken];
            $data['fromHeader'] = true;
            return $data;
        }

        $api = 'GET /rate_limit';
        $info = $this->doAPIRequest($api);
        return $info['rate'];
    }
}