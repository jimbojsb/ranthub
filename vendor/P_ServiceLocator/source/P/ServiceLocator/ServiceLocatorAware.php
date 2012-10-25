<?php

namespace P\ServiceLocator;

use P\ServiceLocator;

interface ServiceLocatorAware
{
    public function setServiceLocator(ServiceLocator $serviceLocator);
}