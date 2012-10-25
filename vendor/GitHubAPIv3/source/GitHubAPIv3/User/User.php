<?php

namespace GitHubAPIv3\User;

use GitHubAPIv3\AbstractEntity;

class User extends BasicUser
{


    protected $name;
    protected $company;
    protected $blog;
    protected $location;
    protected $email;
    protected $hireable;
    protected $bio;
    protected $publicRepos;
    protected $publicGists;
    protected $followers;
    protected $following;
    protected $htmlUrl;
    protected $createdAt;
    protected $type;

    public function getName()
    {
        return $this->name;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getBlog()
    {
        return $this->blog;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getHireable()
    {
        return $this->hireable;
    }

    public function getBio()
    {
        return $this->bio;
    }

    public function getPublicRepos()
    {
        return $this->publicRepos;
    }

    public function getPublicGists()
    {
        return $this->publicGists;
    }

    public function getFollowers()
    {
        return $this->followers;
    }

    public function getFollowing()
    {
        return $this->following;
    }

    public function getHtmlUrl()
    {
        return $this->htmlUrl;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getType()
    {
        return $this->type;
    }

}
