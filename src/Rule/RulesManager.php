<?php
namespace ProjectLint\Rule;

use ProjectLint\Resource\AbstractResource;
use Symfony\Component\Yaml\Parser;

class RulesManager
{
    protected $errors = array();

    protected $projectPath;

    protected $rules = array(
        'settings'  => array(),
        'classes'   => array(),
        'resources' => array(),
    );

    protected function getRelativePath($absolutePath)
    {
        $projectPathLength = strlen($this->projectPath);
        return substr($absolutePath, $projectPathLength);
    }

    protected function getResourcesRules(array $data, $path = '')
    {
        $rules = array();

        foreach ($data as $key => $value) {
            if ('children' == $key) {
                foreach ($value as $childName => $childValue) {
                    $childrenRules = $this->getResourcesRules(
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
        $this->projectPath = $projectPath;
    }

    public function addError($message, $expectedValue, $actualValue, AbstractResource $resource)
    {
        $this->errors[] = array(
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
        return $this->errors;
    }

    public function getRulesForResource(AbstractResource $resource)
    {
        $relativePath = $this->getRelativePath($resource->getRealPath());

        $rules = array();
        foreach ($this->rules['resources'] as $rule) {
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
            $this->rules['resources'] = $this->getResourcesRules(
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
