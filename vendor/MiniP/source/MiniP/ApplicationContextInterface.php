<?php

namespace MiniP;

interface ApplicationContextInterface
{
    public function createServiceLocator();
    public function initialize(Application $application);
}
