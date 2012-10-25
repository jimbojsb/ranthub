<?php

namespace GitHubAPIv3\PullRequest;

use GitHubAPIv3\AbstractEntity;

class PullRequest extends AbstractEntity
{
    protected static $propertyEntityMap = array(
        'milestone' => 'GitHubAPIv3\Issue\Milestone',
        'assignee'  => 'GitHubAPIv3\User\BasicUser',
        'user'      => 'GitHubAPIv3\User\BasicUser'
    );

    protected $url;
    protected $htmlUrl;
    protected $diffUrl;
    protected $patchUrl;
    protected $number;
    protected $state;
    protected $title;
    protected $body;
    protected $createdAt;
    protected $updatedAt;
    protected $closedAt;
    protected $mergedAt;
    protected $base;
    protected $user;

    /** Issue releated */
    protected $milestone;
    protected $assignee;


    public function getUrl()
    {
        return $this->url;
    }

    public function getHtmlUrl()
    {
        return $this->htmlUrl;
    }

    public function getDiffUrl()
    {
        return $this->diffUrl;
    }

    public function getPatchUrl()
    {
        return $this->patchUrl;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getClosedAt()
    {
        return $this->closedAt;
    }

    public function getMergedAt()
    {
        return $this->mergedAt;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return \GitHubAPIv3\User\BasicUser
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @return \GitHubAPIv3\Issue\Milestone
     */
    public function getMilestone()
    {
        return $this->milestone;
    }

}
