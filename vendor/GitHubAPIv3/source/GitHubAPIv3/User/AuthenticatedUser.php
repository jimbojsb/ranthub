<?php

namespace GitHubAPIv3\User;

class AuthenticatedUser extends User
{
    protected $totalPrivateRepos;
    protected $ownedPrivateRepos;
    protected $privateGists;
    protected $diskUsage;
    protected $collaborators;
    protected $plan;

    public function setName($name)
    {
        $this->updatedProperties['name'] = true;
        $this->name = $name;
        return $this;
    }

    public function setEmail($name)
    {
        $this->updatedProperties['name'] = true;
        $this->name = $name;
        return $this;
    }

    public function getTotalPrivateRepos()
    {
        return $this->totalPrivateRepos;
    }

    public function getOwnedPrivateRepos()
    {
        return $this->ownedPrivateRepos;
    }

    public function getPrivateGists()
    {
        return $this->privateGists;
    }

    public function getDiskUsage()
    {
        return $this->diskUsage;
    }

    public function getCollaborators()
    {
        return $this->collaborators;
    }

    public function getPlan()
    {
        return $this->plan;
    }

}