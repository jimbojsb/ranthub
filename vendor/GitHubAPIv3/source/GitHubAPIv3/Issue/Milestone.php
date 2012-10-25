<?php

namespace GitHubAPIv3\Issue;

use GitHubAPIv3\AbstractEntity;

class Milestone extends AbstractEntity
{
    protected static $propertyEntityMap = array(
        'creator' => 'GitHubAPIv3\User\BasicUser'
    );

    protected $url;
    protected $number;
    protected $state;
    protected $title;
    protected $description;
    protected $creator;
    protected $openIssues;
    protected $closedIssues;
    protected $createdAt;
    protected $dueOn;

    public function getUrl()
    {
        return $this->url;
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

    public function getDescription()
    {
        return $this->description;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function getOpenIssues()
    {
        return $this->openIssues;
    }

    public function getClosedIssues()
    {
        return $this->closedIssues;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getDueOn()
    {
        return $this->dueOn;
    }

}
