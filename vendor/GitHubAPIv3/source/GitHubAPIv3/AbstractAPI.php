<?php

namespace GitHubAPIv3;

abstract class AbstractAPI
{
    protected static $apiData = array('rate' => array());

    /** @var string */
    protected $authentication = null;

    protected $lastRequestMetadata = null;
    protected $lastRequestBody = null;
    protected $lastRequestBodyDecoded = null;

    /**
     * 
     */
    public function __construct($authentication = null)
    {
        if (is_string($authentication) && strlen($authentication) == 40) {
            $this->authentication = $authentication;
        } elseif (func_num_args() == 2) {
            $this->authentication = array(
                'username' => func_get_arg(0),
                'password' => func_get_arg(1) 
            );
        } elseif (is_array($authentication) && isset($authentication['username']) && isset($authentication['password'])) {
            $this->authentication = $authentication;
        } elseif ($authentication !== null) {
            throw new \InvalidArgumentException('Authentication should either be an access token from github, or an array("username"=>xxx,"password"=>xxx)');
        }
    }

    public function getLastRequestMetadata()
    {
        return $this->lastRequestMetadata;
    }
    
    public function getLastRequestBody()
    {
        return $this->lastRequestBody;
    }

    protected function doAPIRequest($api, array $content = array())
    {
        list($method, $urlPath) = explode(' ', $api, 2);

        // set method and json header
        $httpOptions = array(
            'method' => $method,
            'header' => "Accept: application/json\r\nContent-type: application/json\r\n",
            'ignore_errors' => true,
        );

        // add in token
        if (is_string($this->authentication)) {
            $httpOptions['header'] .= 'Authorization: token ' . $this->authentication . "\r\n";
        } elseif (is_array($this->authentication)) {
            $httpOptions['header'] .= 'Authorization: Basic ' . base64_encode($this->authentication['username'] . ':' . $this->authentication['password']) . "\r\n";
        }

        if ($content) {
            $httpOptions['content'] = json_encode($content);
        }

        // set context and get contents
        $context = stream_context_create(array(
            'http' => $httpOptions,
            'ssl' => array('verify_peer' => true)
        ));

        // reset last request data
        $this->lastRequestMessage = $this->lastRequestMetadata = null;

        // is it the full url? or the one from the documentation?
        $urlPath = (strpos($urlPath, 'http') !== false) ? $urlPath : 'https://api.github.com' . $urlPath;

        $fh = fopen($urlPath, 'r', false, $context);
        $this->lastRequestBody = stream_get_contents($fh);
        $this->lastRequestMetadata = stream_get_meta_data($fh);
        fclose($fh);

        $this->lastRequestBodyDecoded = json_decode($this->lastRequestBody, true);

        // get rate limit information
        if (get_class($this) !== __NAMESPACE__ . '\RateLimitAPI') {
            foreach ($this->lastRequestMetadata['wrapper_data'] as $index => $header) {
                if (strpos($header, 'X-RateLimit-Remaining:') === 0) {
                    $rlRemaining = substr($header, 23);
                }
                if (strpos($header, 'X-RateLimit-Limit') === 0) {
                    $rlLimit = substr($header, 18);
                }
            }
            if (isset($rlRemaining) && isset($rlLimit) && isset($this->accessToken)) {
                self::$apiData['rate'][$this->accessToken] = array('remaining' => $rlRemaining, 'limit' => $rlLimit);
            }
        }

        if (substr($this->lastRequestMetadata['wrapper_data'][0], 9, 3) !== '200') {
            return false;
        }

        return $this->lastRequestBodyDecoded;
    }

    protected function createEntity($type, $data)
    {
        $entity = new $type;
        $this->synchronizeEntity($entity, $data);
        return $entity;
    }

    protected function synchronizeEntity(AbstractEntity $entity, array $data)
    {
        $ro = new \ReflectionObject($entity);

        if ($ro->hasProperty('propertyEntityMap')) {
            $rMapProp = $ro->getProperty('propertyEntityMap');
            $rMapProp->setAccessible(true);
            $entityMap = $rMapProp->getValue($entity);
        } else {
            $entityMap = array();
        }

        foreach ($data as $dName => $dValue) {
            $dName = lcfirst(str_replace(' ' , '', ucwords(str_replace('_', ' ', $dName))));

            if ($ro->hasProperty($dName)) {
                $prop = $ro->getProperty($dName);
                $prop->setAccessible(true);

                if (isset($entityMap[$dName]) && is_array($dValue)) {
                    $subEntityClass = $entityMap[$dName];
                    $subEntity = new $subEntityClass;
                    $this->synchronizeEntity($subEntity, $dValue);
                    $prop->setValue($entity, $subEntity);
                } else {
                    $prop->setValue($entity, $dValue);
                }
            }
        }
    }

    protected function createArrayFromUpdatedProperties(AbstractEntity $entity)
    {
        $aProperties = array();
        foreach ($entity->getUpdatedProperties() as $name) {
            $property = preg_replace(
                '/(^|[a-z])([A-Z])/',
                '\\1_\\2',
                $name
            );
            $aProperties[$property] = $entity->{'get' . $name}();
        }
        return $aProperties;
    }

    protected function processParameters($validParameters, $parameters)
    {
        $cleanParameters = array();
        foreach ($parameters as $n => $v) {
            if (array_key_exists($n, $validParameters)) {
                $cleanParameters[$n] = $v;
            }
        }
        return $cleanParameters;
    }

}