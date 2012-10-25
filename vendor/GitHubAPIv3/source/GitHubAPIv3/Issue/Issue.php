<?php

namespace GitHubAPIv3\Issue;

use GitHubAPIv3\AbstractEntity;

class Issue extends AbstractEntity
{

    protected $url;
    protected $htmlUrl;
    protected $number;
    protected $state;
    protected $title;
    protected $body;
    protected $user;
    protected $labels;
    protected $assignee;
    protected $milestone;
    protected $comments;
    protected $pullRequest;
    protected $closedAt;
    protected $createdAt;
    protected $updatedAt;

    public function getUrl()
    {
        return $this->url;
    }

    public function getHtmlUrl()
    {
        return $this->htmlUrl;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->updatedProperties['state'] = true;
        $this->state = $state;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->updatedProperties['title'] = true;
        $this->title = $title;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->updatedProperties['body'] = true;
        $this->body = $body;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getLabels()
    {
        return $this->labels;
    }

    public function setLabels($labels)
    {
        $this->updatedProperties['labels'] = true;
        $this->labels = (is_array($labels)) ? implode(',', $labels) : $this->labels = $labels;
        return $this;
    }

    public function getAssignee()
    {
        return $this->assignee;
    }

    public function setAssignee($assignee)
    {
        $this->updatedProperties['assignee'] = true;
        $this->assignee = $assignee;
        return $this;
    }

    public function getMilestone()
    {
        return $this->milestone;
    }

    public function setMilestone($milestone)
    {
        $this->updatedProperties['milestone'] = true;
        $this->milestone = $milestone;
        return $this;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function getPullRequest()
    {
        return $this->pullRequest;
    }

    public function getClosedAt()
    {
        return $this->closedAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

}