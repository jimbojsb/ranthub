<?php
/**
 * P Framework
 * @link http://github.com/pframework
 * @license UNLICENSE http://unlicense.org/UNLICENSE
 * @copyright Public Domain
 * @author Ralph Schindler <ralph@ralphschindler.com>
 */

namespace P\Router;

class HttpRoute implements RouteInterface
{
    /**
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
     */
    protected $validHttpMethods = array('OPTIONS', 'GET', 'HEAD', 'PUT', 'POST', 'DELETE', 'TRACE', 'CONNECT');

    protected $specificationMethods = null;
    protected $specificationUri = null;

    protected $parameterDefaults = array();
    protected $parameterValidators = array();

    public function __construct($specification, array $parameterDefaults = array(), array $parameterValidators = array())
    {
        $this->parseSpecification($specification);
        $this->parameterDefaults = $parameterDefaults;
        $this->parameterValidators = $parameterValidators;
    }

    public function match(SourceInterface $source)
    {
        /* @var $source HttpSourceInterface */
        if (!$source instanceof HttpSource) {
            return false;
        }

        list($reqMethod, $reqUri, $reqProtocol) = $source->getCommand();

        $parameterCount = -1;

        // replace parameters
        $matchRegex = preg_replace('#:([\w-_]+)#', '(?P<$1>[\w-_]+)', $this->specificationUri, -1, $parameterCount);

        // replace url suffixing, if present
        $matchRegex = preg_replace('#/\*$#', '(?P<parameters>/[\w-_/]*)*', $matchRegex);

        if (strpos($reqUri, '?') !== false) {
            $reqUri = substr($reqUri, 0, strpos($reqUri, '?'));
        }

        if (!preg_match_all('|' . $matchRegex . '$|', $reqUri, $matches)) {
            return false;
        }

        $parameters = $this->parameterDefaults;

        foreach ($matches as $parameterName => $parameterValue) {
            if (is_int($parameterName)) {
                continue;
            }

            // validate:
            if (isset($this->parameterValidators[$parameterName])) {
                // @todo
                // return false;
            }

            $parameters[$parameterName] = $parameterValue[0];
        }

        return $parameters;
    }

    public function assemble($parameters)
    {
        $parameterCount = -1;

        // replace parameters
        $matchRegex = preg_replace('#(/:[\w-_]+/)#', '%s', $this->specification, -1, $parameterCount);

        // replace url suffixing, if present
        $matchRegex = preg_replace('#/\*$#', '(?P<parameters>/[\w-_/]*)*', $matchRegex);

        if (!preg_match_all('|' . $matchRegex . '|', $source->getUri(), $matches)) {
            return false;
        }

        $parameters = $this->parameterDefaults;

        foreach ($matches as $parameterName => $parameterValue) {
            if (is_int($parameterName)) {
                continue;
            }

            // validate:
            if (isset($this->parameterValidators[$parameterName])) {
                // @todo
                // return false;
            }

            $parameters[$parameterName] = $parameterValue[0];
        }

        return $parameters;
    }

    protected function parseSpecification($specification)
    {
        $methods = array();

        $reValidHttp = implode('|', $this->validHttpMethods);

        if (preg_match('#^(' . $reValidHttp . ')#', $specification)) {
            // has method
            list($methods, $uri) = explode(' ', $specification, 2);
            $this->specificationMethods = explode(',', $methods);
            $this->specificationUri = $uri;
        } else {
            $this->specificationMethods = $this->validHttpMethods;
            $this->specificationUri = $specification;
        }

    }

}