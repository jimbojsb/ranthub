<?php

namespace GitHubAPIv3\PullRequest;

use GitHubAPIv3\AbstractAPI;

class PullRequestAPI extends AbstractAPI
{

    public function getPullRequests($user, $repo, array $parameters = array())
    {
        // @todo
        $parameters = $this->processParameters(
            array('page' => null, 'per_page' => null, 'state' => array('open', 'closed')),
            $parameters
        );
        
        $api = "GET /repos/$user/$repo/pulls";
        if ($parameters) {
            $api .= '?' . http_build_query($parameters);
        }
        $prs = $this->doAPIRequest($api);
        if ($prs == false) {
            return array();
        }

        $prsEntities = array();
        foreach ($prs as $pr) {
            $prsEntities[] = $this->createEntity(__NAMESPACE__ . '\PullRequest', $pr);
        }
        return $prsEntities;
    }

    public function getPullRequest($user, $repo, $number)
    {
        $api = "GET /repos/:user/:repo/pulls/:number";

    }

}