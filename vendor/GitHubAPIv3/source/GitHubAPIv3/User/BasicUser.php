<?php

namespace GitHubAPIv3\User;

use GitHubAPIv3\AbstractEntity;

class BasicUser extends AbstractEntity
{
    protected $login;
    protected $id;
    protected $avatarUrl;
    protected $gravatarId;
    protected $url;

    public function getLogin()
    {
        return $this->login;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    public function getGravatarId()
    {
        return $this->gravatarId;
    }

    public function getUrl()
    {
        return $this->url;
    }

}
