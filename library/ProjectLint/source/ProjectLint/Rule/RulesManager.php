<?php
namespace ProjectLint\Rule;

use ProjectLint\Resource\AbstractResource;
use Symfony\Component\Yaml\Parser;

class RulesManager
{
    protected $_errors = array();

    protected $_projectPath;

    protected $_rules = array(
        'settings'  => array(),
        'classes'   => array(),
        'resources' => array(),
    );

    protected function _getRelativePath($absolutePath)
    {
        $projectPathLength = strlen($this->_projectPath);
        return substr($absolutePath, $projectPathLength);
    }

    protected function _getResourcesRules(array $data, $path = '')
    {
        $rules = array();

        foreach ($data as $key => $value) {
            if ('children' == $key) {
                foreach ($value as $childName => $childValue) {
                    $childrenRules = $this->_getResourcesRules(
                        $childValue,
                        "$path/$childName"
                    );
                    $rules = array_merge($rules, $childrenRules);
                }
            } else {
                $rules[] = $this->createRule(
                    $key,
                    $path,
                    $value
                );
            }
        }

        return $rules;
    }

    public function __construct($projectPath)
    {
        $this->_projectPath = $projectPath;
    }

    public function addError($message, $expectedValue, $actualValue,
        AbstractResource $resource) {
        $this->_errors[] = array(
            'actualValue'   => $actualValue,
            'expectedValue' => $expectedValue,
            'message'       => $message,
            'resource'      => $resource,
        );
    }

    public function checkRules(AbstractResource $resource)
    {
        $rules = $this->getRulesForResource($resource);
        foreach ($rules as $rule) {
            $rule->check($resource);
        }
    }

    public function createRule($name, $selector, $data)
    {
        $className = '\\ProjectLint\\Rule\\' . ucfirst($name);

        return new $className($this, $selector, $data);
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function getRulesForResource(AbstractResource $resource)
    {
        $relativePath = $this->_getRelativePath($resource->getRealPath());

        $rules = array();
        foreach ($this->_rules['resources'] as $rule) {
            if ($relativePath == $rule->getSelector()) {
                $rules[] = $rule;
            }
        }

        return $rules;
    }

    public function loadFromArray(array $data)
    {
        // Load resources specific rules
        if (array_key_exists('resources', $data)) {
            $this->_rules['resources'] = $this->_getResourcesRules(
                $data['resources']
            );
        }

        return $this;
    }
    public function loadFromFile($filepath)
    {
        $yamlParser = new Parser();
        $data       = $yamlParser->parse(file_get_contents($filepath));

        return $this->loadFromArray($data);
    }
}