<?php

namespace GitHubAPIv3\Issue;

use GitHubAPIv3\AbstractAPI;

class IssueAPI extends AbstractAPI
{

    public function getIssues(array $parameters = array())
    {
        if ($this->accessToken == null) {
            throw new \RuntimeException(__CLASS__ . '::' . __METHOD__ . ' requires a Github Access Token to execute');
        }

        // @todo parameters

        $issues = $this->doAPIRequest('GET /issues');
        if ($issues === false) {
            return false;
        }

        $entities = array();
        foreach ($issues as $issue) {
            $entities[] = $this->createEntity(__NAMESPACE__ . '\Issue', $issue);
        }
        return $entities;
    }

    /**
     * @link http://developer.github.com/v3/issues/#list-issues-for-a-repository
     *
     * Access Token Not Required
     *
     * @param string $user
     * @param string $repo
     * @param array $parameters
     * @return Issue[]
     */
    public function getRepositoryIssues($user, $repo, array $parameters = array())
    {
        // @todo
        $validParameters = array(
            'type' => array('all', 'owner', 'member'),
            'sort' => array('created', 'updated', 'pushed', 'full_name'),
            'direction' => array('asc', 'desc')
        );

        $issues = $this->doAPIRequest("GET /repos/$user/$repo/issues");
        if ($issues === false) {
            return false;
        }

        $entities = array();
        foreach ($issues as $issue) {
            $entities[] = $this->createEntity(__NAMESPACE__ . '\Issue', $issue);
        }
        return $entities;
    }

    /**
     * Access Token Not Required
     *
     * @param $user
     * @param $repo
     * @param $number
     * @return Issue|false
     */
    public function getIssue($user, $repo, $number)
    {
        $issue = $this->doAPIRequest("GET /repos/$user/$repo/issues/$number");
        if (!$issue) {
            return false;
        }
        return $this->createEntity(__NAMESPACE__ . '\Issue', $issue);
    }

    /**
     * @param $user
     * @param $repo
     * @param array $data
     */
    public function createIssue($user, $repo, array $data)
    {
        $data = $this->doAPIRequest("POST /repos/$user/$repo/issues", $data);
        return $this->createEntity(__NAMESPACE__ . '\Issue', $data);
    }

    /**
     * @param $user
     * @param $repo
     * @param Issue $issue
     */
    public function createIssueWithEntity($user, $repo, Issue $issue)
    {
        $data = $this->createArrayFromUpdatedProperties($issue);
        $data = $this->doAPIRequest("POST /repos/$user/$repo/issues", $data);
        $this->synchronizeEntity($issue, $data);
    }

    /**
     * @param Issue $issue
     * @throws \RuntimeException
     */
    public function editIssueWithEntity(Issue $issue)
    {
        $patchUrl = $issue->getUrl(); // issue has url embedded (if it already exists)
        if ($patchUrl == '') {
            throw new \RuntimeException('This issue does not have a valid url embedded, which can only come from issues pulled from GitHub');
        }
        $data = $this->createArrayFromUpdatedProperties($issue);
        $data = $this->doAPIRequest("PATCH $patchUrl", $data);
        $this->synchronizeEntity($issue, $data);
    }

    /**
     * @param $user
     * @param $repo
     * @param $number
     * @param array $data
     */
    public function editIssue($user, $repo, $number, array $data)
    {
        $data = $this->doAPIRequest("PATCH /repos/$user/$repo/issues/$number", $data);
        return $this->createEntity(__NAMESPACE__ . '\Issue', $data);
    }

}
