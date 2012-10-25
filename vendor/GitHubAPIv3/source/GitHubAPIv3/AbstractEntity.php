<?php

namespace GitHubAPIv3;

abstract class AbstractEntity
{
    protected $updatedProperties = array();

    public function getUpdatedProperties()
    {
        return array_keys($this->updatedProperties);
    }

    public function isUpdateable()
    {
        // does it have a set method?
        foreach (get_meta_tags(get_called_class()) as $methodName) {
            if (strpos($methodName, 'set') === 0) {
                return true;
            }
        }
        return false;
    }
}